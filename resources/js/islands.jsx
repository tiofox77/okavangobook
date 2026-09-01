import React from 'react';
import { createRoot } from 'react-dom/client';
import DateRangePicker from './islands/DateRangePicker.jsx';
import PriceRangeSlider from './islands/PriceRangeSlider.jsx';

/**
 * Entrada das ilhas React.
 * Cada nó [data-island="…"] (com wire:ignore para o Livewire não lhe tocar)
 * recebe o componente respetivo. Um MutationObserver remonta ilhas que o
 * Livewire recrie (ex.: troca de tab na ficha do hotel).
 */

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const ISLANDS = {
    'date-range': (el) => {
        const startInput = document.getElementById(el.dataset.startInput);
        const endInput = document.getElementById(el.dataset.endInput);
        if (!startInput || !endInput) return false;

        el.closest('[data-island-zone]')?.classList.add('island-mounted');
        el.classList.remove('hidden');

        createRoot(el).render(
            <DateRangePicker startInput={startInput} endInput={endInput} minDate={el.dataset.min || null} />
        );
        return true;
    },

    'price-range': (el) => {
        const minInput = document.getElementById(el.dataset.minInput);
        const maxInput = document.getElementById(el.dataset.maxInput);
        if (!minInput || !maxInput) return false;

        el.closest('[data-island-zone]')?.classList.add('island-mounted');
        el.classList.remove('hidden');

        createRoot(el).render(
            <PriceRangeSlider
                minInput={minInput}
                maxInput={maxInput}
                min={Number(el.dataset.min ?? 0)}
                max={Number(el.dataset.max ?? 1000000)}
                step={Number(el.dataset.step ?? 5000)}
                wireEl={el.closest('[wire\\:id]')}
            />
        );
        return true;
    },

    // Contador animado (vanilla, sem React): conta de 0 até data-value ao entrar no ecrã
    'count-up': (el) => {
        const target = Number(el.dataset.value ?? el.textContent.replace(/\D/g, ''));
        if (!Number.isFinite(target) || target <= 0) return false;
        const suffix = el.dataset.suffix ?? '';
        const nf = new Intl.NumberFormat('pt-PT');
        const setVal = (v) => { el.textContent = nf.format(Math.round(v)) + suffix; };

        if (reducedMotion) { setVal(target); return true; }

        const io = new IntersectionObserver((entries) => {
            if (!entries[0].isIntersecting) return;
            io.disconnect();
            const dur = 1400;
            const t0 = performance.now();
            const tick = (t) => {
                const p = Math.min(1, (t - t0) / dur);
                setVal(target * (1 - Math.pow(1 - p, 3))); // ease-out cúbico
                if (p < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        }, { threshold: 0.4 });
        io.observe(el);
        return true;
    },
};

function mountAll(scope = document) {
    scope.querySelectorAll('[data-island]').forEach((el) => {
        if (el.__islandMounted) {
            // Morphs do Livewire repõem a class da zona — reafirma (com guarda
            // para não gerar novas mutações quando já está correta).
            const zone = el.closest('[data-island-zone]');
            if (zone && !zone.classList.contains('island-mounted')) zone.classList.add('island-mounted');
            if (el.classList.contains('hidden')) el.classList.remove('hidden');
            return;
        }
        const mount = ISLANDS[el.dataset.island];
        if (mount && mount(el) !== false) {
            el.__islandMounted = true;
        }
    });
}

const boot = () => {
    mountAll();
    // O callback pode correr a meio de um morph do Livewire (nós ainda
    // detached → closest() falha); repete depois de o DOM assentar.
    let settle = null;
    new MutationObserver(() => {
        mountAll();
        clearTimeout(settle);
        settle = setTimeout(mountAll, 80);
    }).observe(document.body, {
        childList: true,
        subtree: true,
        // O morph do Livewire termina com um reset do atributo class da zona
        // (sem childList) — sem isto, a classe island-mounted perdia-se.
        attributes: true,
        attributeFilter: ['class'],
    });
};

document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', boot) : boot();
