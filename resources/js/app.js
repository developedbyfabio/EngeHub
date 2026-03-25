import './bootstrap';
import Alpine from 'alpinejs';
import './toast';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('servers-main-board')) {
        import('./servers-tiles-sortable.js').then((m) => {
            m.initServersPageSortable();
            m.bindServersFullscreenUi();
        });
    }
});