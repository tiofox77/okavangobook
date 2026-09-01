import React, { useCallback, useEffect, useRef, useState } from 'react';
import { motion } from 'framer-motion';

/**
 * Ilha React: slider de preço com dois punhos (filtro da pesquisa).
 * Sincroniza os inputs nativos #min-price/#max-price (wire:model) e chama
 * applyPriceFilter no componente Livewire ao largar o punho.
 */

const fmt = (v) => 'AKZ ' + new Intl.NumberFormat('pt-PT').format(v);

/* Punho definido a nível de módulo: definir componentes DENTRO do render
   cria um tipo novo a cada render e o React remonta o nó (perdia o foco). */
function Thumb({ which, value, min, max, pct, dragging, onStart, onKey }) {
    return (
        <motion.div
            role="slider"
            tabIndex={0}
            aria-valuemin={min}
            aria-valuemax={max}
            aria-valuenow={value}
            aria-label={which === 'lo' ? 'Preço mínimo' : 'Preço máximo'}
            onPointerDown={(e) => { e.preventDefault(); onStart(which); }}
            onKeyDown={(e) => onKey(e, which)}
            animate={{ scale: dragging ? 1.25 : 1 }}
            transition={{ type: 'spring', stiffness: 500, damping: 28 }}
            className="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 h-5 w-5 rounded-full bg-white border-2 border-primary shadow-md cursor-grab active:cursor-grabbing focus:outline-none focus:ring-2 focus:ring-primary/50 z-10"
            style={{ left: pct + '%' }}
        >
            {dragging && (
                <motion.div
                    initial={{ opacity: 0, y: 4, scale: 0.8 }}
                    animate={{ opacity: 1, y: 0, scale: 1 }}
                    className="absolute -top-9 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded-md whitespace-nowrap shadow-lg"
                >
                    {fmt(value)}
                </motion.div>
            )}
        </motion.div>
    );
}

export default function PriceRangeSlider({ minInput, maxInput, min = 0, max = 1000000, step = 5000, wireEl }) {
    const [lo, setLo] = useState(Number(minInput.value) || min);
    const [hi, setHi] = useState(Number(maxInput.value) || max);
    const [drag, setDrag] = useState(null); // 'lo' | 'hi' | null
    const trackRef = useRef(null);
    const state = useRef({ lo, hi });
    state.current = { lo, hi };

    const pct = (v) => ((v - min) / (max - min)) * 100;

    const valueFromClientX = useCallback((clientX) => {
        const r = trackRef.current.getBoundingClientRect();
        const ratio = Math.min(1, Math.max(0, (clientX - r.left) / r.width));
        return Math.round((min + ratio * (max - min)) / step) * step;
    }, [min, max, step]);

    const apply = useCallback((l, h) => {
        const fire = (input, value) => {
            input.value = String(value);
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
        };
        fire(minInput, l);
        fire(maxInput, h);
        try {
            const id = wireEl?.getAttribute('wire:id');
            if (id && window.Livewire) window.Livewire.find(id)?.call('applyPriceFilter');
        } catch (e) { /* silencioso */ }
    }, [minInput, maxInput, wireEl]);

    useEffect(() => {
        if (!drag) return;
        const move = (e) => {
            const x = e.touches ? e.touches[0].clientX : e.clientX;
            const v = valueFromClientX(x);
            if (drag === 'lo') setLo(Math.min(v, state.current.hi - step));
            else setHi(Math.max(v, state.current.lo + step));
        };
        const up = () => {
            setDrag(null);
            apply(state.current.lo, state.current.hi);
        };
        window.addEventListener('pointermove', move);
        window.addEventListener('pointerup', up);
        window.addEventListener('touchmove', move, { passive: false });
        window.addEventListener('touchend', up);
        return () => {
            window.removeEventListener('pointermove', move);
            window.removeEventListener('pointerup', up);
            window.removeEventListener('touchmove', move);
            window.removeEventListener('touchend', up);
        };
    }, [drag, step, valueFromClientX, apply]);

    const onKey = useCallback((e, which) => {
        const delta = e.key === 'ArrowRight' ? step : e.key === 'ArrowLeft' ? -step : 0;
        if (!delta) return;
        e.preventDefault();
        const { lo: l, hi: h } = state.current;
        if (which === 'lo') {
            const v = Math.min(Math.max(min, l + delta), h - step);
            setLo(v);
            apply(v, h);
        } else {
            const v = Math.max(Math.min(max, h + delta), l + step);
            setHi(v);
            apply(l, v);
        }
    }, [min, max, step, apply]);

    return (
        <div className="pt-2 pb-1 px-1">
            <div ref={trackRef} className="relative h-8 flex items-center touch-none">
                <div className="absolute inset-x-0 h-1.5 rounded-full bg-gray-200 dark:bg-gray-600"></div>
                <div
                    className="absolute h-1.5 rounded-full bg-gradient-to-r from-primary to-blue-500"
                    style={{ left: pct(lo) + '%', right: (100 - pct(hi)) + '%' }}
                ></div>
                <Thumb which="lo" value={lo} min={min} max={max} pct={pct(lo)} dragging={drag === 'lo'} onStart={setDrag} onKey={onKey} />
                <Thumb which="hi" value={hi} min={min} max={max} pct={pct(hi)} dragging={drag === 'hi'} onStart={setDrag} onKey={onKey} />
            </div>
            <div className="flex justify-between mt-1 text-xs text-gray-600 dark:text-gray-300 font-medium">
                <span>{fmt(lo)}</span>
                <span>{hi >= max ? fmt(max) + '+' : fmt(hi)}</span>
            </div>
        </div>
    );
}
