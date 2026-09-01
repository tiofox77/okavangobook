import React, { useMemo, useState } from 'react';
import { motion, AnimatePresence, LayoutGroup } from 'framer-motion';

/**
 * Ilha React: explorador das 18 províncias (/destinos).
 * Pesquisa instantânea, ordenação e filtro "só com alojamentos", com
 * reordenação animada (layout animations do framer-motion).
 * Os dados vêm do Blade (SSR-friendly: sem JS, a grelha Blade é mostrada).
 */

const SORTS = [
    { key: 'popular', label: 'Mais alojamentos', icon: 'fa-fire' },
    { key: 'alphabetical', label: 'A–Z', icon: 'fa-arrow-down-a-z' },
    { key: 'price', label: 'Preço', icon: 'fa-tag' },
];

// Ponto como separador de milhares, igual ao resto do site (number_format PHP).
// O Intl pt-PT usa espaço fino e ficava "21 088" ao lado de "21.088".
const money = (v) => String(Math.round(v)).replace(/\B(?=(\d{3})+(?!\d))/g, '.');

function Card({ d, index }) {
    return (
        <motion.a
            layout
            href={d.url}
            initial={{ opacity: 0, y: 18 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ type: 'spring', stiffness: 320, damping: 30, delay: Math.min(index * 0.03, 0.3) }}
            whileHover={{ y: -6 }}
            className="group relative block rounded-2xl overflow-hidden shadow-md hover:shadow-2xl bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary"
        >
            <div className="relative h-48 overflow-hidden bg-gray-100 dark:bg-gray-700">
                <img
                    src={d.image}
                    alt={d.name}
                    loading="lazy"
                    className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    onError={(e) => { e.currentTarget.style.opacity = 0.3; }}
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent" />
                <div className="absolute bottom-0 inset-x-0 p-4">
                    <h3 className="text-xl font-bold text-white drop-shadow">{d.name}</h3>
                    <p className="text-white/80 text-xs">
                        {d.locations} {d.locations === 1 ? 'localidade' : 'localidades'}
                    </p>
                </div>
                {d.hotels > 0 && (
                    <span className="absolute top-3 right-3 bg-white/95 dark:bg-gray-900/90 text-primary dark:text-blue-300 text-xs font-bold px-2.5 py-1 rounded-full shadow">
                        {d.hotels} {d.hotels === 1 ? 'alojamento' : 'alojamentos'}
                    </span>
                )}
            </div>
            <div className="p-4">
                <p className="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 min-h-[2.5rem]">
                    {d.description || `Descubra ${d.name} e os seus alojamentos.`}
                </p>
                <div className="flex items-center justify-between mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                    <span className="text-sm font-semibold text-gray-800 dark:text-gray-100">
                        {d.minPrice ? <>desde <span className="text-primary dark:text-blue-300">{money(d.minPrice)} Kz</span></> : <span className="text-gray-400 font-normal">Ver opções</span>}
                    </span>
                    <span className="text-primary dark:text-blue-300 text-sm font-medium inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                        Explorar <i className="fas fa-arrow-right text-xs" />
                    </span>
                </div>
            </div>
        </motion.a>
    );
}

export default function DestinationsExplorer({ destinations }) {
    const [q, setQ] = useState('');
    const [sort, setSort] = useState('popular');
    const [onlyWith, setOnlyWith] = useState(false);

    const list = useMemo(() => {
        const term = q.trim().toLowerCase();
        let out = destinations.filter(d =>
            (!term || d.name.toLowerCase().includes(term) || (d.description || '').toLowerCase().includes(term))
            && (!onlyWith || d.hotels > 0)
        );
        out = [...out].sort((a, b) => {
            if (sort === 'alphabetical') return a.name.localeCompare(b.name, 'pt');
            if (sort === 'price') {
                if (!a.minPrice && !b.minPrice) return b.hotels - a.hotels;
                if (!a.minPrice) return 1;
                if (!b.minPrice) return -1;
                return a.minPrice - b.minPrice;
            }
            return b.hotels - a.hotels;
        });
        return out;
    }, [destinations, q, sort, onlyWith]);

    const totalHotels = useMemo(() => destinations.reduce((s, d) => s + d.hotels, 0), [destinations]);

    return (
        <div>
            {/* Barra de controlo */}
            <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-8">
                <div className="flex flex-col lg:flex-row lg:items-center gap-3">
                    <div className="relative flex-1">
                        <i className="fas fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                        <input
                            value={q}
                            onChange={(e) => setQ(e.target.value)}
                            placeholder="Procurar província… (ex.: Benguela)"
                            aria-label="Procurar província"
                            className="w-full pl-10 pr-9 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary"
                        />
                        {q && (
                            <button onClick={() => setQ('')} aria-label="Limpar"
                                    className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i className="fas fa-times" />
                            </button>
                        )}
                    </div>

                    <div className="flex items-center gap-2 flex-wrap">
                        {SORTS.map(s => (
                            <motion.button
                                key={s.key}
                                whileTap={{ scale: 0.94 }}
                                onClick={() => setSort(s.key)}
                                className={'relative px-3 py-2 rounded-lg text-sm font-medium transition-colors ' +
                                    (sort === s.key ? 'text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700')}
                            >
                                {sort === s.key && (
                                    <motion.span layoutId="ks-sort-pill" className="absolute inset-0 bg-primary rounded-lg"
                                                 transition={{ type: 'spring', stiffness: 420, damping: 32 }} />
                                )}
                                <span className="relative flex items-center gap-1.5">
                                    <i className={'fas ' + s.icon + ' text-xs'} />{s.label}
                                </span>
                            </motion.button>
                        ))}
                        <label className="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-300 cursor-pointer rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <input type="checkbox" checked={onlyWith} onChange={(e) => setOnlyWith(e.target.checked)} className="rounded text-primary" />
                            Só com alojamentos
                        </label>
                    </div>
                </div>

                <p className="text-xs text-gray-500 dark:text-gray-400 mt-3">
                    <AnimatePresence mode="popLayout" initial={false}>
                        <motion.span key={list.length} initial={{ opacity: 0, y: -4 }} animate={{ opacity: 1, y: 0 }} exit={{ opacity: 0, y: 4 }} className="inline-block font-semibold text-gray-700 dark:text-gray-200">
                            {list.length}
                        </motion.span>
                    </AnimatePresence>
                    {' '}de {destinations.length} províncias · {totalHotels} alojamentos no total
                </p>
            </div>

            {/* Grelha com reordenação animada.
                Sem AnimatePresence/exit de propósito: com popLayout os cards
                filtrados ficavam no DOM à espera da animação de saída (o
                contador dizia "3 de 18" mas continuavam 18 cards visíveis).
                Desmontar já e animar só entrada+reposicionamento é fiável. */}
            <LayoutGroup>
                <motion.div layout className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    {list.map((d, i) => <Card key={d.slug} d={d} index={i} />)}
                </motion.div>
            </LayoutGroup>

            {list.length === 0 && (
                <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="text-center py-16">
                    <i className="fas fa-map-location-dot text-5xl text-gray-300 mb-4" />
                    <p className="text-gray-500 dark:text-gray-400">Nenhuma província encontrada para “{q}”.</p>
                    <button onClick={() => { setQ(''); setOnlyWith(false); }} className="mt-3 text-primary font-medium hover:underline">
                        Limpar filtros
                    </button>
                </motion.div>
            )}
        </div>
    );
}
