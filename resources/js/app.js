import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart; // 2. Lo hacemos accesible en cualquier <script>

Alpine.start();
