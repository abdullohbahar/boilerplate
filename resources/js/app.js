import ApexCharts from 'apexcharts';
import '@stisla/vanilla';
import Alpine from 'alpinejs';

window.ApexCharts = ApexCharts;
window.Alpine = Alpine;

// ─── Toast ───────────────────────────────────────────────────────────────────

Alpine.data('toast', (initialToasts = []) => ({
	toasts: [],

	init() {
		initialToasts.forEach(t => this.add(t.message, t.type));
		window.toast = (message, type = 'success') => this.add(message, type);
	},

	add(message, type = 'success') {
		const id = Date.now() + Math.random();
		this.toasts.push({ id, message, type, visible: true });
		setTimeout(() => this.dismiss(id), 4000);
	},

	dismiss(id) {
		const t = this.toasts.find(t => t.id === id);
		if (t) { t.visible = false; }
		setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 200);
	},
}));

// ─── Delete confirmation ──────────────────────────────────────────────────────

window.confirmDelete = (url, label = 'this item') => {
	if (!window.confirm(`Delete "${label}"? This cannot be undone.`)) { return; }

	const form = document.createElement('form');
	form.method = 'POST';
	form.action = url;
	form.innerHTML =
		`<input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">` +
		`<input type="hidden" name="_method" value="DELETE">`;
	document.body.appendChild(form);
	form.submit();
};

// ─── AJAX form validation ─────────────────────────────────────────────────────

Alpine.data('formAjax', () => ({
	errors: {},
	loading: false,
	message: '',
	serverError: '',

	async submit() {
		const form = this.$el;
		this.loading = true;
		this.errors = {};
		this.message = '';
		this.serverError = '';

		try {
			const response = await fetch(form.action, {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
					'Accept': 'application/json',
					'X-Requested-With': 'XMLHttpRequest',
				},
				body: new FormData(form),
			});

			// Session expired → auth redirected us to login
			if (response.redirected) {
				window.location.href = response.url;
				return;
			}

			// Non-JSON response (e.g. 419 CSRF error)
			if (!response.headers.get('content-type')?.includes('application/json')) {
				console.error('[formAjax] HTTP', response.status, response.url);
				this.serverError = `HTTP ${response.status} – please refresh the page and try again.`;
				return;
			}

			const json = await response.json();

			if (!response.ok) {
				if (json.errors) {
					this.errors = json.errors;
				} else if (json.message) {
					if (window.toast) { window.toast(json.message, 'error'); }
					else { this.serverError = json.message; }
				}
				return;
			}

			if (json.redirect) {
				window.location.href = json.redirect;
				return;
			}

			const msg = json.message ?? 'Saved.';
			if (window.toast) { window.toast(msg, 'success'); }
			else { this.message = msg; }

		} catch (e) {
			console.error('[formAjax]', e);
		} finally {
			this.loading = false;
		}
	},
}));

Alpine.start();

import './meridian/app-shell.js';
import './meridian/theme.js';
import './meridian/table-select.js';
import './meridian/charts.js';
