import React, { useMemo, useState } from 'react';
import { motion } from 'framer-motion';

/**
 * Ilha React: galeria do destino em mosaico (bento).
 * Fotos e vídeos no MESMO mosaico — os vídeos com miniatura do YouTube e
 * selo de reprodução — e tudo abre no Lightbox, que reproduz o vídeo
 * embebido. Substitui a antiga divisão "grelha de fotos + lista de vídeos".
 */

const VISIVEIS = 7; // 1 destaque + 6 no mosaico; o resto vai para o "+N"

/** Padrão bento: o 1.º ocupa 2x2, o 4.º e o 7.º ficam largos. */
function classesTile(i, total) {
    if (i === 0) return 'col-span-2 row-span-2';
    if (total > 4 && (i === 3 || i === 6)) return 'col-span-2';
    return '';
}

function miniatura(item) {
    if (item.type === 'video') {
        return item.youtube ? `https://i.ytimg.com/vi/${item.youtube}/hqdefault.jpg` : null;
    }
    return item.src;
}

export default function LocationGallery({ itens, titulo }) {
    const [erros, setErros] = useState({});

    const { visiveis, extra } = useMemo(() => ({
        visiveis: itens.slice(0, VISIVEIS),
        extra: Math.max(0, itens.length - VISIVEIS),
    }), [itens]);

    const abrir = (indice) => {
        if (window.KiandaLightbox) {
            window.KiandaLightbox.open(itens, indice);
        }
    };

    const fotos = itens.filter(i => i.type === 'image').length;
    const videos = itens.length - fotos;

    return (
        <div>
            <div className="flex flex-wrap items-end justify-between gap-3 mb-5">
                <div>
                    <h2 className="text-3xl font-bold text-gray-800 dark:text-white">
                        <i className="fas fa-images text-primary mr-2" aria-hidden="true"></i>
                        {titulo}
                    </h2>
                    <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {fotos > 0 && <>{fotos} {fotos === 1 ? 'foto' : 'fotos'}</>}
                        {fotos > 0 && videos > 0 && ' · '}
                        {videos > 0 && <>{videos} {videos === 1 ? 'vídeo' : 'vídeos'}</>}
                        <span className="text-gray-400"> — clique para ver em ecrã inteiro</span>
                    </p>
                </div>
                {itens.length > 1 && (
                    <motion.button
                        whileHover={{ scale: 1.03 }}
                        whileTap={{ scale: 0.97 }}
                        onClick={() => abrir(0)}
                        className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary text-white text-sm font-medium shadow-sm hover:shadow-md transition-shadow"
                    >
                        <i className="fas fa-expand text-xs" aria-hidden="true"></i>
                        Ver tudo
                    </motion.button>
                )}
            </div>

            <div className="grid grid-cols-2 md:grid-cols-4 auto-rows-[9rem] md:auto-rows-[11rem] gap-3">
                {visiveis.map((item, i) => {
                    const thumb = miniatura(item);
                    const ultimo = i === VISIVEIS - 1 && extra > 0;

                    return (
                        <motion.button
                            key={i}
                            type="button"
                            onClick={() => abrir(i)}
                            initial={{ opacity: 0, scale: 0.94 }}
                            whileInView={{ opacity: 1, scale: 1 }}
                            viewport={{ once: true, margin: '-40px' }}
                            transition={{ duration: 0.4, delay: Math.min(i * 0.06, 0.35) }}
                            whileHover={{ y: -4 }}
                            aria-label={item.title || (item.type === 'video' ? 'Ver vídeo' : 'Ver foto')}
                            className={'group relative overflow-hidden rounded-2xl bg-gray-200 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 ' + classesTile(i, visiveis.length)}
                        >
                            {thumb && !erros[i] ? (
                                <img
                                    src={thumb}
                                    alt={item.title || ''}
                                    loading={i === 0 ? 'eager' : 'lazy'}
                                    onError={() => setErros(e => ({ ...e, [i]: true }))}
                                    className="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                />
                            ) : (
                                <span className="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-700 to-gray-900 text-white/70">
                                    <i className={'fas ' + (item.type === 'video' ? 'fa-film' : 'fa-image') + ' text-3xl'} aria-hidden="true"></i>
                                </span>
                            )}

                            {/* véu para leitura da legenda */}
                            <span className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent opacity-80 group-hover:opacity-95 transition-opacity" />

                            {/* selo de vídeo */}
                            {item.type === 'video' && !ultimo && (
                                <span className="absolute inset-0 flex items-center justify-center">
                                    <motion.span
                                        whileHover={{ scale: 1.12 }}
                                        className="h-14 w-14 rounded-full bg-white/95 text-primary flex items-center justify-center shadow-xl"
                                    >
                                        <i className="fas fa-play text-lg ml-0.5" aria-hidden="true"></i>
                                    </motion.span>
                                </span>
                            )}

                            {/* legenda */}
                            {item.title && !ultimo && (
                                <span className="absolute bottom-0 inset-x-0 p-3 text-left">
                                    <span className="block text-white text-sm font-medium drop-shadow line-clamp-2 translate-y-1 group-hover:translate-y-0 transition-transform">
                                        {item.title}
                                    </span>
                                </span>
                            )}

                            {/* "+N" no último tile quando há mais media */}
                            {ultimo && (
                                <span className="absolute inset-0 flex flex-col items-center justify-center bg-black/60 backdrop-blur-[2px] text-white">
                                    <span className="text-2xl font-bold">+{extra}</span>
                                    <span className="text-xs opacity-80">ver tudo</span>
                                </span>
                            )}

                            {/* lupa no hover (só fotos) */}
                            {item.type === 'image' && !ultimo && (
                                <span className="absolute top-3 right-3 h-8 w-8 rounded-full bg-white/90 text-gray-700 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i className="fas fa-expand text-xs" aria-hidden="true"></i>
                                </span>
                            )}
                        </motion.button>
                    );
                })}
            </div>
        </div>
    );
}
