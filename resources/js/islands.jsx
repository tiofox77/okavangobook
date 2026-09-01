import React from 'react';
import { createRoot } from 'react-dom/client';
import DateRangePicker from './islands/DateRangePicker.jsx';

/**
 * Entrada das ilhas React.
 * Cada nó [data-island="…"] (com wire:ignore para o Livewire não lhe tocar)
 * recebe o componente respetivo. Um MutationObserver remonta ilhas que o
 * Livewire recrie (ex.: troca de tab na ficha do hotel).
 */

const ISLANDS = {
    'date-range': (el) => {
        const startInput = document.getElementById(el.dataset.startInput);
        const endInput = document.getElementById(el.dataset.endInput);
        if (!startInput || !endInput) return false;

        // Esconde os campos nativos (ficam no DOM para o wire:model/fallback)
        el.closest('[data-island-zone]')?.classList.add('island-mounted');
        el.classList.remove('hidden');

        createRoot(el).render(
            <DateRangePicker
                startInput={startInput}
                endInput={endInput}
                minDate={el.dataset.min || null}
            />
        );
        return true;
    },
};

function mountAll(scope = document) {
    scope.querySelectorAll('[data-island]').forEach((el) => {
        if (el.__islandMounted) return;
        const mount = ISLANDS[el.dataset.island];
        if (mount && mount(el) !== false) {
            el.__islandMounted = true;
        }
    });
}

const boot = () => {
    mountAll();
    new MutationObserver(() => mountAll()).observe(document.body, { childList: true, subtree: true });
};

document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', boot) : boot();
