// Importa tutte le funzionalità di Bootstrap
import * as bootstrap from 'bootstrap';

// Aggiungi un event listener che attende il caricamento completo del DOM
document.addEventListener('DOMContentLoaded', function () {
    // Seleziona tutti gli elementi che hanno l'attributo data-bs-toggle="tooltip"
    const tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

    // Itera su ogni elemento trovato nella pagina
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        // Ottieni il valore del contenuto del tooltip dall'attributo data-bs-title
        const notes = tooltipTriggerEl.getAttribute('data-bs-title');

        // Se esiste un contenuto per il tooltip (non vuoto o composto solo da spazi)
        if (notes && notes.trim() !== '') {
            // Inizializza un nuovo tooltip di Bootstrap con contenuto dinamico
            new bootstrap.Tooltip(tooltipTriggerEl, {
                title: notes, // Assegna il titolo del tooltip
                placement: tooltipTriggerEl.getAttribute('data-bs-placement') || 'top', // Posizione del tooltip (predefinita 'top' se non specificata)
                customClass: tooltipTriggerEl.getAttribute('data-bs-custom-class') || '' // Classe CSS personalizzata (vuota se non specificata)
            });
        } else {
            // Se non c'è un titolo specificato, crea un tooltip con le impostazioni predefinite
            new bootstrap.Tooltip(tooltipTriggerEl);
        }
    });
});
