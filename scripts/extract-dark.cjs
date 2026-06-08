/**
 * Lê o CSS gerado pelo Tailwind e mantém APENAS as regras cujo seletor
 * contém ".dark" (variantes de dark mode). Assim o ficheiro final é
 * puramente aditivo e não afeta o modo claro existente.
 */
const fs = require('fs');
const postcss = require('postcss');

const src = fs.readFileSync(process.argv[2], 'utf8');
const root = postcss.parse(src);

function selectorHasDark(rule) {
    return rule.selector && rule.selector.split(',').some((s) => /(^|\s|>|~|\+)\.dark(\s|\.|:|\\)/.test(s));
}

root.walkRules((rule) => {
    // Regras dentro de @media/@supports também são tratadas aqui
    if (!selectorHasDark(rule)) {
        rule.remove();
    }
});

// Remover at-rules (ex: @media) que ficaram vazias
root.walkAtRules((at) => {
    if (at.nodes && at.nodes.length === 0) {
        at.remove();
    }
});

const out = root.toString();
fs.writeFileSync(process.argv[3], out);
console.log('Regras .dark extraidas para', process.argv[3], '-', out.length, 'bytes');
