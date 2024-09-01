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

        const tripTitle = form.dataset.trip;

        // Insert contents
        confirmationButton.innerText = 'Conferma Eliminazione';
        confirmationButton.className = 'btn btn-danger';
        modalTitle.innerText = 'Elimina viaggio';
        modalBody.innerHTML = `Sei sicuro di voler cancellare <strong>${tripTitle}</strong>?`;
    })
})

confirmationButton.addEventListener('click', () => {
    if (activeForm) activeForm.submit();
});

modal.addEventListener('hidden.bs.modal', () => {
    activeForm = null;
})