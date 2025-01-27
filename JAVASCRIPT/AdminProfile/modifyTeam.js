async function downloadTeam(teamName) {

    const response = await fetch('../../PHP/Team/InfoTeamUsername.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `teamName=${teamName}`,
    });

    if (!response.ok) {
        console.log(response.error);
        return null;
    } else {
        return await response.json();
    }
}

async function verifyTeamMember($userid) {
    const response = await fetch('../../PHP/Team/VerificaMembro.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `userid=${$userid}`,
    });

    if (response.ok) {
        return await response.json();
    }
}

async function checkUsername(username) {

    const response = await fetch('../../PHP/utente/DownloadDatiDaUsername.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `username=${username}`,
    });

    if (response.ok) {
        return await response.json();
    }


}


document.addEventListener("DOMContentLoaded", async function() {

    // Mostra un messaggio di errore
    function showError(message) {
        const errorDiv = document.getElementById('error-message');
        errorDiv.innerHTML = message;
        errorDiv.style.display = 'block';

        // Nascondi l'errore automaticamente dopo 5 secondi (opzionale)
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 3000);
    }

    // Mostra un popup
    function showPopup(message) {
        const popupDiv = document.getElementById('popup-message');
        popupDiv.innerHTML = message;
        popupDiv.style.display = 'block';

        // Nascondi il popup automaticamente dopo 5 secondi (opzionale)
        setTimeout(() => {
            popupDiv.style.display = 'none';
        }, 3000);
    }

    const urlParams = new URLSearchParams(window.location.search);
    const nomeTeam = urlParams.get('nomeTeam');

    var team = await downloadTeam(nomeTeam);
    var copyTeam = JSON.parse(JSON.stringify(team));
    var mainTag = document.querySelector("main");
    var counter = team.utenti.length;

    if (mainTag) {
        let mainBlockHTML = `
        <div id="MainBlock" class="col-5 flex-column justify-content-center align-items-center mb-3 pb-4">
            <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                ${team.nome}
            </div>
            <div class="container mt-3">
                
                <!-- Prima row: membri del team -->
                <div class="row mt-3 justify-content-center">
    `;

        // Aggiungi i membri nella prima row
        team.utenti.forEach((member, index) => {
            mainBlockHTML += `
                <div class="col-md-6 text-center mb-3" id="member-${index}">
                    <div class="p-3 border bg-light infoblock">
                        <div class="username font-weight-bold mb-2">${member.username}</div>
                        <button type="button" class="btn btn-secondary mr-2" onclick="editMember(${index})">Modifica</button>
                        <button type="button" class="btn btn-danger" onclick="deleteMember(${index})" ${team.utenti.length <= 2 ? "disabled" : ""}>Elimina</button>
                    </div>
                </div>
            `;
        });

        // Chiudi la prima row
        mainBlockHTML += `</div>`;

        // Seconda row: pulsante per aggiungere un membro
        mainBlockHTML += `
            <div class="row mt-3 justify-content-center">
                <button type="button" class="btn btn-primary" onclick="addMember()">Aggiungi Componente</button>
            </div>
        `;

        // Terza row: pulsante per salvare il team
        mainBlockHTML += `
            <div class="row mt-3 justify-content-center">
                <button type="button" class="btn btn-primary col-4" id="saveTeam">Salva</button>
            </div>
        `;

        // Chiudi il contenitore
        mainBlockHTML += `
                </div>
            </div>
        `;

        mainTag.innerHTML = mainBlockHTML;

        if (team.utenti.length >= 4) {
            mainTag.querySelector('.btn-primary').style.display = 'none';
        }
    }

    function updateDeleteButtonsState() {
        const deleteButtons = document.querySelectorAll('.btn-danger');
        const numMembers = document.querySelectorAll('.infoblock').length;

        deleteButtons.forEach(button => {
            button.disabled = numMembers <= 2;
        });
    }

    window.editMember = function(index) {
        const memberDiv = document.getElementById(`member-${index}`);
        const username = memberDiv.querySelector('.username').innerHTML.trim();

        memberDiv.innerHTML = `
            <div class="p-3 border bg-light infoblock">
                <input type="text" class="form-control mb-2" id="edit-username-${index}" value="${username}">
                <button type="button" class="btn btn-secondary mr-2" onclick="saveEdit(${index})">Salva</button>
                <button type="button" class="btn btn-danger" onclick="cancelEdit(${index})">Annulla</button>
            </div>
        `;

        // Nascondi il pulsante "Aggiungi Componente" in modalità edit
        mainTag.querySelector('.btn-primary').style.display = 'none';

    }

    window.saveEdit = function(index) {
        const newUsername = document.getElementById(`edit-username-${index}`).value;

        const memberDiv = document.getElementById(`member-${index}`);
        memberDiv.innerHTML = `
            <div class="p-3 border bg-light infoblock">
                <div class="username font-weight-bold mb-2">${newUsername}</div>
                <button type="button" class="btn btn-secondary mr-2" onclick="editMember(${index})">Modifica</button>
                <button type="button" class="btn btn-danger" onclick="deleteMember(${index})">Elimina</button>
            </div>
        `;

        // Controlla lo stato dei pulsanti "Elimina"
        updateDeleteButtonsState();

        // Mostra di nuovo il pulsante "Aggiungi Componente" se ci sono meno di 4 membri
        const numMembers = document.querySelectorAll('.infoblock').length;
        if (numMembers < 4) {
            mainTag.querySelector('.btn-primary').style.display = 'block';
        } else {
            mainTag.querySelector('.btn-primary').style.display = 'none';
        }
    }

    window.cancelEdit = function(index) {
        const memberDiv = document.getElementById(`member-${index}`);
        const username = team.utenti[index].username;

        memberDiv.innerHTML = `
            <div class="p-3 border bg-light infoblock">
                <div class="username font-weight-bold mb-2">${username}</div>
                <button type="button" class="btn btn-secondary mr-2" onclick="editMember(${index})">Modifica</button>
                <button type="button" class="btn btn-danger" onclick="deleteMember(${index})">Elimina</button>
            </div>
        `;

        // Controlla lo stato dei pulsanti "Elimina"
        updateDeleteButtonsState();

        // Mostra di nuovo il pulsante "Aggiungi Componente" se ci sono meno di 4 membri
        const numMembers = document.querySelectorAll('.infoblock').length;
        if (numMembers < 4) {
            mainTag.querySelector('.btn-primary').style.display = 'block';
        } else {
            mainTag.querySelector('.btn-primary').style.display = 'none';
        }
    }

    window.deleteMember = function(index) {
        counter--;
        console.log(counter);
        team.utenti.splice(index, 1);
        document.getElementById(`member-${index}`).remove();

        // Aggiorna lo stato dei pulsanti "Elimina"
        updateDeleteButtonsState();

        // Mostra il pulsante "Aggiungi Componente" se ci sono meno di 4 membri
        const numMembers = document.querySelectorAll('.infoblock').length;
        const addMemberButton = document.getElementById('addMember');

        console.log(counter);
        if (counter < 4) {
            mainTag.querySelector('.btn-primary').style.display = 'block';
        } else{
            mainTag.querySelector('.btn-primary').style.display = 'none';
        }
    }

    window.addMember = function() {
        const newIndex = document.querySelectorAll('.infoblock').length;
    
        const mainTag = document.querySelector("main");

        counter++;
        console.log(counter);
        let newMemberHTML = `
            <div class="col-md-6 text-center mb-3" id="member-${newIndex}">
                <div class="p-3 border bg-light infoblock">
                    <input type="text" class="form-control mb-2" id="edit-username-${newIndex}" value="">
                    <button type="button" class="btn btn-secondary mr-2" onclick="saveEdit(${newIndex})">Salva</button>
                    <button type="button" class="btn btn-danger" onclick="deleteMember(${newIndex})">Elimina</button>
                </div>
            </div>
        `;
        mainTag.querySelector('.row').insertAdjacentHTML('beforeend', newMemberHTML);
    
        // Nascondi il pulsante "Aggiungi Componente" se ci sono 4 membri
        const numMembers = document.querySelectorAll('.infoblock').length;
        
        if (counter >= 4) {
            mainTag.querySelector('.btn-primary').style.display = 'none';
        } else {
            mainTag.querySelector('.btn-primary').style.display = 'block';
        }
    
        // Aggiorna lo stato dei pulsanti "Elimina"
        updateDeleteButtonsState();
    }

    // Aggiorna lo stato dei pulsanti "Elimina" al caricamento iniziale
    updateDeleteButtonsState();

    savebutton = document.getElementById('saveTeam');

    savebutton.addEventListener('click', async function() {
        const updatedUsernames = Array.from(document.querySelectorAll('.username')).map(element => element.innerHTML.trim());
    
        // Controllo per username duplicati
        const duplicates = updatedUsernames.filter((username, index, self) => self.indexOf(username) !== index);
        if (duplicates.length > 0) {
            showError(`Gli username duplicati non sono consentiti: ${duplicates.join(', ')}`);
            return;
        }
    
        for (let i = 0; i < updatedUsernames.length; i++) {
            var user = await checkUsername(updatedUsernames[i]);
            if (user === null) {
                showError('Username non valido: ' + updatedUsernames[i]);
                return;
            }
        }
    
        const teamUsernames = copyTeam.utenti.map(user => user.username); // Prendi solo gli username dal team originale
    
        const newComponenti = updatedUsernames.filter(username => !teamUsernames.includes(username));
    
        console.log("componenti:");
        console.log(teamUsernames);
    
        console.log("Nuovi componenti:");
        console.log(newComponenti);
    
        for (let i = 0; i < newComponenti.length; i++) {
            var user = await checkUsername(newComponenti[i]);
            if (await verifyTeamMember(user.userid)) {
                showError(newComponenti[i] + ' è già membro di un team');
                return;
            }
        }
    
        const oldComponenti = teamUsernames.filter(username => !updatedUsernames.includes(username));
    
        console.log("Vecchi componenti:");
        console.log(oldComponenti);
    
        const query = new URLSearchParams({
            newComponents: JSON.stringify(newComponenti),
            oldComponents: JSON.stringify(oldComponenti),
            teamName: team.nome,
            numMembers: updatedUsernames.length,
        }).toString();
    
        const response = await fetch('../../PHP/Team/modifyTeam.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: query,
        });
    
        if (response.ok) {
            showPopup('Team modificato con successo!');
        } else {
            showError('Errore durante la modifica del team');
        }
    
        window.location.href = 'manageTeams.php';
    });
});

