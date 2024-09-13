document.addEventListener('DOMContentLoaded', function () {
    // Seleziona l'elemento della modale
    const descriptionModal = document.getElementById('descriptionModal');

    // Aggiungi un ascoltatore di eventi per l'apertura della modale
    descriptionModal.addEventListener('show.bs.modal', function (event) {
        // Ottieni il bottone che ha attivato l'apertura della modale
        const button = event.relatedTarget;

        // Estrai il titolo e la descrizione dai dati del bottone
        const title = button.getAttribute('data-title');
        const description = button.getAttribute('data-description');

        // Seleziona l'elemento del titolo e del corpo della modale
        const modalTitle = descriptionModal.querySelector('.modal-title');
        const modalBody = descriptionModal.querySelector('.modal-body');

        // Imposta il titolo e la descrizione nella modale
        modalTitle.textContent = title;
        modalBody.textContent = description;
    });
});
