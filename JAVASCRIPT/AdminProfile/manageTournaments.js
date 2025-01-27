document.addEventListener('DOMContentLoaded', function () {
    var mainTag = document.querySelector("main");

    function renderSingleTournaments(singleTournaments) {
        const tournaments = Object.values(singleTournaments);

        tournaments.forEach((tournament) => {
            let singleTournamentHTML = `
            <div id="MainBlock" class="col-5 flex-column justify-content-center align-items-center mb-3 pb-4">
                <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                    ${tournament.torneo.NomeTorneo}
                </div>
                <div class="container mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="p-3 border bg-light infoblock">
                                <h5>Numero di partecipanti</h5>
                                <p id="teamSize">${tournament.torneo.NumeroPartecipanti} partecipanti</p>
                                <h5 class="mt-3">Data di svolgimento</h5>
                                <p id="tournamentDate">${new Date(tournament.torneo.DataCreazione).toLocaleDateString('it-IT', { day: '2-digit', month: '2-digit', year: 'numeric' })}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border bg-light infoblock">
                                <h5>Username dei partecipanti</h5>
                                <p id="teamMembers">${tournament.partecipanti.map(member => `<div>${member}</div>`).join('')}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-md-6 text-center">
                            <button class="viewDetailsButton btn btn-primary" data-url="viewDetailTournamentSingle.php?idTorneo=${tournament.torneo.IdTorneo}">Visualizza dettagli</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
            mainTag.innerHTML += singleTournamentHTML;
        });
    }

    function renderTeamTournaments(teamTournaments) {
        const tournaments = Object.values(teamTournaments);

        tournaments.forEach((tournament) => {
            let teamTournamentHTML = `
            <div id="MainBlock" class="col-5 flex-column justify-content-center align-items-center mb-3 pb-4">
                <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                    ${tournament.torneo.NomeTorneo}
                </div>
                <div class="container mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="p-3 border bg-light infoblock">
                                <h5>Numero di team partecipanti</h5>
                                <p id="teamSize">${tournament.torneo.NumeroTeamPartecipanti} partecipanti</p>
                                <h5 class="mt-3">Data di svolgimento</h5>
                                <p id="tournamentDate">${new Date(tournament.torneo.DataCreazione).toLocaleDateString('it-IT', { day: '2-digit', month: '2-digit', year: 'numeric' })}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border bg-light infoblock">
                                <h5>Nomi dei team partecipanti</h5>
                                <p id="teamMembers">${tournament.squadre.map(team => `<div>${team}</div>`).join('')}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-md-6 text-center">
                            <button class="viewDetailsButton btn btn-primary" data-url="viewDetailTournamentTeam.php?idTorneo=${tournament.torneo.IdTorneo}">Visualizza dettagli</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
            mainTag.innerHTML += teamTournamentHTML;
        });
    }

    // Aggiunta event delegation per i pulsanti
    mainTag.addEventListener('click', function (event) {
        if (event.target.classList.contains('viewDetailsButton')) {
            const url = event.target.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            }
        }
    });

    const searchButton = document.getElementById("searchButton");

    searchButton.addEventListener("click", async function () {
        const searchInput = document.getElementById('searchInput').value.trim();
        const toggleFiltersCheckbox = document.getElementById('toggleFilters');

        let filters = { searchInput };

        if (toggleFiltersCheckbox.checked) {
            const tournamentType = document.getElementById('tournamentType').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            filters = {
                ...filters,
                tournamentType,
                startDate,
                endDate,
            };

            if (tournamentType === 'squadre') {
                filters.numTeams = document.getElementById('numTeams').value;
                filters.teamSize = document.getElementById('teamSize').value;
                filters.teamName = document.getElementById('teamName').value.trim();
            } else if (tournamentType === 'singolo') {
                filters.username = document.getElementById('usernameField').value.trim();
            }

        } else {
            filters = { searchInput };
        }

        const query = new URLSearchParams({
            searchInput: filters.searchInput && filters.searchInput !== "" ? filters.searchInput : null,
            tournamentType: filters.tournamentType && filters.tournamentType !== "" ? filters.tournamentType : null,
            startDate: filters.startDate && filters.startDate !== "" ? filters.startDate : null,
            endDate: filters.endDate && filters.endDate !== "" ? filters.endDate : null,
            numTeams: filters.numTeams && filters.numTeams !== "" ? filters.numTeams : null,
            teamSize: filters.teamSize && filters.teamSize !== "" ? filters.teamSize : null,
            teamName: filters.teamName && filters.teamName !== "" ? filters.teamName : null,
            username: filters.username && filters.username !== "" ? filters.username : null
        }).toString();

        const response = await fetch('../../PHP/Torneo/RicercaStoricoTornei.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: query,
        });

        const data = await response.json();

        mainTag.innerHTML = "";

        renderSingleTournaments(data.torneiSingoli);
        renderTeamTournaments(data.torneiSquadre);
    });
    
    // Reset dei filtri e sblocco dei campi al ritorno alla pagina principale
    window.addEventListener('popstate', function () {
        const toggleFiltersCheckbox = document.getElementById('toggleFilters');
        const numTeamsSelect = document.getElementById('numTeams');
        const teamSizeSelect = document.getElementById('teamSize');

        // Deseleziona la checkbox dei filtri
        toggleFiltersCheckbox.checked = false;

        // Nasconde i filtri
        numTeamsSelect.disabled = true;
        teamSizeSelect.disabled = true;

        // Rimuove eventuali valori dai campi
        document.getElementById('searchInput').value = '';
        document.getElementById('numTeams').value = '';
        document.getElementById('teamSize').value = '';
        document.getElementById('teamName').value = '';
        document.getElementById('usernameField').value = '';
    });
});
