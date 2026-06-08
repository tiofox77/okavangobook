/**
 * Config usada APENAS para gerar as variantes de dark mode (.dark)
 * sem mexer no tailwind.min.css (v2) existente.
 */
module.exports = {
    darkMode: 'class',
    content: [
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './app/**/*.php',
    ],
    corePlugins: { preflight: false },
    theme: {
        extend: {
            colors: {
                primary: { DEFAULT: '#134e91', dark: '#0d3a6b' },
                secondary: '#f59e0b',
            },
        },
    },
};
