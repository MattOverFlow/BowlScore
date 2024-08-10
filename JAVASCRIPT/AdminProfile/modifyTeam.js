document.addEventListener("DOMContentLoaded", function() {

    var team = {
        "nomeTeam": "Team1",
        "numeroPersone": 4,
        "team": [
            { "username": "capo1" },
            { "username": "capo2" },
            { "username": "capo3" },
            { "username": "capo4" }
        ]
    }

    var mainTag = document.querySelector("main");
    var deleteCount = 0; 

    if (mainTag) {
        let mainBlockHTML = `
            <div id="MainBlock" class="col-5 flex-column justify-content-center align-items-center mb-3 pb-4">
                <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                    ${team.nomeTeam}
                </div>
                <div class="container mt-3">
                    <div class="row mt-3 justify-content-center">
        `;

        team.team.forEach((member, index) => {
            mainBlockHTML += `
                <div class="col-md-6 text-center mb-3" id="member-${index}">
                    <div class="p-3 border bg-light infoblock">
                        <div class="username font-weight-bold mb-2">${member.username}</div>
                        <button type="button" class="btn btn-secondary mr-2" onclick="editMember(${index})">Modifica</button>
                        <button type="button" class="btn btn-danger" onclick="deleteMember(${index})">Elimina</button>
                    </div>
                </div>
            `;
        });

        mainBlockHTML += `
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <button type="button" class="btn btn-primary col-4" id="saveTeam">Salva</button>
                    </div>
                </div>
            </div>
        `;

        mainTag.innerHTML = mainBlockHTML;
    }

    window.editMember = function(index) {
        const memberDiv = document.getElementById(`member-${index}`);
        const username = team.team[index].username;
        memberDiv.innerHTML = `
            <div class="p-3 border bg-light infoblock">
                <input type="text" class="form-control mb-2" id="edit-username-${index}" value="${username}">
                <button type="button" class="btn btn-secondary mr-2" onclick="saveEdit(${index})">Salva</button>
                <button type="button" class="btn btn-danger" onclick="cancelEdit(${index})">Annulla</button>
            </div>
        `;
    }

    window.saveEdit = function(index) {
        const newUsername = document.getElementById(`edit-username-${index}`).value;
        team.team[index].username = newUsername;
        const memberDiv = document.getElementById(`member-${index}`);
        memberDiv.innerHTML = `
            <div class="p-3 border bg-light infoblock">
                <div class="username font-weight-bold mb-2">${newUsername}</div>
                <button type="button" class="btn btn-secondary mr-2" onclick="editMember(${index})">Modifica</button>
                <button type="button" class="btn btn-danger" onclick="deleteMember(${index})">Elimina</button>
            </div>
        `;
    }

    window.cancelEdit = function(index) {
        const memberDiv = document.getElementById(`member-${index}`);
        const username = team.team[index].username;
        memberDiv.innerHTML = `
            <div class="p-3 border bg-light infoblock">
                <div class="username font-weight-bold mb-2">${username}</div>
                <button type="button" class="btn btn-secondary mr-2" onclick="editMember(${index})">Modifica</button>
                <button type="button" class="btn btn-danger" onclick="deleteMember(${index})">Elimina</button>
            </div>
        `;
    }

    window.deleteMember = function(index) {
        if (deleteCount < 2) {
            team.team.splice(index, 1);
            document.getElementById(`member-${index}`).remove();
            deleteCount++;
    
            if (deleteCount >= 2) {
                // Disabilita tutti i pulsanti "Elimina"
                document.querySelectorAll('.btn-danger').forEach(button => {
                    button.disabled = true;
                });
            }
        }
    }
});