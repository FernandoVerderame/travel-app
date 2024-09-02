import * as bootstrap from 'bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

    tooltipTriggerList.forEach(tooltipTriggerEl => {
        // Imposta il contenuto del tooltip
        const notes = tooltipTriggerEl.getAttribute('data-bs-title');

        if (notes && notes.trim() !== '') {
            // Inizializza il tooltip con il contenuto dinamico
            new bootstrap.Tooltip(tooltipTriggerEl, {
                title: notes,
                placement: tooltipTriggerEl.getAttribute('data-bs-placement') || 'top',
                customClass: tooltipTriggerEl.getAttribute('data-bs-custom-class') || ''
            });
        } else {
            new bootstrap.Tooltip(tooltipTriggerEl);
        }
    });
});

