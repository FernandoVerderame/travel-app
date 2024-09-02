const deleteForms = document.querySelectorAll('.delete-form');
const modal = document.getElementById('modal');
const modalTitle = document.querySelector('.modal-title');
const modalBody = document.querySelector('.modal-body');
const confirmationButton = document.getElementById('modal-confirmation-button');

let activeForm = null;

deleteForms.forEach(form => {
    form.addEventListener('submit', e => {
        e.preventDefault();

        activeForm = form;

        const type = form.dataset.type;
        const tripTitle = form.dataset.trip;
        const stopTitle = form.dataset.stop;

        if (type === 'trip') {
            modalTitle.innerText = 'Elimina viaggio';
            modalBody.innerHTML = `Sei sicuro di voler cancellare il viaggio <strong>${tripTitle}</strong>?`;
        } else if (type === 'stop') {
            modalTitle.innerText = 'Elimina tappa';
            modalBody.innerHTML = `Sei sicuro di voler cancellare la tappa <strong>${stopTitle}</strong>?`;
        }

        // Configura il pulsante di conferma
        confirmationButton.innerText = 'Conferma Eliminazione';
        confirmationButton.className = 'btn btn-danger';
    })
})

confirmationButton.addEventListener('click', () => {
    if (activeForm) activeForm.submit();
});

modal.addEventListener('hidden.bs.modal', () => {
    activeForm = null;
})