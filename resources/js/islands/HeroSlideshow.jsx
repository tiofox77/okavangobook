import React, { useCallback, useEffect, useRef, useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';

/**
 * Ilha React: slideshow do hero da home.
 * Crossfade + Ken Burns (zoom lento), barras de progresso que acompanham o
 * autoplay, legenda animada, swipe no telemóvel, pausa ao passar o rato e
 * respeito por prefers-reduced-motion. Substitui as transições Alpine
 * (que ficam como fallback sem JS). Não toca no formulário Livewire.
 */

const DURATION = 7000;
const reduced = typeof window !== 'undefined'
    && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export default function HeroSlideshow({ slides }) {
    const [index, setIndex] = useState(0);
    const [paused, setPaused] = useState(false);
    const [progress, setProgress] = useState(0);
    const raf = useRef(null);
    const startedAt = useRef(performance.now());

    const go = useCallback((next) => {
        setIndex((i) => (next + slides.length) % slides.length);
        startedAt.current = performance.now();
        setProgress(0);
    }, [slides.length]);

    // Autoplay conduzido por rAF (a barra e a troca partilham o mesmo relógio)
    useEffect(() => {
        if (reduced || slides.length < 2) return;
        const tick = (t) => {
            if (!paused) {
                const p = Math.min(1, (t - startedAt.current) / DURATION);
                setProgress(p);
                if (p >= 1) {
                    startedAt.current = t;
                    setProgress(0);
                    setIndex((i) => (i + 1) % slides.length);
                }
            } else {
                startedAt.current = t - progress * DURATION;
            }
            raf.current = requestAnimationFrame(tick);
        };
        raf.current = requestAnimationFrame(tick);
        return () => cancelAnimationFrame(raf.current);
    }, [paused, progress, slides.length]);

    // Setas do teclado quando o hero está focado
    const onKeyDown = (e) => {
        if (e.key === 'ArrowRight') { e.preventDefault(); go(index + 1); }
        else if (e.key === 'ArrowLeft') { e.preventDefault(); go(index - 1); }
    };

    const slide = slides[index];

    return (
        <div
            className="absolute inset-0 overflow-hidden"
            onMouseEnter={() => setPaused(true)}
            onMouseLeave={() => setPaused(false)}
            onKeyDown={onKeyDown}
            tabIndex={-1}
            aria-live="polite"
        >
            <AnimatePresence initial={false}>
                <motion.div
                    key={index}
                    className="absolute inset-0"
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    exit={{ opacity: 0 }}
                    transition={{ duration: reduced ? 0 : 1.1, ease: 'easeInOut' }}
                >
                    <motion.img
                        src={slide.src}
                        alt={slide.alt}
                        fetchpriority={index === 0 ? 'high' : undefined}
                        className="w-full h-full object-cover"
                        initial={reduced ? false : { scale: 1.06 }}
                        animate={reduced ? undefined : { scale: 1.16 }}
                        transition={{ duration: (DURATION + 2500) / 1000, ease: 'linear' }}
                        draggable={false}
                    />
                </motion.div>
            </AnimatePresence>

            {/* Swipe layer (mobile) — não intercepta cliques do conteúdo */}
            {slides.length > 1 && (
                <motion.div
                    className="absolute inset-x-0 bottom-0 h-1/3 z-20 md:hidden"
                    drag="x"
                    dragConstraints={{ left: 0, right: 0 }}
                    dragElastic={0.35}
                    onDragEnd={(e, info) => {
                        if (info.offset.x < -70) go(index + 1);
                        else if (info.offset.x > 70) go(index - 1);
                    }}
                    style={{ touchAction: 'pan-y' }}
                    aria-hidden="true"
                />
            )}

            {/* Legenda do slide */}
            <div className="absolute bottom-4 left-4 z-30 pointer-events-none">
                <AnimatePresence mode="wait">
                    <motion.p
                        key={index}
                        initial={{ opacity: 0, y: 10 }}
                        animate={{ opacity: 1, y: 0 }}
                        exit={{ opacity: 0, y: -6 }}
                        transition={{ duration: 0.45 }}
                        className="text-white/90 text-xs sm:text-sm bg-black/35 backdrop-blur-sm px-3 py-1.5 rounded-full"
                    >
                        <i className="fas fa-location-dot mr-1.5 text-[10px]" aria-hidden="true" />
                        {slide.caption}
                    </motion.p>
                </AnimatePresence>
            </div>

            {/* Barras de progresso / navegação */}
            {slides.length > 1 && (
                <div className="absolute bottom-4 right-4 z-30 flex items-center gap-2">
                    {slides.map((s, i) => (
                        <button
                            key={i}
                            type="button"
                            onClick={() => go(i)}
                            aria-label={`Mostrar ${s.caption}`}
                            aria-current={i === index}
                            className="group relative h-1.5 rounded-full bg-white/35 hover:bg-white/55 transition-all overflow-hidden"
                            style={{ width: i === index ? 44 : 18 }}
                        >
                            {i === index && (
                                <span
                                    className="absolute inset-y-0 left-0 bg-white rounded-full"
                                    style={{ width: `${Math.round(progress * 100)}%` }}
                                />
                            )}
                        </button>
                    ))}
                    <button
                        type="button"
                        onClick={() => setPaused((p) => !p)}
                        aria-label={paused ? 'Retomar slideshow' : 'Pausar slideshow'}
                        className="ml-1 h-7 w-7 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/35 text-white text-xs transition-colors"
                    >
                        <i className={'fas ' + (paused ? 'fa-play' : 'fa-pause')} />
                    </button>
                </div>
            )}
        </div>
    );
}
