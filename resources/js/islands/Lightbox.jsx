import React, { useCallback, useEffect, useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';

/**
 * Ilha React: Lightbox de imagens das propriedades (framer-motion).
 * Corrige os bugs do viewer antigo: scroll-lock do fundo, setas do teclado,
 * swipe no telemóvel, thumbnails, transições entre imagens e zero distorção
 * (object-contain sempre). Exposto como window.KiandaLightbox.open(images, i).
 */

const swipeConfidence = 90;

export default function Lightbox() {
    const [state, setState] = useState({ open: false, images: [], index: 0 });
    const [dir, setDir] = useState(0);

    // API global (o Alpine openImageViewer delega para aqui)
    useEffect(() => {
        window.KiandaLightbox = {
            open(images, index = 0) {
                const list = (Array.isArray(images) ? images : []).filter(Boolean);
                if (!list.length) return;
                setDir(0);
                setState({ open: true, images: list, index: Math.min(Math.max(0, index), list.length - 1) });
            },
        };
        return () => { delete window.KiandaLightbox; };
    }, []);

    const close = useCallback(() => setState(s => ({ ...s, open: false })), []);
    const go = useCallback((delta) => {
        setDir(delta);
        setState(s => ({ ...s, index: (s.index + delta + s.images.length) % s.images.length }));
    }, []);

    // scroll-lock + teclado enquanto aberto
    useEffect(() => {
        if (!state.open) return;
        const prev = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        const key = (e) => {
            if (e.key === 'Escape') close();
            else if (e.key === 'ArrowRight') go(1);
            else if (e.key === 'ArrowLeft') go(-1);
        };
        window.addEventListener('keydown', key);
        return () => {
            document.body.style.overflow = prev;
            window.removeEventListener('keydown', key);
        };
    }, [state.open, close, go]);

    // pré-carrega vizinhas
    useEffect(() => {
        if (!state.open) return;
        [1, -1].forEach(d => {
            const src = state.images[(state.index + d + state.images.length) % state.images.length];
            if (src) { const im = new Image(); im.src = src; }
        });
    }, [state.open, state.index, state.images]);

    const { open, images, index } = state;
    const many = images.length > 1;

    return (
        <AnimatePresence>
            {open && (
                <motion.div
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    exit={{ opacity: 0 }}
                    transition={{ duration: 0.22 }}
                    className="fixed inset-0 z-[90] bg-black/95 flex flex-col select-none"
                    onClick={close}
                    role="dialog"
                    aria-modal="true"
                    aria-label="Galeria de imagens"
                >
                    {/* topo: contador + fechar */}
                    <div className="flex items-center justify-between p-4 text-white" onClick={(e) => e.stopPropagation()}>
                        <span className="text-sm bg-white/10 px-3 py-1 rounded-full">{index + 1} / {images.length}</span>
                        <motion.button whileTap={{ scale: 0.85 }} onClick={close} aria-label="Fechar"
                                className="h-10 w-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20">
                            <i className="fas fa-times text-lg"></i>
                        </motion.button>
                    </div>

                    {/* imagem central com slide + swipe */}
                    <div className="relative flex-1 flex items-center justify-center overflow-hidden px-2" onClick={(e) => e.stopPropagation()}>
                        {many && (
                            <motion.button whileTap={{ scale: 0.8 }} onClick={() => go(-1)} aria-label="Imagem anterior"
                                    className="absolute left-3 z-10 h-11 w-11 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/25 text-white">
                                <i className="fas fa-chevron-left"></i>
                            </motion.button>
                        )}
                        <AnimatePresence initial={false} custom={dir} mode="popLayout">
                            <motion.img
                                key={index}
                                src={images[index]}
                                alt={`Imagem ${index + 1}`}
                                custom={dir}
                                initial={{ x: dir * 240, opacity: 0, scale: 0.96 }}
                                animate={{ x: 0, opacity: 1, scale: 1 }}
                                exit={{ x: dir * -240, opacity: 0, scale: 0.96 }}
                                transition={{ type: 'spring', stiffness: 340, damping: 32 }}
                                drag={many ? 'x' : false}
                                dragConstraints={{ left: 0, right: 0 }}
                                dragElastic={0.6}
                                onDragEnd={(e, info) => {
                                    if (info.offset.x < -swipeConfidence) go(1);
                                    else if (info.offset.x > swipeConfidence) go(-1);
                                }}
                                className="max-h-full max-w-full object-contain rounded-lg shadow-2xl cursor-grab active:cursor-grabbing"
                                onError={(e) => { e.currentTarget.style.opacity = 0.25; }}
                            />
                        </AnimatePresence>
                        {many && (
                            <motion.button whileTap={{ scale: 0.8 }} onClick={() => go(1)} aria-label="Imagem seguinte"
                                    className="absolute right-3 z-10 h-11 w-11 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/25 text-white">
                                <i className="fas fa-chevron-right"></i>
                            </motion.button>
                        )}
                    </div>

                    {/* thumbnails */}
                    {many && (
                        <div className="p-3 overflow-x-auto" onClick={(e) => e.stopPropagation()}>
                            <div className="flex gap-2 justify-center min-w-max mx-auto">
                                {images.map((src, i) => (
                                    <motion.button
                                        key={i}
                                        whileTap={{ scale: 0.9 }}
                                        onClick={() => { setDir(i > index ? 1 : -1); setState(s => ({ ...s, index: i })); }}
                                        aria-label={`Ir para imagem ${i + 1}`}
                                        className={'relative h-14 w-20 flex-shrink-0 rounded-md overflow-hidden ring-2 transition ' + (i === index ? 'ring-white' : 'ring-transparent opacity-60 hover:opacity-100')}
                                    >
                                        <img src={src} alt="" loading="lazy" className="h-full w-full object-cover" />
                                        {i === index && (
                                            <motion.div layoutId="ks-thumb-active" className="absolute inset-0 ring-2 ring-white rounded-md" />
                                        )}
                                    </motion.button>
                                ))}
                            </div>
                        </div>
                    )}
                </motion.div>
            )}
        </AnimatePresence>
    );
}
