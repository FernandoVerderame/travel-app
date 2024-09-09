// URL segnaposto per l'immagine, da utilizzare se nessuna immagine è presente
const placeholder = 'https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM=';

// Seleziona il campo file input per l'immagine
const imageField = document.getElementById('image');

// Seleziona l'elemento immagine per l'anteprima
const previewField = document.getElementById('preview');

// Seleziona il pulsante per cambiare l'immagine
const changeImageButton = document.getElementById('change-image-button');

// Seleziona l'elemento contenente l'immagine precedente
const previousImageField = document.getElementById('previous-image-field');

// Variabile per memorizzare temporaneamente l'URL del blob dell'immagine caricata
let blobUrl;

// Aggiungi un evento al campo file per quando l'utente seleziona una nuova immagine
imageField.addEventListener('change', () => {

    // Verifica se un file è stato selezionato
    if (imageField.files && imageField.files[0]) {
        // Ottieni il file selezionato
        const file = imageField.files[0];

        // Crea un URL temporaneo per il file selezionato
        blobUrl = URL.createObjectURL(file);

        // Imposta l'URL temporaneo come src dell'elemento di anteprima
        previewField.src = blobUrl;
    } else {
        // Se non c'è un file, mostra l'immagine segnaposto
        previewField.src = placeholder;
    }
});

// Aggiungi un evento che si attiva prima che la pagina venga ricaricata o chiusa
window.addEventListener('beforeunload', () => {
    // Revoca l'URL del blob per liberare memoria
    if (blobUrl) URL.revokeObjectURL(blobUrl);
});

// Aggiungi un evento al pulsante per cambiare immagine
changeImageButton.addEventListener('click', () => {
    // Nasconde il campo dell'immagine precedente
    previousImageField.classList.add('d-none');

    // Mostra il campo per selezionare una nuova immagine
    imageField.classList.remove('d-none');

    // Imposta l'immagine di anteprima sul segnaposto
    previewField.src = placeholder;

    // Simula un clic sul campo input per aprire il file picker
    imageField.click();
});
