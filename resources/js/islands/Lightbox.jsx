import React, { useCallback, useEffect, useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';

/**
 * Ilha React: Lightbox de media (fotos E vídeos).
 * Aceita duas formas de item, para retrocompatibilidade:
 *   - string  -> imagem (usado pelas galerias das propriedades)
 *   - objeto  -> { type: 'image'|'video', src, title, youtube, vimeo }
 * Exposto como window.KiandaLightbox.open(itens, indice).
 */

const swipeConfidence = 90;

/** Normaliza qualquer entrada para o formato interno. */
function normalizar(item) {
    if (typeof item === 'string') {
        return { type: 'image', src: item, title: null };
    }
    return {
        type: item.type === 'video' ? 'video' : 'image',
        src: item.src || item.url,
        title: item.title || null,
        youtube: item.youtube || null,
        vimeo: item.vimeo || null,
    };
}

function Video({ item }) {
    const comum = 'absolute inset-0 w-full h-full';

    if (item.youtube) {
        return (
            <div className="relative w-full max-w-5xl" style={{ paddingTop: '56.25%' }}>
                <iframe
                    className={comum + ' rounded-lg'}
                    src={`https://www.youtube-nocookie.com/embed/${item.youtube}?autoplay=1&rel=0`}
                    title={item.title || 'Vídeo'}
                    allow="accelerometer; autoplay; encrypted-media; picture-in-picture"
                    allowFullScreen
                />
            </div>
        );
    }
    if (item.vimeo) {
        return (
            <div className="relative w-full max-w-5xl" style={{ paddingTop: '56.25%' }}>
                <iframe
                    className={comum + ' rounded-lg'}
                    src={`https://player.vimeo.com/video/${item.vimeo}?autoplay=1`}
                    title={item.title || 'Vídeo'}
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowFullScreen
                />
            </div>
        );
    }
    return (
        <video
            controls
            autoPlay
            className="max-h-full max-w-full rounded-lg shadow-2xl bg-black"
            src={item.src}
        >
            O seu navegador não suporta vídeo.
        </video>
    );
}

export default function Lightbox() {
    const [state, setState] = useState({ open: false, itens: [], index: 0 });
    const [dir, setDir] = useState(0);

    useEffect(() => {
        window.KiandaLightbox = {
            open(itens, index = 0) {
                const lista = (Array.isArray(itens) ? itens : []).filter(Boolean).map(normalizar);
                if (!lista.length) return;
                setDir(0);
                setState({ open: true, itens: lista, index: Math.min(Math.max(0, index), lista.length - 1) });
            },
        };
        return () => { delete window.KiandaLightbox; };
    }, []);

    const close = useCallback(() => setState(s => ({ ...s, open: false })), []);
    const go = useCallback((delta) => {
        setDir(delta);
        setState(s => ({ ...s, index: (s.index + delta + s.itens.length) % s.itens.length }));
    }, []);

    // scroll-lock + teclado enquanto aberto
    useEffect(() => {
        if (!state.open) return;
        const anterior = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        const tecla = (e) => {
            if (e.key === 'Escape') close();
            else if (e.key === 'ArrowRight') go(1);
            else if (e.key === 'ArrowLeft') go(-1);
        };
        window.addEventListener('keydown', tecla);
        return () => {
            document.body.style.overflow = anterior;
            window.removeEventListener('keydown', tecla);
        };
    }, [state.open, close, go]);

    // pré-carrega as imagens vizinhas
    useEffect(() => {
        if (!state.open) return;
        [1, -1].forEach(d => {
            const item = state.itens[(state.index + d + state.itens.length) % state.itens.length];
            if (item?.type === 'image' && item.src) { const im = new Image(); im.src = item.src; }
        });
    }, [state.open, state.index, state.itens]);

    const { open, itens, index } = state;
    const varios = itens.length > 1;
    const atual = itens[index];

    // Sem AnimatePresence no invólucro de propósito: com a saída animada, o
    // nó ficava preso no DOM (o estado fechava — o scroll era restaurado —
    // mas o lightbox continuava visível). Desmontar já torna o fecho
    // infalível; a entrada continua animada.
    if (!open || !atual) {
        return null;
    }

    return (
        <>
            {(
                <motion.div
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ duration: 0.22 }}
                    className="fixed inset-0 z-[90] bg-black/95 flex flex-col select-none"
                    onClick={close}
                    role="dialog"
                    aria-modal="true"
                    aria-label="Galeria"
                >
                    {/* topo: contador + título + fechar */}
                    <div className="flex items-center justify-between gap-3 p-4 text-white" onClick={(e) => e.stopPropagation()}>
                        <span className="text-sm bg-white/10 px-3 py-1 rounded-full flex-shrink-0">{index + 1} / {itens.length}</span>
                        {atual.title && <span className="text-sm text-white/80 truncate hidden sm:block">{atual.title}</span>}
                        <motion.button whileTap={{ scale: 0.85 }} onClick={close} aria-label="Fechar"
                                className="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20">
                            <i className="fas fa-times text-lg"></i>
                        </motion.button>
                    </div>

                    {/* palco */}
                    <div className="relative flex-1 flex items-center justify-center overflow-hidden px-2" onClick={(e) => e.stopPropagation()}>
                        {varios && (
                            <motion.button whileTap={{ scale: 0.8 }} onClick={() => go(-1)} aria-label="Anterior"
                                    className="absolute left-3 z-10 h-11 w-11 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/25 text-white">
                                <i className="fas fa-chevron-left"></i>
                            </motion.button>
                        )}

                        <AnimatePresence initial={false} custom={dir} mode="popLayout">
                            <motion.div
                                key={index}
                                custom={dir}
                                initial={{ x: dir * 240, opacity: 0, scale: 0.96 }}
                                animate={{ x: 0, opacity: 1, scale: 1 }}
                                exit={{ x: dir * -240, opacity: 0, scale: 0.96 }}
                                transition={{ type: 'spring', stiffness: 340, damping: 32 }}
                                drag={varios && atual.type === 'image' ? 'x' : false}
                                dragConstraints={{ left: 0, right: 0 }}
                                dragElastic={0.6}
                                onDragEnd={(e, info) => {
                                    if (info.offset.x < -swipeConfidence) go(1);
                                    else if (info.offset.x > swipeConfidence) go(-1);
                                }}
                                className="max-h-full max-w-full flex items-center justify-center w-full"
                            >
                                {atual.type === 'video' ? (
                                    <Video item={atual} />
                                ) : (
                                    <img
                                        src={atual.src}
                                        alt={atual.title || `Imagem ${index + 1}`}
                                        className="max-h-[78vh] max-w-full object-contain rounded-lg shadow-2xl cursor-grab active:cursor-grabbing"
                                        onError={(e) => { e.currentTarget.style.opacity = 0.25; }}
                                        draggable={false}
                                    />
                                )}
                            </motion.div>
                        </AnimatePresence>

                        {varios && (
                            <motion.button whileTap={{ scale: 0.8 }} onClick={() => go(1)} aria-label="Seguinte"
                                    className="absolute right-3 z-10 h-11 w-11 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/25 text-white">
                                <i className="fas fa-chevron-right"></i>
                            </motion.button>
                        )}
                    </div>

                    {/* miniaturas */}
                    {varios && (
                        <div className="p-3 overflow-x-auto" onClick={(e) => e.stopPropagation()}>
                            <div className="flex gap-2 justify-center min-w-max mx-auto">
                                {itens.map((item, i) => (
                                    <motion.button
                                        key={i}
                                        whileTap={{ scale: 0.9 }}
                                        onClick={() => { setDir(i > index ? 1 : -1); setState(s => ({ ...s, index: i })); }}
                                        aria-label={`Ir para item ${i + 1}`}
                                        className={'relative h-14 w-20 flex-shrink-0 rounded-md overflow-hidden ring-2 transition bg-gray-800 ' + (i === index ? 'ring-white' : 'ring-transparent opacity-60 hover:opacity-100')}
                                    >
                                        {item.type === 'video' ? (
                                            item.youtube ? (
                                                <img src={`https://i.ytimg.com/vi/${item.youtube}/mqdefault.jpg`} alt="" loading="lazy" className="h-full w-full object-cover" />
                                            ) : (
                                                <span className="h-full w-full flex items-center justify-center text-white/80"><i className="fas fa-play"></i></span>
                                            )
                                        ) : (
                                            <img src={item.src} alt="" loading="lazy" className="h-full w-full object-cover" />
                                        )}
                                        {item.type === 'video' && (
                                            <span className="absolute inset-0 flex items-center justify-center bg-black/35">
                                                <i className="fas fa-play text-white text-xs"></i>
                                            </span>
                                        )}
                                    </motion.button>
                                ))}
                            </div>
                        </div>
                    )}
                </motion.div>
            )}
        </>
    );
}
