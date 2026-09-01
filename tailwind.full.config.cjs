/**
 * Config do build COMPLETO do Tailwind v3 (substitui o tailwind.min.css v2).
 * O tailwind.config.cjs continua a existir apenas para o build:dark legado.
 *
 * Gerar: npm run build:css
 */
module.exports = {
    darkMode: 'class',
    content: [
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './app/Livewire/**/*.php',
        './app/Http/**/*.php',
        './app/View/**/*.php',
    ],
    // Classes construídas por interpolação no Blade (bg-{{ $color }}-100…):
    // o scanner não as vê, por isso ficam aqui garantidas.
    safelist: [
        {
            pattern: /^(bg|text|border)-(amber|blue|cyan|emerald|gray|green|indigo|orange|pink|purple|red|rose|teal|yellow)-(50|100|200|300|400|500|600|700|800|900)$/,
            variants: ['dark', 'hover', 'dark:hover'],
        },
        {
            pattern: /^bg-(amber|blue|cyan|emerald|gray|green|indigo|purple|red|rose|teal)-900\/30$/,
            variants: ['dark'],
        },
    ],
    theme: {
        extend: {
            colors: {
                primary: { DEFAULT: '#134e91', dark: '#0d3a6b' },
                secondary: '#f59e0b',
            },
        },
    },
};
