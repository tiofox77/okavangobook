import React, { useEffect, useMemo, useRef, useState } from 'react';

/**
 * Ilha React: seletor de intervalo de datas (estilo Booking/Airbnb).
 * Progressive enhancement — sincroniza com os <input type="date"> nativos
 * (que ficam escondidos mas continuam a alimentar o Livewire via wire:model).
 */

const MONTHS = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
const WEEKDAYS = ['S', 'T', 'Q', 'Q', 'S', 'S', 'D']; // seg..dom
const DAY_NAMES = ['dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sáb'];

const iso = (d) => `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
const fromIso = (s) => { if (!s) return null; const [y, m, d] = s.split('-').map(Number); return new Date(y, m - 1, d); };
const sameDay = (a, b) => a && b && a.getTime() === b.getTime();
const fmtShort = (d) => d ? `${DAY_NAMES[d.getDay()]}, ${String(d.getDate()).padStart(2, '0')} ${MONTHS[d.getMonth()].slice(0, 3).toLowerCase()}` : '';

function monthMatrix(year, month) {
    const first = new Date(year, month, 1);
    const offset = (first.getDay() + 6) % 7; // semana começa à segunda
    const days = new Date(year, month + 1, 0).getDate();
    const cells = [];
    for (let i = 0; i < offset; i++) cells.push(null);
    for (let d = 1; d <= days; d++) cells.push(new Date(year, month, d));
    return cells;
}

function Month({ year, month, start, end, hover, min, onPick, onHover }) {
    const cells = useMemo(() => monthMatrix(year, month), [year, month]);
    const rangeEnd = end || hover;
    const inRange = (d) => start && rangeEnd && !sameDay(start, rangeEnd)
        && d > (start < rangeEnd ? start : rangeEnd) && d < (start < rangeEnd ? rangeEnd : start);

    return (
        <div className="w-64 select-none">
            <div className="text-center font-semibold text-gray-800 dark:text-gray-100 mb-2">
                {MONTHS[month]} {year}
            </div>
            <div className="grid grid-cols-7 text-center text-xs text-gray-400 mb-1">
                {WEEKDAYS.map((w, i) => <div key={i} className="py-1">{w}</div>)}
            </div>
            <div className="grid grid-cols-7 text-center text-sm">
                {cells.map((d, i) => {
                    if (!d) return <div key={i} />;
                    const disabled = d < min;
                    const isStart = sameDay(d, start);
                    const isEnd = sameDay(d, end);
                    const mid = inRange(d);
                    return (
                        <button
                            key={i}
                            type="button"
                            disabled={disabled}
                            onClick={() => onPick(d)}
                            onMouseEnter={() => onHover(d)}
                            className={[
                                'h-9 w-9 mx-auto flex items-center justify-center rounded-full transition-colors',
                                disabled ? 'text-gray-300 dark:text-gray-600 cursor-not-allowed' : 'cursor-pointer',
                                (isStart || isEnd) ? 'bg-primary text-white font-bold' : '',
                                mid ? 'bg-blue-100 dark:bg-blue-900/40 text-primary dark:text-blue-300 rounded-none' : '',
                                (!disabled && !isStart && !isEnd && !mid) ? 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200' : '',
                            ].join(' ')}
                        >
                            {d.getDate()}
                        </button>
                    );
                })}
            </div>
        </div>
    );
}

export default function DateRangePicker({ startInput, endInput, minDate }) {
    const min = fromIso(minDate) || new Date();
    const [start, setStart] = useState(fromIso(startInput.value));
    const [end, setEnd] = useState(fromIso(endInput.value));
    const [hover, setHover] = useState(null);
    const [open, setOpen] = useState(false);
    const base = start || min;
    const [view, setView] = useState({ y: base.getFullYear(), m: base.getMonth() });
    const rootRef = useRef(null);

    const nights = start && end ? Math.round((end - start) / 86400000) : 0;

    const sync = (s, e) => {
        const fire = (input, value) => {
            input.value = value;
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
        };
        if (s) fire(startInput, iso(s));
        if (e) fire(endInput, iso(e));
    };

    const pick = (d) => {
        if (!start || (start && end)) {
            setStart(d); setEnd(null); setHover(null);
        } else if (d <= start) {
            setStart(d); setEnd(null);
        } else {
            setEnd(d);
            sync(start, d);
            setTimeout(() => setOpen(false), 180);
        }
    };

    useEffect(() => {
        const away = (e) => { if (rootRef.current && !rootRef.current.contains(e.target)) setOpen(false); };
        const esc = (e) => { if (e.key === 'Escape') setOpen(false); };
        document.addEventListener('mousedown', away);
        document.addEventListener('keydown', esc);
        return () => { document.removeEventListener('mousedown', away); document.removeEventListener('keydown', esc); };
    }, []);

    const nav = (delta) => setView(({ y, m }) => {
        const d = new Date(y, m + delta, 1);
        return { y: d.getFullYear(), m: d.getMonth() };
    });
    const next = new Date(view.y, view.m + 1, 1);

    return (
        <div ref={rootRef} className="relative">
            <button
                type="button"
                onClick={() => setOpen(!open)}
                aria-expanded={open}
                className="w-full flex items-center gap-3 pl-3 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-left focus:outline-none focus:ring-2 focus:ring-primary hover:border-primary/50 transition-colors"
            >
                <i className="far fa-calendar-alt text-gray-400" aria-hidden="true"></i>
                <span className="flex-1 text-gray-800 dark:text-gray-100 text-sm sm:text-base">
                    {start ? fmtShort(start) : 'Check-in'}
                    <span className="mx-2 text-gray-400">→</span>
                    {end ? fmtShort(end) : 'Check-out'}
                </span>
                {nights > 0 && (
                    <span className="text-xs bg-blue-100 dark:bg-blue-900/40 text-primary dark:text-blue-300 px-2 py-1 rounded-full whitespace-nowrap">
                        {nights} {nights === 1 ? 'noite' : 'noites'}
                    </span>
                )}
            </button>

            {open && (
                <div className="absolute z-50 mt-2 left-0 right-0 sm:right-auto bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl p-4 ks-fade-in">
                    <div className="flex items-center justify-between mb-2">
                        <button type="button" onClick={() => nav(-1)} aria-label="Mês anterior"
                                className="h-8 w-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300">
                            <i className="fas fa-chevron-left text-sm"></i>
                        </button>
                        <button type="button" onClick={() => nav(1)} aria-label="Mês seguinte"
                                className="h-8 w-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300">
                            <i className="fas fa-chevron-right text-sm"></i>
                        </button>
                    </div>
                    <div className="flex gap-6 flex-col sm:flex-row" onMouseLeave={() => setHover(null)}>
                        <Month year={view.y} month={view.m} start={start} end={end} hover={hover} min={min} onPick={pick} onHover={setHover} />
                        <div className="hidden sm:block">
                            <Month year={next.getFullYear()} month={next.getMonth()} start={start} end={end} hover={hover} min={min} onPick={pick} onHover={setHover} />
                        </div>
                    </div>
                    <div className="flex items-center justify-between mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <button type="button"
                                onClick={() => { setStart(null); setEnd(null); }}
                                className="text-sm text-gray-500 dark:text-gray-400 hover:text-primary">
                            Limpar
                        </button>
                        <span className="text-sm text-gray-600 dark:text-gray-300">
                            {start && !end ? 'Escolha a data de check-out' : nights > 0 ? `${nights} ${nights === 1 ? 'noite' : 'noites'}` : 'Escolha as datas'}
                        </span>
                    </div>
                </div>
            )}
        </div>
    );
}
