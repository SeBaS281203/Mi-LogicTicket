/**
 * LogicTicket - Sistema UX: Toasts, Loader, Confirmaciones
 * Integración con Alpine.js
 */

document.addEventListener('alpine:init', () => {
  // === Toast Store ===
  Alpine.store('toast', {
    items: [],

    add(message, type = 'success', duration = 4000) {
      const id = Date.now().toString(36) + Math.random().toString(36).slice(2);
      this.items.push({ id, message, type });
      if (duration > 0) {
        setTimeout(() => this.remove(id), duration);
      }
      return id;
    },

    remove(id) {
      this.items = this.items.filter((t) => t.id !== id);
    },

    success(message, duration = 4000) {
      return this.add(message, 'success', duration);
    },
    error(message, duration = 5000) {
      return this.add(message, 'error', duration);
    },
    info(message, duration = 4000) {
      return this.add(message, 'info', duration);
    },
    warning(message, duration = 4500) {
      return this.add(message, 'warning', duration);
    },
  });

  // === Loader Store (full page) ===
  Alpine.store('loader', {
    visible: false,
    show() {
      this.visible = true;
    },
    hide() {
      this.visible = false;
    },
    async run(fn) {
      this.show();
      try {
        return await fn();
      } finally {
        this.hide();
      }
    },
  });

  // === Confirm Modal Store ===
  Alpine.store('confirm', {
    open: false,
    title: '',
    message: '',
    confirmText: 'Confirmar',
    cancelText: 'Cancelar',
    variant: 'danger', // danger | warning | info
    resolve: null,

    show({ title = '¿Estás seguro?', message = '', confirmText = 'Confirmar', cancelText = 'Cancelar', variant = 'danger' }) {
      this.title = title;
      this.message = message;
      this.confirmText = confirmText;
      this.cancelText = cancelText;
      this.variant = variant;
      this.open = true;
      return new Promise((resolve) => {
        this.resolve = resolve;
      });
    },

    accept() {
      this.open = false;
      if (this.resolve) this.resolve(true);
      this.resolve = null;
    },

    cancel() {
      this.open = false;
      if (this.resolve) this.resolve(false);
      this.resolve = null;
    },
  });

  // Directive: x-loading - deshabilita botón y muestra spinner
  Alpine.directive('loading', (el, { expression }, { evaluateLater, effect }) => {
    const getLoading = evaluateLater(expression);
    let span, spinner;
    effect(() => {
      getLoading((loading) => {
        const isLoading = !!loading;
        const btn = el.tagName === 'BUTTON' || el.tagName === 'A' ? el : el.querySelector('button, [role="button"], a.btn');
        const target = btn || el;
        if (isLoading) {
          target.disabled = true;
          target.setAttribute('aria-busy', 'true');
          if (!target.querySelector('[data-loading-spinner]')) {
            const content = target.innerHTML;
            target.setAttribute('data-loading-original', content);
            spinner = document.createElement('span');
            spinner.setAttribute('data-loading-spinner', '');
            spinner.className = 'inline-flex items-center';
            spinner.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
            target.innerHTML = '';
            target.appendChild(spinner);
            span = document.createElement('span');
            span.textContent = 'Procesando...';
            target.appendChild(span);
          }
        } else {
          target.disabled = false;
          target.removeAttribute('aria-busy');
          const original = target.getAttribute('data-loading-original');
          if (original) {
            target.innerHTML = original;
            target.removeAttribute('data-loading-original');
          }
        }
      });
    });
  });
});

// Helper global para toasts (también usable fuera de Alpine)
window.showToast = function (message, type = 'success', duration = 4000) {
  if (typeof Alpine !== 'undefined' && Alpine.store && Alpine.store('toast')) {
    return Alpine.store('toast').add(message, type, duration);
  }
  console.warn('showToast: Alpine not ready', { message, type });
};
