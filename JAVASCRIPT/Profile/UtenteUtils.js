export async function downloadDatiUtente() {
    const response = await fetch('../../PHP/Utente/DownloadDatiUtente.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
    });
    if (!response.ok) {
        console.error('Errore nella risposta:', response.statusText);
        return null;
    }
    const data = await response.json();
    if (data == null) {
        console.error('Errore nel download dei dati utente');
        return null;
    } else {
        return data;
    }
}