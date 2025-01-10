async function downloadTeams() {

    const response = await fetch('../../PHP/Team/DownloadTeams.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    });


    if (!response.ok) {
        console.log(response.error);
        return null;
    } else {
        return await response.json();
    }

}

async function cercaTeams(searchTerm) {

    const response = await fetch('../../PHP/Team/cercaTeams.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `query=${searchTerm}`,
    });

    if (!response.ok) {
        console.log(response.error);
        return null;
    } else {
        return await response.json();
    }

}



document.addEventListener("DOMContentLoaded", async function () {

    var mainTag = document.querySelector("main");

    function renderTeams(teams) {

        let mainBlockHTML = '';

        teams.forEach((team) => {
            mainBlockHTML += `
                <div id="MainBlock" class="col-5 flex-column justify-content-center align-items-center mb-3 pb-4">
                    <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                        ${team.Nome}
                    </div>
                    <div class="container mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="p-3 border bg-light infoblock">
                                    <h5>Numero di persone nel team</h5>
                                    <p id="teamSize">${team.NumeroComponenti}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border bg-light infoblock">
                                    <h5>Username dei partecipanti</h5>
                                    <p id="teamMembers">${team.Componenti.map(member => `<div>${member}</div>`).join('')}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 justify-content-center">
                            <div class="col-md-6 text-center">
                                <button type="button" class="btn btn-secondary" onclick="window.location.href='../../HTML/AdminProfile/modifyTeam.php?nomeTeam=' + encodeURIComponent('${team.Nome}')">Modifica</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        mainTag.innerHTML = mainBlockHTML;
    }

    if (mainTag) {
        var teams = await downloadTeams();
        renderTeams(teams);
    }

    window.editTeam = function (nomeTeam) {
        window.location.href = '../../HTML/AdminProfile/modifyTeam.php?nomeTeam=' + encodeURIComponent(nomeTeam)
    }

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', async function () {
            const searchTerm = this.value.toLowerCase();
            
            const filteredTeams = await cercaTeams(searchTerm);
            
            mainTag.innerHTML = "";
        
            renderTeams(filteredTeams);
        });
    }
});