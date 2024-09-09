// Seleziona tutti gli elementi del DOM con la classe 'delete-form' (i moduli per la cancellazione)
const deleteForms = document.querySelectorAll('.delete-form');

// Seleziona l'elemento modale
const modal = document.getElementById('modal');

// Seleziona il titolo del modale
const modalTitle = document.querySelector('.modal-title');

// Seleziona il corpo del modale
const modalBody = document.querySelector('.modal-body');

// Seleziona il pulsante di conferma nel modale
const confirmationButton = document.getElementById('modal-confirmation-button');

// Variabile per tenere traccia del modulo attivo (quello che si sta tentando di inviare)
let activeForm = null;

// Cicla su ogni modulo per aggiungere un listener all'evento 'submit'
deleteForms.forEach(form => {
    form.addEventListener('submit', e => {
        // Prevenire il comportamento predefinito di invio del modulo
        e.preventDefault();

        // Assegna il modulo attualmente attivo alla variabile 'activeForm'
        activeForm = form;

        // Ottieni i dati personalizzati dal modulo (tipo, titolo del viaggio, titolo della tappa)
        const type = form.dataset.type;
        const tripTitle = form.dataset.trip;
        const stopTitle = form.dataset.stop;

        // Modifica il contenuto del modale in base al tipo di modulo (viaggio o tappa)
        if (type === 'trip') {
            modalTitle.innerText = 'Elimina viaggio';
            modalBody.innerHTML = `Sei sicuro di voler cancellare il viaggio <strong>${tripTitle}</strong>?`;
        } else if (type === 'stop') {
            modalTitle.innerText = 'Elimina tappa';
            modalBody.innerHTML = `Sei sicuro di voler cancellare la tappa <strong>${stopTitle}</strong>?`;
        }

        // Configura il pulsante di conferma (testo e stile del pulsante)
        confirmationButton.innerText = 'Conferma Eliminazione';
        confirmationButton.className = 'btn btn-danger';
    });
});

// Aggiungi un listener per gestire il clic sul pulsante di conferma del modale
confirmationButton.addEventListener('click', () => {
    // Se c'è un modulo attivo, invialo
    if (activeForm) activeForm.submit();
});

// Aggiungi un listener per gestire l'evento di chiusura del modale
modal.addEventListener('hidden.bs.modal', () => {
    // Resetta il modulo attivo quando il modale viene chiuso
    activeForm = null;
});
