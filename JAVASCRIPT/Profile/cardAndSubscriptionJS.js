import { downloadDatiUtente } from '../Profile/UtenteUtils.js';

document.addEventListener('DOMContentLoaded',async function() {
    
    async function downloadListaPacchetti(){
        const response = await fetch('../../PHP/Carte/DownloadListaPacchetti.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            });
        if (!response.ok) {
            console.log(response.error);
            return null;
        }
        const data = await response.json();
        if (data == null) {
            console.log('lista dei pacchetti vuota');
            return null;
        } else {
            return data;
        }
    }

    async function downloadListaTipiAbbonamento(){
        const response = await fetch('../../PHP/Carte/DownloadTipiAbbonamento.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            });
        if (!response.ok) {
            console.log(response.error);
            return null;
        }
        const data = await response.json();
        if (data == null) {
            console.log('tipi di abbonamento vuoto');
            return null;
        } else {
            return data;
        }
    }

    async function downloadDatiCarta(){
        const response = await fetch('../../PHP/Carte/DownloadDatiCarta.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            });
        if (!response.ok) {
            console.log(response.error);
            return null;
        }
        const data = await response.json();
        if (data == null) {
            console.log('dati carta vuoti');
            return null;
        } else {
            return data;
        }
    }

    async function downloadDatiAbbonamento(){
        const response = await fetch('../../PHP/Carte/DownloadDatiAbbonamento.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            });
        if (!response.ok) {
            console.log(response.error);
            return null;
        }
        const data = await response.json();
        if (data == null) {
            console.log('Edati abbonamento vuoti');
            return null;
        } else {
            return data;
        }
    }


    var manageCardBlock=this.getElementById("manageCard");
    var manageSubscription=this.getElementById("manageSubscription");
    var manageMatchPack=this.getElementById("manageMatchPack");

    var listaPacchetti = await downloadListaPacchetti();
    var listaTipiAbbonamento = await downloadListaTipiAbbonamento(); 
    var datiCarta = await downloadDatiCarta();
    var datiUtente = await downloadDatiUtente();
    var datiAbbonamento = await downloadDatiAbbonamento();

    if (datiCarta != null) {

        manageCardBlock.innerHTML = `
        <div class="container">
            <div class="row mt-4">
                <div class="col-6 text-center">${datiUtente.nome} ${datiUtente.cognome}</div>
                <div class="col-6 text-center">Codice carta: ${datiCarta.identificativo}</div>
            </div>
            <div class="row mt-3">
                <div class="col-6 text-center">Data creazione: ${datiCarta.dataCreazione}</div>
                <div class="col-6 text-center">Data scadenza: ${datiCarta.dataScadenza}</div>
            </div>
            <div class="row mt-4 d-flex justify-content-center">
                <div class="col-9 text-center mx-auto">
                    <button class="btn btn-primary" type="submit" id="deleteCard">Elimina Carta</button> 
                </div>
            </div>
        </div>

    `;
    } else{
        manageCardBlock.innerHTML = `
        <p class="mt-3">Nessuna carta associata, richiedi una nuova</p>
        <button class="btn btn-primary" type="submit" id="createCard">Ottieni carta</button>
        `;
    }

    if (datiCarta != null) {
        let radioButtonsHTML = '';
        listaTipiAbbonamento.forEach((abbonamento) => {
            radioButtonsHTML += `
                <input type="radio" name="subscriptionType" value="${abbonamento.codice}" 
                    ${datiAbbonamento?.codiceTipo === abbonamento.codice ? 'checked' : ''}> 
                    ${abbonamento.durata} mesi - €${abbonamento.prezzo}<br>
            `;
        });

        manageSubscription.innerHTML = `
            <div class="container">
                <div class="row mt-2">
                    <div class="col text-center">
                        <label>Tipo di abbonamento:</label><br>
                        ${radioButtonsHTML}
                        ${datiAbbonamento?.dataAcquisto && datiAbbonamento?.dataScadenza ? `
                            <p class="mt-2">Data inizio: ${datiAbbonamento.dataAcquisto}</p>
                            <p>Data fine: ${datiAbbonamento.dataScadenza}</p>
                        ` : ''}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col text-center">
                        <button class="btn btn-primary" type="submit" id="updateSubscription">Aggiorna Abbonamento</button>
                    </div>
                </div>
            </div>
        `;
    } else {
        manageSubscription.innerHTML= `
            <p class="mt-3">Nessuna carta trovata, richiedere prima una carta per attivare un abbonamento</p>
        `;
    }


    if (datiCarta != null) {    

        let pacchettiOptions = '';
        listaPacchetti.forEach((pacchetto) => {
            pacchettiOptions += `
                <option value="${pacchetto.codice}">
                    ${pacchetto.numero_partite} Partite - €${pacchetto.prezzo}
                </option>
            `;
        });
    
        manageMatchPack.innerHTML = `
            <div class="container">
                <div class="row mt-4">
                    <div class="col text-center">
                        <p>Partite disponibili: ${datiCarta.partiteTotali}</p>
                        <label for="matchPack">Seleziona pacchetto di partite:</label>
                        <select id="matchPack" class="form-control mt-2">
                            ${pacchettiOptions}
                        </select>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col text-center">
                        <button class="btn btn-primary" type="button" id="updateMatches">Aggiungi Partita</button>
                    </div>
                </div>
            </div>
        `;
    } else {
        manageMatchPack = `
        <p class="mt-3">Nessuna carta trovata, richiedere la carta per comprare un pacchetto di partite</p>
    `;
    }

    var createCardButton = document.getElementById("createCard");
    var deleteCardButton = document.getElementById("deleteCard");
    var updateSubscriptionButton = document.getElementById("updateSubscription");
    var updateMatchesButton = document.getElementById("updateMatches");
    
    if(createCardButton != null){
        console.log("createCardButton");
        createCardButton.addEventListener('click',async function() {
            await fetch('../../PHP/Carte/CreateCard.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
            });
            location.reload();
        });
    }

    if(deleteCardButton != null){
        deleteCardButton.addEventListener('click',async function() {
            await fetch('../../PHP/Carte/DeleteCard.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
            });
            location.reload();
        });
    }

    if (updateSubscriptionButton != null) {
        updateSubscriptionButton.addEventListener('click', async function () {
            const selectedRadio = document.querySelector('input[name="subscriptionType"]:checked');
            
            if (selectedRadio != null) {
                await fetch('../../PHP/Carte/UpdateSubscription.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `codiceTipo=${selectedRadio.value}`,
                });
            }
            location.reload();
        });
    }

    if (updateMatchesButton != null) {
        updateMatchesButton.addEventListener('click', async function () {
            const matchPackSelect = document.getElementById('matchPack');
            if (matchPackSelect != null) {
                const selectedOptionValue = matchPackSelect.value;
                
                if (selectedOptionValue != null) {
                    await fetch('../../PHP/Carte/UpdateMatches.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `codicePacchetto=${selectedOptionValue}`,
                    });
                }
            }
            location.reload();
        });
    }




});