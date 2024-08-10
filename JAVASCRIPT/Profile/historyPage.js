document.addEventListener('DOMContentLoaded', function() {

    var matchList = document.getElementById("matchList");

    var prova = [
        {
            "numeroPartite": 5,
            "numeroPersone": 10,
            "numeroPersoneEntrate": 7,
            "tipoPartita": "pubblica",
            "host": "capo1",
            "id" : 1
        },
        {
            "numeroPartite": 3,
            "numeroPersone": 6,
            "numeroPersoneEntrate": 2,
            "tipoPartita": "amici",
            "host": "capo2",
            "id" : 2
        },
        {
            "numeroPartite": 8,
            "numeroPersone": 5,
            "numeroPersoneEntrate": 3,
            "tipoPartita": "pubblica",
            "host": "capo3",
            "id" : 3
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

            mainTag.innerHTML += `
                <div id="MainBlock" class="col-5 flex-column justify-content-center align-items-center mb-3 pb-4">
                    <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                        Partita di ${match.host}
                    </div>
                    <div class="container mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="p-3 border bg-light infoblock">
                                    <h5>Numero di match totali da giocare</h5>
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
                    <button type="button" class="btn btn-primary viewDetailsButton" data-match-id="${match.id}">Vedi Dettagli</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.querySelectorAll('.viewDetailsButton').forEach(function(button) {
                button.addEventListener('click', function() {
                    var matchId = this.getAttribute('data-match-id');
                    window.location.href = `detailedMatchPage.php?matchId=${matchId}`;
                });
            });
        });
    }



});



