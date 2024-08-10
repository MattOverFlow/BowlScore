document.addEventListener("DOMContentLoaded", function() {

    var prova = [
        {
            "nomeTeam": "Ciao",
            "numeroPersone": 4,
            "team": [
                { "username": "capo1" },
                { "username": "capo2" },
                { "username": "capo3" },
                { "username": "capo4" }
            ]
        },
        {
            "nomeTeam": "Team2",
            "numeroPersone": 2,
            "team": [
                { "username": "capo1" },
                { "username": "capo2" },
            ]
        },
        {
            "nomeTeam": "LucasGym",
            "numeroPersone": 3,
            "team": [
                { "username": "capo1" },
                { "username": "capo2" },
                { "username": "capo3" },
            ]
        }
    ];

    var mainTag = document.querySelector("main");

    function renderTeams(teams) {
        let mainBlockHTML = '';

        teams.forEach((team, teamIndex) => {
            mainBlockHTML += `
                <div id="MainBlock" class="col-5 flex-column justify-content-center align-items-center mb-3 pb-4">
                    <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                        ${team.nomeTeam}
                    </div>
                    <div class="container mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="p-3 border bg-light infoblock">
                                    <h5>Numero di persone nel team</h5>
                                    <p id="teamSize">${team.numeroPersone}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border bg-light infoblock">
                                    <h5>Username dei partecipanti</h5>
                                    <p id="teamMembers">${team.team.map(member => `<div>${member.username}</div>`).join('')}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 justify-content-center">
                            <div class="col-md-6 text-center">
                                <button type="button" class="btn btn-secondary" onclick="editTeam(${teamIndex})">Modifica</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        mainTag.innerHTML = mainBlockHTML;
    }

    if (mainTag) {
        renderTeams(prova);
    }

    window.editTeam = function(teamIndex) {
        window.location.href = 'modifyTeam.php';
    }

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const filteredTeams = prova.filter(team => team.nomeTeam.toLowerCase().includes(searchTerm));
            renderTeams(filteredTeams);
        });
    }
});