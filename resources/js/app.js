const menuButton = document.querySelector('[data-menu-button]');
const mobileMenu = document.querySelector('[data-mobile-menu]');

if (menuButton && mobileMenu) {
    menuButton.addEventListener('click', () => {
        const expanded = menuButton.getAttribute('aria-expanded') === 'true';
        menuButton.setAttribute('aria-expanded', String(!expanded));
        mobileMenu.classList.toggle('hidden', expanded);
    });

    mobileMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            menuButton.setAttribute('aria-expanded', 'false');
            mobileMenu.classList.add('hidden');
        });
    });
}

const creditInput = document.querySelector('[data-credit-select-input]');
const creditLevelsSource = document.querySelector('[data-credit-levels]');
const termField = document.querySelector('[data-term-field]');
const termSelect = document.querySelector('[data-term-select]');
const loanSummary = document.querySelector('[data-loan-summary]');
const summaryRange = document.querySelector('[data-summary-range]');
const summaryRate = document.querySelector('[data-summary-rate]');
const summaryTerms = document.querySelector('[data-summary-terms]');
let creditLevels = {};

if (creditLevelsSource) {
    try {
        creditLevels = JSON.parse(creditLevelsSource.textContent || '{}');
    } catch {
        creditLevels = {};
    }
}

const syncCreditFields = (clearTerm = false) => {
    if (!creditInput || !termField || !termSelect || !loanSummary) return;

    const level = creditLevels[creditInput.value];

    if (!level) {
        termSelect.innerHTML = '<option value="">Seleccionar plazo</option>';
        termSelect.disabled = true;
        loanSummary.classList.add('invisible');
        loanSummary.setAttribute('aria-hidden', 'true');
        return;
    }

    const previousTerm = clearTerm ? '' : termSelect.value;
    termSelect.innerHTML = '<option value="">Seleccionar plazo</option>';

    level.available_terms.forEach((availableTerm) => {
        const option = document.createElement('option');
        option.value = String(availableTerm);
        option.textContent = `${availableTerm} meses`;
        option.selected = String(availableTerm) === previousTerm;
        termSelect.append(option);
    });

    termSelect.disabled = false;
    loanSummary.classList.remove('invisible');
    loanSummary.setAttribute('aria-hidden', 'false');
    summaryRange.textContent = level.range_label;
    summaryRate.textContent = `${Number(level.annual_rate).toLocaleString('es-BO', { maximumFractionDigits: 2 })}%`;
    summaryTerms.textContent = level.terms_label;
};

creditInput?.addEventListener('change', () => syncCreditFields(true));

document.querySelectorAll('[data-credit-select]').forEach((link) => {
    link.addEventListener('click', () => {
        if (creditInput) {
            creditInput.value = link.dataset.creditSelect || '';
            syncCreditFields(true);
        }
    });
});

syncCreditFields(false);

document.querySelectorAll('[data-image-fallback]').forEach((image) => {
    image.addEventListener('error', () => {
        const fallback = image.dataset.imageFallback;

        if (fallback && image.src !== fallback) {
            image.src = fallback;
            return;
        }

        image.hidden = true;
    });
});

const resultModal = document.querySelector('[data-result-modal]');

if (resultModal) {
    const resultPanel = resultModal.querySelector('[data-result-panel]');
    const closeButtons = resultModal.querySelectorAll('[data-modal-close]');
    const returnFocus = document.querySelector('[data-modal-return-focus]');
    const previousBodyOverflow = document.body.style.overflow;
    const previousBodyPaddingRight = document.body.style.paddingRight;
    let modalOpen = false;

    const focusableElements = () => Array.from(resultModal.querySelectorAll(
        'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])',
    ));

    const openModal = () => {
        if (modalOpen) return;

        modalOpen = true;
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.overflow = 'hidden';

        if (scrollbarWidth > 0) {
            document.body.style.paddingRight = `${scrollbarWidth}px`;
        }

        resultModal.classList.remove('pointer-events-none', 'invisible', 'opacity-0');
        resultModal.classList.add('opacity-100');
        resultPanel.classList.remove('scale-95', 'opacity-0');
        resultPanel.classList.add('scale-100', 'opacity-100');
        resultModal.setAttribute('aria-hidden', 'false');
        resultModal.querySelector('[data-modal-close]')?.focus({ preventScroll: true });
    };

    const closeModal = () => {
        if (!modalOpen) return;

        modalOpen = false;
        resultModal.classList.remove('opacity-100');
        resultModal.classList.add('opacity-0', 'pointer-events-none');
        resultPanel.classList.remove('scale-100', 'opacity-100');
        resultPanel.classList.add('scale-95', 'opacity-0');
        resultModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = previousBodyOverflow;
        document.body.style.paddingRight = previousBodyPaddingRight;

        window.setTimeout(() => {
            if (!modalOpen) resultModal.classList.add('invisible');
        }, 200);

        returnFocus?.focus({ preventScroll: true });
    };

    closeButtons.forEach((button) => button.addEventListener('click', closeModal));

    resultModal.addEventListener('click', (event) => {
        if (event.target === resultModal) closeModal();
    });

    document.addEventListener('keydown', (event) => {
        if (!modalOpen) return;

        if (event.key === 'Escape') {
            event.preventDefault();
            closeModal();
            return;
        }

        if (event.key === 'Tab') {
            const focusable = focusableElements();
            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (!first || !last) return;

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus({ preventScroll: true });
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus({ preventScroll: true });
            }
        }
    });

    if (resultModal.dataset.autoOpen === 'true') openModal();
}
