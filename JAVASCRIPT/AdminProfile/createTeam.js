function showError(message) {
    const errorPopup = document.getElementById('errorPopup');
    errorPopup.textContent = message;
    errorPopup.style.display = 'block';
    setTimeout(() => {
        errorPopup.style.display = 'none';
    }, 3000);
}

function showSuccess(message) {
    const successPopup = document.getElementById('successPopup');
    if (successPopup) {
        successPopup.textContent = message;
        successPopup.style.display = 'block';
        setTimeout(() => {
            successPopup.style.display = 'none';
        }, 3000);
    } else {
        console.error('Success popup element not found');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const teamMembersSelect = document.getElementById('teamMembers');
    const teamMembersContainer = document.getElementById('teamMembersContainer');

    teamMembersSelect.addEventListener('change', function () {
        const numberOfMembers = parseInt(this.value);
        updateTeamMembersFields(numberOfMembers);
    });

    function updateTeamMembersFields(numberOfMembers) {
        // Rimuovi tutti i campi esistenti
        while (teamMembersContainer.firstChild) {
            teamMembersContainer.removeChild(teamMembersContainer.firstChild);
        }

        // Aggiungi i campi per i membri del team
        for (let i = 1; i <= numberOfMembers; i++) {
            const label = document.createElement('label');
            label.setAttribute('for', `member${i}`);
            label.textContent = `Username componente ${i}:`;

            const input = document.createElement('input');
            input.type = 'text';
            input.id = `member${i}`;
            input.name = `member${i}`;
            input.className = 'form-control mb-2';

            teamMembersContainer.appendChild(label);
            teamMembersContainer.appendChild(input);
        }
    }

    const createTeamButton = document.querySelector('.btn.btn-primary');
    createTeamButton.addEventListener('click',async function () {

        const teamName = document.getElementById('teamName').value;
        const teamMembers = [];
        const numberOfMembers = parseInt(teamMembersSelect.value);

        const teamInfoResult = await teamInfo(teamName);
        if (teamInfoResult != null) {
            showError('Il nome del team è già in uso');
            return;
        }

        for (let i = 1; i <= numberOfMembers; i++) {
            const member = document.getElementById(`member${i}`).value;
            teamMembers.push(member);

            var user = await userInfo(member);

            if (user == null) {
                showError('Il membro del team non esiste : ' + member);
                return;
            }

            if (await verifyTeamMember(user.userid)) {
                showError('Il membro del team è già in un team : ' + member);
                return;
            }
        }

        await fetch('../../PHP/Team/CreaTeam.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                teamName: teamName,
                teamSize: numberOfMembers,
                teamMembers: teamMembers
            }),
        });

        localStorage.setItem('teamCreated', 'true');
        location.reload();

    });

    if (localStorage.getItem('teamCreated') === 'true') {
        localStorage.removeItem('teamCreated');
        showSuccess('Team creato con successo');
    }

    async function teamInfo(nomeTeam) {

        const response = await fetch('../../PHP/Team/InfoTeam.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `teamName=${nomeTeam}`,
        });

        if (response.ok) {
            return await response.json();
        }
    }

    async function userInfo(username) {
        const response = await fetch('../../PHP/Utente/DownloadDatiDaUsername.php', {
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


});