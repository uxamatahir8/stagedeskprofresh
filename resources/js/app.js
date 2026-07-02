import './bootstrap';

const closeAllDropdowns = (except = null) => {
    document.querySelectorAll('[data-tw-dropdown-menu]').forEach((menu) => {
        if (menu !== except) {
            menu.classList.add('hidden');
            menu.closest('[data-tw-dropdown]')?.querySelector('[data-tw-dropdown-toggle]')?.setAttribute('aria-expanded', 'false');
        }
    });
};

const setDrawerOpen = (open) => {
    const drawer = document.querySelector('[data-tw-mobile-drawer]');
    const overlay = document.querySelector('[data-tw-mobile-overlay]');

    if (!drawer || !overlay) return;

    drawer.classList.toggle('-translate-x-full', !open);
    overlay.classList.toggle('hidden', !open);
    document.documentElement.classList.toggle('overflow-hidden', open);
};

const setModalOpen = (modal, open) => {
    if (!modal) return;

    modal.classList.toggle('hidden', !open);
    modal.classList.toggle('flex', open);
    modal.setAttribute('aria-hidden', String(!open));
    document.documentElement.classList.toggle('overflow-hidden', open);
};

const refreshLucide = () => {
    const lib = window.lucide || window.Lucide;
    if (lib && typeof lib.createIcons === 'function') {
        lib.createIcons({
            attrs: { 'stroke-width': 2 },
        });
    }
};

document.addEventListener('click', (event) => {
    const drawerOpen = event.target.closest('[data-tw-drawer-open]');
    const drawerClose = event.target.closest('[data-tw-drawer-close]');
    const dropdownToggle = event.target.closest('[data-tw-dropdown-toggle]');
    const passwordToggle = event.target.closest('[data-tw-password-toggle]');
    const modalOpen = event.target.closest('[data-tw-modal-open]');
    const modalClose = event.target.closest('[data-tw-modal-close]');

    if (drawerOpen) {
        event.preventDefault();
        setDrawerOpen(true);
        return;
    }

    if (drawerClose) {
        event.preventDefault();
        setDrawerOpen(false);
        return;
    }

    if (passwordToggle) {
        event.preventDefault();
        const input = document.getElementById(passwordToggle.dataset.twPasswordToggle);
        if (!input) return;

        const showing = input.type === 'text';
        input.type = showing ? 'password' : 'text';
        passwordToggle.setAttribute('aria-pressed', String(!showing));
        passwordToggle.querySelector('[data-password-label]')?.replaceChildren(document.createTextNode(showing ? 'Show' : 'Hide'));
        refreshLucide();
        return;
    }

    if (modalOpen) {
        event.preventDefault();
        setModalOpen(document.getElementById(modalOpen.dataset.twModalOpen), true);
        refreshLucide();
        return;
    }

    if (modalClose) {
        event.preventDefault();
        setModalOpen(modalClose.closest('[data-tw-modal]'), false);
        return;
    }

    if (dropdownToggle) {
        event.preventDefault();
        const wrapper = dropdownToggle.closest('[data-tw-dropdown]');
        const menu = wrapper?.querySelector('[data-tw-dropdown-menu]');
        if (!menu) return;

        const willOpen = menu.classList.contains('hidden');
        closeAllDropdowns(menu);
        menu.classList.toggle('hidden', !willOpen);
        dropdownToggle.setAttribute('aria-expanded', String(willOpen));
        refreshLucide();
        return;
    }

    if (!event.target.closest('[data-tw-dropdown]')) {
        closeAllDropdowns();
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;

    closeAllDropdowns();
    setDrawerOpen(false);
    document.querySelectorAll('[data-tw-modal]').forEach((modal) => setModalOpen(modal, false));
});

document.addEventListener('DOMContentLoaded', refreshLucide);
window.addEventListener('load', refreshLucide);
