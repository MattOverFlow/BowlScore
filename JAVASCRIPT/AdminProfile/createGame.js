import { generateGame } from "./game.js";

async function getUserInfo(username) {

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

async function getCardInfo(userid) {

    const response = await fetch('../../PHP/Carte/DownloadDatiCarta.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `userid=${userid}`,
    });

    if (response.ok) {

        return await response.json();

    }

}

async function getSubscriptionInfo(userid) {

    const response = await fetch('../../PHP/Carte/DownloadDatiCarta.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `userid=${userid}`,
    });

    if (response.ok) {
        return await response.json();
    }
}

function showError(message) {
    const errorPopup = document.getElementById('errorPopup');
    errorPopup.textContent = message;
    errorPopup.style.display = 'block';
    setTimeout(() => {
        errorPopup.style.display = 'none';
    }, 3000);
}

function showPopup(popupId, message) {
    const popup = document.getElementById(popupId);
    popup.textContent = message;
    popup.style.display = 'block';
    setTimeout(() => {
        popup.style.display = 'none';
    }, 3000); // Popup will be hidden after 3 seconds
}

document.addEventListener('DOMContentLoaded', async function () {
    const participantsSelect = document.getElementById('participants');
    const participantsList = document.getElementById('participantsList');

    function createParticipantFields(numParticipants) {
        participantsList.innerHTML = '';

        for (let i = 1; i <= numParticipants; i++) {
            const participantDiv = document.createElement('div');
            participantDiv.classList.add('mb-2');

            const label = document.createElement('label');
            label.textContent = `Partecipante ${i}:`;

            const input = document.createElement('input');
            input.type = 'text';
            input.name = `participant${i}`;
            input.classList.add('form-control');
            input.placeholder = `Nome partecipante ${i}`;

            participantDiv.appendChild(label);
            participantDiv.appendChild(input);
            participantsList.appendChild(participantDiv);
        }
    }

    participantsSelect.addEventListener('change', async function () {
        const numParticipants = participantsSelect.value;
        createParticipantFields(numParticipants);
    });

    createParticipantFields(participantsSelect.value);

    document.getElementById('createGameButton').addEventListener('click', async function () {

        const gameName = document.getElementById('gameName').value;
        const participants = [];
        const numParticipants = participantsSelect.value;

        if (numParticipants < 2) {
            showError('Inserisci almeno 2 partecipanti');
            return;
        }

        for (let i = 1; i <= numParticipants; i++) {
            const participantName = document.querySelector(`[name="participant${i}"]`).value;

            var user = await getUserInfo(participantName);
            if (user == null) {
                showError('Partecipante non registrato nel sistema: ' + participantName);
                return;
            }

            var card = await getCardInfo(user.userid);

            if (card == null) {
                showError('Partecipante senza carta associata: ' + participantName);
                return;
            }

            var subscription = await getSubscriptionInfo(user.userid);
            if (subscription == null) {

                if (card.partiteTotali <= 0) {
                    showError('Partecipante senza abbastanza partite: ' + participantName);
                    return;
                }
            } else {
                var dataOggi = new Date();
                var dataScadenzaCarta = new Date(subscription.dataScadenza);

                if (dataOggi > dataScadenzaCarta) {
                    if (card.partiteTotali <= 0) {
                        showError('Partecipante senza abbastanza partite: ' + participantName);
                        return;
                    }
                }
            }


            if (participantName) {
                participants.push(participantName);
            }
        }

        let game = generateGame(numParticipants);

        let scoresList = [];
        let totalScoresList = [];

        for (let result of game) {
            scoresList.push(result.scores);
            totalScoresList.push(result.totalScores);
        }

        const query = new URLSearchParams({
            gameName: gameName,
            numParticipants: numParticipants,
            participants: JSON.stringify(participants),
            scoresList: JSON.stringify(scoresList),
            totalScoresList: JSON.stringify(totalScoresList)
        }).toString();

        const response = await fetch('../../PHP/Partite/createGame.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: query,
        });

        location.reload();

        if (response.ok) {
            showPopup('successPopup', 'Partita creata con successo!');
        } else {
            showPopup('errorPopup', 'Errore nella creazione della partita.');
        }
    });
});