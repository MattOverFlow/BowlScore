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
                                    <h5>${tournament.tipo === "Singolo" ? "Numero di partecipanti" : "Numero di persone nel team"}</h5>
                                    <p id="teamSize">${tournament.tipo === "Singolo" ? tournament.numPartecipanti : tournament.numElementiTeam}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border bg-light infoblock">
                                    <h5>${tournament.tipo === "Singolo" ? "Username dei partecipanti" : "Nomi dei team"}</h5>
                                    <p id="teamMembers">${tournament.tipo === "Singolo" ? tournament.partecipanti.map(member => `<div>${member}</div>`).join('') : tournament.teams.map(team => `<div>${team}</div>`).join('')}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 justify-content-center">
                            <div class="col-md-6 text-center">
                                ${tournament.isCompleted 
                                    ? (tournament.tipo === 'Singolo' 
                                        ? `<button class="viewDetailsButton btn btn-primary" data-url="viewDetailTournamentSingle.php">Visualizza dettagli</button>`
                                        : `<button class="viewDetailsButton btn btn-primary" data-url="viewDetailTournamentTeam.php">Visualizza dettagli</button>`
                                      )
                                    : (tournament.tipo === 'Singolo' 
                                        ? `<button class="startTournamentButton btn btn-secondary" data-url="simulateTournamentSingle.php">Inizia torneo</button>`
                                        : `<button class="startTournamentButton btn btn-secondary" data-url="simulateTournamentTeam.php">Inizia torneo</button>`
                                      )
                                }
                            </div>
                        </div>
                    </div>
                </div>
            `;
            mainTag.innerHTML += mainBlockHTML;
        });

        // Add event listeners for view details buttons
        document.querySelectorAll('.viewDetailsButton').forEach(function(button) {
            button.addEventListener('click', function() {
                var url = this.getAttribute('data-url');
                window.location.href = url;
            });
        });

        // Add event listeners for start tournament buttons
        document.querySelectorAll('.startTournamentButton').forEach(function(button) {
            button.addEventListener('click', function() {
                var url = this.getAttribute('data-url');
                window.location.href = url;
            });
        });
    }

    // Render all tournaments initially
    renderTournaments(tournaments);
});