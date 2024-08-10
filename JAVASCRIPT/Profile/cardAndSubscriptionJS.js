document.addEventListener('DOMContentLoaded', function() {
    
    // TODO: aggiungi funzione per scaricare informazioni in modo asincrono dal DB
    var data = {
        "code": "123fsd123",
        "dateCreation": "12/03/2022",
        "dateExpiration" : "12/03/2029",
        "name": "Marco",
        "surname": "Carola",
        "subscriptionType": "none",
        "subscriptionStartDate" : "15/06/2023",
        "subscriptionEndDate" : "15/07/2023",
        "matchAvaibleCount" : 10
    };

    var manageCardBlock=this.getElementById("manageCard");
    var manageSubscription=this.getElementById("manageSubscription");
    var manageMatchPack=this.getElementById("manageMatchPack");

    // TODO: aggiungi funzione per cancellare carta
    if (data.code != null && data.code.length > 0) {
        manageCardBlock.innerHTML = `
            <div class="container">
                <div class="row mt-4">
                    <div class="col-6 text-center">${data.name} ${data.surname}</div>
                    <div class="col-6 text-center">Codice carta: ${data.code}</div>
                </div>
                <div class="row mt-3">
                    <div class="col-6 text-center">Data creazione: ${data.dateCreation}</div>
                    <div class="col-6 text-center">Data scadenza: ${data.dateExpiration}</div>
                </div>
                <div class="row mt-4">
                    <div class="col text-center">
                        <button class="btn btn-primary" type="submit">Elimina Carta</button> 
                    </div>
                </div>
            </div>
        `;
        // TODO: aggiungi funzione per aggiornare l'abbonamento
        manageSubscription.innerHTML=`
                        <div class="container">
                <div class="row mt-2">
                    <div class="col text-center">
                        <label>Tipo di abbonamento:</label><br>
                        <input type="radio" name="subscriptionType" value="mensile" ${data.subscriptionType === 'mensile' ? 'checked' : ''}> Mensile<br>
                        <input type="radio" name="subscriptionType" value="trimestrale" ${data.subscriptionType === 'trimestrale' ? 'checked' : ''}> Trimestrale<br>
                        <input type="radio" name="subscriptionType" value="annuale" ${data.subscriptionType === 'annuale' ? 'checked' : ''}> Annuale<br>
                        ${data.subscriptionStartDate && data.subscriptionEndDate ? `
                            <p class="mt-2">Data inizio: ${data.subscriptionStartDate}</p>
                            <p>Data fine: ${data.subscriptionEndDate}</p>
                        ` : ''}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col text-center">
                        <button class="btn btn-primary" type="submit">Aggiorna Abbonamento</button>
                    </div>
                </div>
            </div>
        `;

        // TODO: aggiungi funzione per aggiungere una partita o un pacchetto di partite
        if (data.subscriptionType === "none") {
            manageMatchPack.innerHTML = `
                <div class="container">
                    <div class="row mt-4">
                        <div class="col text-center">
                            <p>Partite disponibili: ${data.matchAvaibleCount}</p>
                            <label for="matchPack">Seleziona pacchetto di partite:</label>
                            <select id="matchPack" class="form-control mt-2">
                                <option value="3">3 Partite</option>
                                <option value="5">5 Partite</option>
                                <option value="10">10 Partite</option>
                                <option value="20">20 Partite</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col text-center">
                            <button class="btn btn-primary" type="button">Aggiungi Partita</button>
                            <button class="btn btn-primary" type="button">Aggiorna Pacchetto</button>
                        </div>
                    </div>
                </div>
            `;
        }

    } else {
        // TODO: aggiungi funzione per creare una carta
        manageCardBlock.innerHTML = `
            <p class="mt-3">Nessuna carta associata, richiedi una nuova</p>
            <button class="btn btn-primary" type="submit">Ottieni carta</button>
        `;
        manageSubscription.innerHTML= `
            <p class="mt-3">Nessuna carta trovata, richiedere prima una carta per attivare un abbonamento</p>
        `;
        manageMatchPack = `
            <p class="mt-3">Nessuna carta trovata, richiedere la carta per comprare un pacchetto di partite</p>
        `;
    }
});