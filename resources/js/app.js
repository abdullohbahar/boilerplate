import ApexCharts from 'apexcharts';
import '@stisla/vanilla';
import Alpine from 'alpinejs';

window.ApexCharts = ApexCharts;
window.Alpine = Alpine;
Alpine.start();

import './meridian/app-shell.js';
import './meridian/theme.js';
import './meridian/table-select.js';
import './meridian/charts.js';
