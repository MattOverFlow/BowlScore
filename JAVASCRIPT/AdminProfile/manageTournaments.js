document.addEventListener('DOMContentLoaded', function() {
    var tournaments = [
        {
            nome: "francescoCup",
            data: "01/01/2021",
            tipo: "Teams",
            numElementiTeam: 3,
            teams: ["team1", "team2", "team3", "team4"],
            isCompleted: true
        },
        {
            nome: "CiccioCup",
            data: "01/01/2021",
            tipo: "Singolo",
            numPartecipanti: 9,
            partecipanti: ["piero", "pirlo", "franceso", "beppe", "zampa", "mattia", "nico", "miguel", "giovanni"],
            isCompleted: false
        }
    ];

    var mainTag = document.querySelector("main");

    function renderTournaments(filteredTournaments) {
        mainTag.innerHTML = "";
        filteredTournaments.forEach((tournament, index) => {
            let mainBlockHTML = `
                <div id="MainBlock" class="col-5 flex-column justify-content-center align-items-center mb-3 pb-4">
                    <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                        ${tournament.nome}
                    </div>
                    <div class="container mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="p-3 border bg-light infoblock">
                                    <h5>Tipo di torneo</h5>
                                    <p id="tournamentType">${tournament.tipo}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border bg-light infoblock">
                                    <h5>${tournament.tipo === "Singolo" ? "Numero di partecipanti" : "Numero di persone nel team"}</h5>
                                    <p id="teamSize">${tournament.tipo === "Singolo" ? tournament.numPartecipanti : tournament.numElementiTeam}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="p-3 border bg-light infoblock">
                                    <h5>${tournament.tipo === "Singolo" ? "Username dei partecipanti" : "Nomi dei team"}</h5>
                                    <p id="teamMembers">${tournament.tipo === "Singolo" ? tournament.partecipanti.map(member => `<div>${member}</div>`).join('') : tournament.teams.map(team => `<div>${team}</div>`).join('')}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 justify-content-center">
                            <div class="col-md-6 text-center">
                                ${tournament.isCompleted 
                                    ? `<button onclick="${tournament.tipo === 'Singolo' ? '1' : 'simulateTournamentTeam.php'}" class="btn btn-primary">Visualizza dettagli</button>`
                                    : `<button onclick="${tournament.tipo === 'Singolo' ? '3' : 'simulateTournamentTeam.php'}" class="btn btn-secondary">Inizia torneo</button>`
                                }
                            </div>
                        </div>
                    </div>
                </div>
            `;
            mainTag.innerHTML += mainBlockHTML;
        });
    }

    // Render all tournaments initially
    renderTournaments(tournaments);

    // Add event listener for search input
    document.getElementById('searchInput').addEventListener('input', function(event) {
        const searchTerm = event.target.value.toLowerCase();
        const filteredTournaments = tournaments.filter(tournament => tournament.nome.toLowerCase().includes(searchTerm));
        renderTournaments(filteredTournaments);
    });
});