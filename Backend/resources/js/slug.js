// Seleziona il campo input per il titolo
const titleField = document.getElementById('title');

// Seleziona il campo input per lo slug
const slugField = document.getElementById('slug');

// Aggiungi un evento 'blur' al campo titolo (si attiva quando l'utente termina di modificare il campo)
titleField.addEventListener('blur', () => {
    // Quando il campo titolo perde il focus, genera automaticamente lo slug:
    // 1. Rimuove gli spazi bianchi all'inizio e alla fine della stringa (trim)
    // 2. Trasforma tutti i caratteri in minuscolo (toLowerCase)
    // 3. Sostituisce gli spazi con i trattini ('-')
    slugField.value = titleField.value.trim().toLowerCase().split(' ').join('-');
});
