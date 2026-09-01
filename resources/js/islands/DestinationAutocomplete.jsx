import React, { useCallback, useEffect, useMemo, useRef, useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';

/**
 * Ilha React: autocomplete instantâneo de destino (home + barra da pesquisa).
 * Junta destinos (API /locations?q=) e hotéis (/hotels?q=) com cache local,
 * navegação por teclado e dropdown animado. Sincroniza o input nativo
 * (wire:model) e, na home, chama selectLocation(name, id) no Livewire.
 */

const cache = new Map();

async function fetchSuggestions(q) {
    if (cache.has(q)) return cache.get(q);
    const [locRes, hotRes] = await Promise.all([
        fetch(`/api/v1/locations?q=${encodeURIComponent(q)}&limit=5`, { headers: { Accept: 'application/json' } }).then(r => r.ok ? r.json() : { data: [] }).catch(() => ({ data: [] })),
        fetch(`/api/v1/hotels?q=${encodeURIComponent(q)}&per_page=5`, { headers: { Accept: 'application/json' } }).then(r => r.ok ? r.json() : { data: [] }).catch(() => ({ data: [] })),
    ]);
    const items = [
        ...(locRes.data || []).map(l => ({ type: 'location', id: l.id, name: l.name, sub: l.province_name || l.province, url: null })),
        ...(hotRes.data || []).map(h => ({ type: 'hotel', id: h.id, name: h.name, sub: (h.location?.name ? h.location.name + ', ' : '') + (h.location?.province || ''), url: h.url, stars: h.stars })),
    ];
    cache.set(q, items);
    return items;
}

function Highlight({ text, query }) {
    const i = text.toLowerCase().indexOf(query.toLowerCase());
    if (i === -1 || !query) return <>{text}</>;
    return (
        <>
            {text.slice(0, i)}
            <span className="font-bold text-primary dark:text-blue-300">{text.slice(i, i + query.length)}</span>
            {text.slice(i + query.length)}
        </>
    );
}

export default function DestinationAutocomplete({ nativeInput, wireEl, mode, dateIds }) {
    const [value, setValue] = useState(nativeInput.value || '');
    const [items, setItems] = useState([]);
    const [open, setOpen] = useState(false);
    const [active, setActive] = useState(-1);
    const [loading, setLoading] = useState(false);
    const rootRef = useRef(null);
    const timer = useRef(null);

    const syncNative = useCallback((text) => {
        nativeInput.value = text;
        nativeInput.dispatchEvent(new Event('input', { bubbles: true }));
        nativeInput.dispatchEvent(new Event('change', { bubbles: true }));
    }, [nativeInput]);

    const onChange = (e) => {
        const v = e.target.value;
        setValue(v);
        setActive(-1);
        clearTimeout(timer.current);
        if (v.trim().length < 2) { setItems([]); setOpen(false); syncNative(v); return; }
        setLoading(true);
        timer.current = setTimeout(async () => {
            const res = await fetchSuggestions(v.trim());
            setItems(res);
            setOpen(true);
            setLoading(false);
            syncNative(v);
        }, 250);
    };

    const datesQuery = useCallback(() => {
        const ci = document.getElementById(dateIds?.[0] || '')?.value;
        const co = document.getElementById(dateIds?.[1] || '')?.value;
        return ci && co ? `?check_in=${ci}&check_out=${co}` : '';
    }, [dateIds]);

    const choose = useCallback((item) => {
        if (!item) return;
        if (item.type === 'hotel' && item.url) {
            window.location.href = item.url + datesQuery();
            return;
        }
        setValue(item.name);
        setOpen(false);
        syncNative(item.name);
        if (mode === 'home') {
            try {
                const id = wireEl?.getAttribute('wire:id');
                if (id && window.Livewire) window.Livewire.find(id)?.call('selectLocation', item.name, item.id);
            } catch (e) { /* silencioso */ }
        }
    }, [mode, wireEl, syncNative, datesQuery]);

    const onKeyDown = (e) => {
        if (!open || !items.length) {
            if (e.key === 'Enter') { syncNative(value); } // segue para o submit normal
            return;
        }
        if (e.key === 'ArrowDown') { e.preventDefault(); setActive(a => (a + 1) % items.length); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); setActive(a => (a - 1 + items.length) % items.length); }
        else if (e.key === 'Enter') { e.preventDefault(); active >= 0 ? choose(items[active]) : (syncNative(value), setOpen(false)); }
        else if (e.key === 'Escape') { setOpen(false); }
    };

    useEffect(() => {
        const away = (e) => { if (rootRef.current && !rootRef.current.contains(e.target)) setOpen(false); };
        document.addEventListener('mousedown', away);
        return () => document.removeEventListener('mousedown', away);
    }, []);

    const grouped = useMemo(() => ({
        locations: items.filter(i => i.type === 'location'),
        hotels: items.filter(i => i.type === 'hotel'),
    }), [items]);

    let flat = -1;

    const renderItem = (item) => {
        flat += 1;
        const idx = flat;
        const isActive = idx === active;
        return (
            <button
                key={item.type + item.id}
                type="button"
                onMouseEnter={() => setActive(idx)}
                onClick={() => choose(item)}
                className={'w-full flex items-center gap-3 px-4 py-2.5 text-left transition-colors ' + (isActive ? 'bg-blue-50 dark:bg-blue-900/30' : 'hover:bg-gray-50 dark:hover:bg-gray-700/60')}
            >
                <span className={'h-8 w-8 flex items-center justify-center rounded-full flex-shrink-0 ' + (item.type === 'hotel' ? 'bg-blue-100 dark:bg-blue-900/40 text-primary dark:text-blue-300' : 'bg-orange-100 dark:bg-orange-900/40 text-orange-600 dark:text-orange-300')}>
                    <i className={'fas ' + (item.type === 'hotel' ? 'fa-hotel' : 'fa-map-marker-alt') + ' text-sm'}></i>
                </span>
                <span className="min-w-0 flex-1">
                    <span className="block truncate text-sm text-gray-800 dark:text-gray-100"><Highlight text={item.name} query={value} /></span>
                    {item.sub && <span className="block truncate text-xs text-gray-500 dark:text-gray-400">{item.sub}</span>}
                </span>
                {item.type === 'hotel' && item.stars > 0 && (
                    <span className="text-xs text-amber-500 flex-shrink-0">{'★'.repeat(Math.min(5, item.stars))}</span>
                )}
            </button>
        );
    };

    return (
        <div ref={rootRef} className="relative">
            <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i className="fas fa-map-marker-alt text-gray-400"></i>
                </div>
                <input
                    type="text"
                    value={value}
                    onChange={onChange}
                    onKeyDown={onKeyDown}
                    onFocus={() => { if (items.length) setOpen(true); }}
                    placeholder="Hotel ou destino… (ex.: Luanda)"
                    aria-label="Destino"
                    autoComplete="off"
                    className="w-full pl-10 pr-9 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                />
                <div className="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    {loading
                        ? <i className="fas fa-circle-notch fa-spin text-gray-400 text-sm"></i>
                        : value && <i className="fas fa-magnifying-glass text-gray-300 text-sm"></i>}
                </div>
            </div>

            <AnimatePresence>
                {open && (
                    <motion.div
                        initial={{ opacity: 0, y: -6, scale: 0.98 }}
                        animate={{ opacity: 1, y: 0, scale: 1 }}
                        exit={{ opacity: 0, y: -4, scale: 0.985 }}
                        transition={{ type: 'spring', stiffness: 480, damping: 32 }}
                        className="absolute z-40 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl overflow-hidden origin-top"
                    >
                        {items.length === 0 ? (
                            <div className="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                Sem resultados para “{value}”.
                            </div>
                        ) : (
                            <div className="max-h-80 overflow-y-auto py-1">
                                {grouped.locations.length > 0 && (
                                    <div>
                                        <p className="px-4 pt-2 pb-1 text-[11px] font-semibold uppercase tracking-wide text-gray-400">Destinos</p>
                                        {grouped.locations.map(renderItem)}
                                    </div>
                                )}
                                {grouped.hotels.length > 0 && (
                                    <div>
                                        <p className="px-4 pt-2 pb-1 text-[11px] font-semibold uppercase tracking-wide text-gray-400">Hotéis</p>
                                        {grouped.hotels.map(renderItem)}
                                    </div>
                                )}
                            </div>
                        )}
                    </motion.div>
                )}
            </AnimatePresence>
        </div>
    );
}
