import ApexCharts from 'apexcharts';
import '@stisla/vanilla';
import Alpine from 'alpinejs';

window.ApexCharts = ApexCharts;
window.Alpine = Alpine;

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
				}
				return;
			}

			if (json.redirect) {
				window.location.href = json.redirect;
				return;
			}

			this.message = json.message ?? 'Saved.';

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
