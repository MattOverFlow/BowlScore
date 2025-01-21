document.addEventListener('DOMContentLoaded', function () {

    var mainTag = document.querySelector("main");

    function renderMatch(matchList) {

        matchList.forEach(function (match) {

            mainTag.innerHTML += `
                <div id="MainBlock" class="col-5 d-flex flex-column justify-content-center align-items-center mb-3 pb-4">
                    <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                        Partita: ${match.nome}
                    </div>
                    <div class="container d-flex flex-column justify-content-center align-items-center mt-3">
                        <div class="row w-100 d-flex justify-content-center">
                            <div class="col-12 d-flex justify-content-center">
                                <div class="p-3 border bg-light infoblock text-center">
                                    <h5>Numero di partecipanti</h5>
                                    <p id="totalGames">${match.numeroGiocatori}</p>
                                    <h5 class="mt-3">Data di svolgimento</h5>
                                    <p id="tournamentDate">${new Date(match.data).toLocaleDateString('it-IT', { day: '2-digit', month: '2-digit', year: 'numeric' })}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 w-100 d-flex justify-content-center">
                            <div class="col-12 d-flex justify-content-center">
                                <button type="button" class="btn btn-primary viewDetailsButton" data-match-id="${match.id}">Vedi Dettagli</button>
                            </div>
                        </div>
                    </div>
                </div>
        `;
            document.querySelectorAll('.viewDetailsButton').forEach(function (button) {
                button.addEventListener('click', function () {
                    var matchId = this.getAttribute('data-match-id');
                    window.location.href = `detailedMatchPage.php?matchId=${matchId}`;
                });
            });
        });
    }

    const searchButton = document.getElementById("searchButton");

    searchButton.addEventListener("click", async function () {

        const searchInput = document.getElementById('searchInput').value.trim();

        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;


        const query = new URLSearchParams({
            searchInput: searchInput && searchInput !== "" ? searchInput : null,
            startDate: startDate && startDate !== "" ? startDate : null,
            endDate: endDate && endDate !== "" ? endDate : null,
            username: "###"
        }).toString();


        const response = await fetch('../../PHP/Torneo/RicercaStoricoPartite.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: query,
        });

        const data = await response.json();

        mainTag.innerHTML = "";

        renderMatch(data);

    });

});

