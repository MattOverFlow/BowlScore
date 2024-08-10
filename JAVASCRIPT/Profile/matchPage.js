document.addEventListener("DOMContentLoaded", function() {

    var prova = [
        {
            "numeroPartite": 5,
            "numeroPersone": 10,
            "numeroPersoneEntrate": 7,
            "tipoPartita": "pubblica",
            "host": "capo1",
            "isPartecipating": false
        },
        {
            "numeroPartite": 3,
            "numeroPersone": 6,
            "numeroPersoneEntrate": 2,
            "tipoPartita": "amici",
            "host": "capo2",
            "isPartecipating": false
        },
        {
            "numeroPartite": 8,
            "numeroPersone": 5,
            "numeroPersoneEntrate": 3,
            "tipoPartita": "pubblica",
            "host": "capo3",
            "isPartecipating": false
        }
    ];

    var user = {
        "username": "capo3",
        "amici": [
            { "username": "capo2" }
        ]
    };

    var mainTag = document.querySelector("main");

    if (mainTag) {
        prova.forEach(function(match) {
            var isFriend = user.amici.some(amico => amico.username === match.host);
            var isHost = user.username === match.host;

            if (match.tipoPartita === "pubblica" || isFriend) {
                var buttonsHTML = '';

                if (isHost) {
                    buttonsHTML = `
                        <button type="button" class="btn btn-secondary" id="startGameButton">Inizia</button>
                    `;
                } else if (match.isPartecipating) {
                    buttonsHTML = `
                        <p>In attesa dell'inizio della partita</p>
                    `;
                } else {
                    buttonsHTML = `
                        <button type="button" class="btn btn-primary mr-2" id="joinGameButton">Aggiungiti</button>
                    `;
                }

                mainTag.innerHTML += `
                    <div id="MainBlock" class="col-5 flex-column justify-content-center align-items-center mb-3 pb-4">
                        <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                            Partita di ${match.host}
                        </div>
                        <div class="container mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="p-3 border bg-light infoblock">
                                        <h5>Numero di match totali giocati</h5>
                                        <p id="totalGames">${match.numeroPartite}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border bg-light infoblock">
                                        <h5>Numero di persone entrate</h5>
                                        <p id="peopleEntered">${match.numeroPersoneEntrate}/${match.numeroPersone}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="p-3 border bg-light infoblock">
                                        <h5>Pubblica o solo per amici</h5>
                                        <p id="gameType">${match.tipoPartita}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex justify-content-center align-items-center">
                                    ${buttonsHTML}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        });
    }
});