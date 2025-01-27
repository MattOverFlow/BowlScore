import { generateGame } from "./game.js";

function showPopup(popupId, message) {
    const popup = document.getElementById(popupId);
    popup.textContent = message;
    popup.style.display = 'block';
    setTimeout(() => {
        popup.style.display = 'none';
    }, 3000); // Popup will be hidden after 3 seconds
}

document.addEventListener('DOMContentLoaded', function () {
    const tournamentTypeSelect = document.getElementById('tournamentType');
    const additionalFieldsContainer = document.getElementById('additionalFieldsContainer');

    tournamentTypeSelect.addEventListener('change', function () {
        // Rimuovi eventuali campi aggiunti in precedenza
        additionalFieldsContainer.innerHTML = '';

        if (tournamentTypeSelect.value === 'singolo') {
            // Aggiungi campo per il numero di partecipanti singoli
            const participantsDiv = document.createElement('div');
            participantsDiv.className = 'row w-100 mt-3';

            const participantsLabelDiv = document.createElement('div');
            participantsLabelDiv.className = 'col-6 d-flex justify-content-start align-items-center';
            const participantsLabel = document.createElement('label');
            participantsLabel.for = 'numParticipants';
            participantsLabel.innerText = 'Numero di partecipanti:';
            participantsLabelDiv.appendChild(participantsLabel);

            const participantsSelectDiv = document.createElement('div');
            participantsSelectDiv.className = 'col-6 d-flex justify-content-end align-items-center';
            const participantsSelect = document.createElement('select');
            participantsSelect.id = 'numParticipants';
            participantsSelect.name = 'numParticipants';
            participantsSelect.className = 'form-control ml-2';

            const emptyOption = document.createElement('option');
            emptyOption.value = '';
            emptyOption.text = 'Seleziona tipo';
            emptyOption.selected = true;
            emptyOption.disabled = true;
            participantsSelect.appendChild(emptyOption);


            // Aggiungi opzioni al menu a tendina
            for (let i = 4; i <= 10; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.text = i;
                participantsSelect.appendChild(option);
            }

            participantsSelectDiv.appendChild(participantsSelect);
            participantsDiv.appendChild(participantsLabelDiv);
            participantsDiv.appendChild(participantsSelectDiv);
            additionalFieldsContainer.appendChild(participantsDiv);

            participantsSelect.addEventListener('change', function () {
                // Rimuovi eventuali campi username aggiunti in precedenza
                const usernameFields = document.getElementById('usernameFields');
                if (usernameFields) {
                    usernameFields.remove();
                }

                // Crea un nuovo div per i campi username
                const newUsernameFields = document.createElement('div');
                newUsernameFields.id = 'usernameFields';
                newUsernameFields.className = 'col-12 mt-3 d-flex flex-column align-items-center';

                for (let i = 1; i <= participantsSelect.value; i++) {
                    const usernameLabel = document.createElement('label');
                    usernameLabel.for = `username${i}`;
                    usernameLabel.innerText = `Username partecipante ${i}:`;
                    newUsernameFields.appendChild(usernameLabel);

                    const usernameInput = document.createElement('input');
                    usernameInput.type = 'text';
                    usernameInput.id = `username${i}`;
                    usernameInput.name = `username${i}`;
                    usernameInput.className = 'form-control ml-2 mb-2';
                    newUsernameFields.appendChild(usernameInput);
                }

                additionalFieldsContainer.appendChild(newUsernameFields);
            });
        } else if (tournamentTypeSelect.value === 'squadre') {
            // Aggiungi campo per il numero di team
            const numTeamsDiv = document.createElement('div');
            numTeamsDiv.className = 'row w-100 mt-3';

            const numTeamsLabelDiv = document.createElement('div');
            numTeamsLabelDiv.className = 'col-6 d-flex justify-content-start align-items-center';
            const numTeamsLabel = document.createElement('label');
            numTeamsLabel.for = 'numTeams';
            numTeamsLabel.innerText = 'Numero di team:';
            numTeamsLabelDiv.appendChild(numTeamsLabel);

            const numTeamsSelectDiv = document.createElement('div');
            numTeamsSelectDiv.className = 'col-6 d-flex justify-content-end align-items-center';
            const numTeamsSelect = document.createElement('select');
            numTeamsSelect.id = 'numTeams';
            numTeamsSelect.name = 'numTeams';
            numTeamsSelect.className = 'form-control ml-2';

            // Aggiungi opzioni al menu a tendina
            for (let i = 2; i <= 4; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.text = i;
                numTeamsSelect.appendChild(option);
            }

            numTeamsSelectDiv.appendChild(numTeamsSelect);
            numTeamsDiv.appendChild(numTeamsLabelDiv);
            numTeamsDiv.appendChild(numTeamsSelectDiv);
            additionalFieldsContainer.appendChild(numTeamsDiv);

            // Aggiungi campo per la dimensione del team
            const teamSizeDiv = document.createElement('div');
            teamSizeDiv.className = 'row w-100 mt-3';

            const teamSizeLabelDiv = document.createElement('div');
            teamSizeLabelDiv.className = 'col-6 d-flex justify-content-start align-items-center';
            const teamSizeLabel = document.createElement('label');
            teamSizeLabel.for = 'teamSize';
            teamSizeLabel.innerText = 'Dimensione del team:';
            teamSizeLabelDiv.appendChild(teamSizeLabel);

            const teamSizeSelectDiv = document.createElement('div');
            teamSizeSelectDiv.className = 'col-6 d-flex justify-content-end align-items-center';
            const teamSizeSelect = document.createElement('select');
            teamSizeSelect.id = 'teamSize';
            teamSizeSelect.name = 'teamSize';
            teamSizeSelect.className = 'form-control ml-2';

            // Aggiungi opzioni al menu a tendina
            for (let i = 2; i <= 4; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.text = i;
                teamSizeSelect.appendChild(option);
            }

            teamSizeSelectDiv.appendChild(teamSizeSelect);
            teamSizeDiv.appendChild(teamSizeLabelDiv);
            teamSizeDiv.appendChild(teamSizeSelectDiv);
            additionalFieldsContainer.appendChild(teamSizeDiv);

            // Funzione per creare campi team
            function createTeamFields() {

                const existingTeamFields = document.getElementById('teamFields');
                if (existingTeamFields) {
                    existingTeamFields.remove();
                }

                // Crea un nuovo div per i campi team
                const newTeamFields = document.createElement('div');
                newTeamFields.id = 'teamFields';
                newTeamFields.className = 'col-12 mt-3 d-flex flex-column align-items-center';

                let errorOccurred = false; 

                for (let i = 1; i <= numTeamsSelect.value; i++) {
                    const teamDiv = document.createElement('div');
                    teamDiv.className = 'mb-3 w-100';

                    const teamLabel = document.createElement('label');
                    teamLabel.for = `teamName${i}`;
                    teamLabel.innerText = `Nome squadra ${i}:`;
                    teamDiv.appendChild(teamLabel);

                    const teamInput = document.createElement('input');
                    teamInput.type = 'text';
                    teamInput.id = `teamName${i}`;
                    teamInput.name = `teamName${i}`;
                    teamInput.className = 'form-control ml-2 mb-2';
                    teamDiv.appendChild(teamInput);

                    // Listener per gestire l'evento di completamento input
                    teamInput.addEventListener('blur', async function () {
                        const teamName = teamInput.value.trim();
                        const teamFields = document.getElementById('teamFields');

                        if (teamName === '') {
                            // Se il nome del team è vuoto, elimina solo i checkbox associati
                            const existingParticipantsDiv = teamDiv.querySelector('.d-flex');
                            if (existingParticipantsDiv) {
                                existingParticipantsDiv.remove(); // Elimina i membri esistenti
                            }
                            return;
                        }

                        // Se il nome cambia, rimuovi i checkbox esistenti e rifai la ricerca
                        const existingParticipantsDiv = teamDiv.querySelector('.d-flex');
                        if (existingParticipantsDiv) {
                            existingParticipantsDiv.remove(); // Elimina i membri esistenti
                        }

                        try {
                            const teamData = await ScaricaDatiUtentiTeam(teamName);
                            if (!teamData) {
                                alert(`Il team "${teamName}" non esiste.`);
                                errorOccurred = true;
                                return;
                            }

                            if (teamData.length < teamSizeSelect.value) {
                                alert(
                                    `Il team "${teamName}" ha meno membri di quelli richiesti (${teamSizeSelect.value}).`
                                );
                                errorOccurred = true;
                                return;
                            }

                            // Crea checkbox per i membri del team
                            const participantsDiv = document.createElement('div');
                            participantsDiv.className = 'd-flex flex-wrap mt-2';

                            let selectedCount = 0; // Contatore per i membri selezionati

                            teamData.forEach((player) => {
                                const checkboxDiv = document.createElement('div');
                                checkboxDiv.className = 'form-check mr-3';

                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.className = 'form-check-input';
                                checkbox.id = `team${i}Participant${player.id}`;
                                checkbox.name = `team${i}Participants[]`;
                                checkbox.value = player.username;

                                const label = document.createElement('label');
                                label.className = 'form-check-label';
                                label.htmlFor = checkbox.id;
                                label.innerText = player.username;
                                label.style.marginRight = '15px';

                                // Limita il numero di selezioni
                                checkbox.addEventListener('change', function () {
                                    if (checkbox.checked) {
                                        selectedCount++;
                                        if (selectedCount > teamSizeSelect.value) {
                                            alert(
                                                `Puoi selezionare solo ${teamSizeSelect.value} membri per il team.`
                                            );
                                            checkbox.checked = false;
                                            selectedCount--;
                                        }
                                    } else {
                                        selectedCount--;
                                    }
                                });

                                checkboxDiv.appendChild(checkbox);
                                checkboxDiv.appendChild(label);
                                participantsDiv.appendChild(checkboxDiv);
                            });

                            teamDiv.appendChild(participantsDiv);
                        } catch (error) {
                            console.error('Errore durante il recupero dei dati del team:', error);
                            alert('Il team non esiste');
                            errorOccurred = true;
                        }
                    });

                    newTeamFields.appendChild(teamDiv);
                }

                if (errorOccurred) {
                    showPopup('errorPopup', 'Errore nel recupero dei dati del team.');
                    return;
                }

                additionalFieldsContainer.appendChild(newTeamFields);
            }

            numTeamsSelect.addEventListener('change', createTeamFields);
            teamSizeSelect.addEventListener('change', createTeamFields);

            // Trigger change event per inizializzare i campi
            numTeamsSelect.dispatchEvent(new Event('change'));
        }
    });

    const createTournamentButton = document.querySelector('.btn.btn-primary');
    createTournamentButton.addEventListener('click', async function () {

        const tournamentType = tournamentTypeSelect.value;
        const tournamentName = document.getElementById('tournamentName').value;
        if (tournamentName === '') {
            alert('Inserisci un nome per il torneo.');
            return;
        }

        if (tournamentType === 'singolo') {

            const numParticipants = parseInt(document.getElementById('numParticipants').value);
            const participants = [];

            // Raccogli i nomi dei partecipanti
            for (let i = 1; i <= numParticipants; i++) {
                const username = document.getElementById(`username${i}`).value;
                participants.push(username);
            }

            // Verifica se ci sono nomi duplicati
            const uniqueNames = new Set(participants);
            if (uniqueNames.size !== participants.length) {
                showPopup('errorPopup', 'Ci sono partecipanti duplicati. Ogni partecipante deve avere un nome unico.');
                return; // Interrompe l'operazione se ci sono duplicati
            }

            // Ora procedi con la verifica dell'esistenza degli username
            for (let i = 1; i <= numParticipants; i++) {
                const username = document.getElementById(`username${i}`).value;
                const userId = await usernameExist(username);
                if (!userId) {
                    alert('L\'username inserito non esiste: ' + username);
                    return;
                }
            }

            const query = new URLSearchParams({
                tournamentName: tournamentName,
                numParticipants: numParticipants,
            }).toString();

            const response = await fetch('../../PHP/Torneo/CreaTorneoSingolo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: query,
            });

            var idTorneoSingolo = await response.json();

            if (!idTorneoSingolo) {
                showPopup('errorPopup', 'Errore nella creazione del torneo.');
                return;
            }

            for (let i = 1; i <= 4; i++) {
                var result = await creaPartitaSingolo("Partita " + i, participants.length, participants, idTorneoSingolo);
                if (!result) {
                    showPopup('errorPopup', 'Errore nella creazione della partita.');
                    return;
                };

            }

            showPopup('successPopup', 'Torneo creato con successo!');

            location.reload();


        } else if (tournamentType === 'squadre') {

            const numTeams = parseInt(document.getElementById('numTeams').value);
            const teamSize = parseInt(document.getElementById('teamSize').value);

            const teams = [];
            const matches = [];
            const teamNames = [];

            if (numTeams < 2 || numTeams > 4) {
                alert('Il numero di squadre deve essere compreso tra 2 e 4.');
                return;
            }

            for (let i = 1; i <= numTeams; i++) {
                const teamName = document.getElementById(`teamName${i}`).value.trim();
                if (!teamName) {
                    alert(`Inserisci il nome per la squadra ${i}`);
                    return;
                }

                if (teamNames.includes(teamName)) {
                    showPopup('errorPopup', `Il nome della squadra "${teamName}" è duplicato.`);
                    return;
                }
                teamNames.push(teamName);

                const selectedPlayers = [];
                const checkboxes = document.querySelectorAll(`input[name="team${i}Participants[]"]:checked`);
                checkboxes.forEach(checkbox => {
                    selectedPlayers.push(checkbox.value);
                });

                if (selectedPlayers.length !== parseInt(teamSize)) {
                    alert(`La squadra "${teamName}" deve avere esattamente ${teamSize} membri selezionati.`);
                    return;
                }

                teams.push({
                    teamName: teamName,
                    players: selectedPlayers
                });
            }

            const query = new URLSearchParams({
                tournamentName: tournamentName,
                numTeams: numTeams,
                teamSize: teamSize,
                teams: JSON.stringify(teams),
            }).toString();

            const response = await fetch('../../PHP/Torneo/CreaTorneoSquadre.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: query,
            });

            var idTorneoSquadre = await response.json();

            if (!idTorneoSquadre) {
                showPopup('errorPopup', 'Errore nella creazione del torneo.');
                return;
            }

            if (numTeams === 2) {
                for (let i = 1; i <= 4; i++) {
                    matches.push({
                        matchName: `Partita ${i}`,
                        teams: [teams[0], teams[1]],
                    });
                }
            } else if (numTeams === 3) {
                const matchups = [
                    [0, 1],
                    [0, 2],
                    [1, 2],
                    [1, 0],
                    [2, 0],
                    [2, 1],
                ];

                matchups.forEach((pair, index) => {
                    matches.push({
                        matchName: `Partita ${index + 1}`,
                        teams: [teams[pair[0]], teams[pair[1]]],
                    });
                });
            } else if (numTeams === 4) {
                // Caso 3: 4 team, tutti contro tutti + una partita casuale
                const allMatchups = [];
                for (let i = 0; i < numTeams; i++) {
                    for (let j = i + 1; j < numTeams; j++) {
                        allMatchups.push([i, j]);
                    }
                }

                allMatchups.forEach((pair, index) => {
                    matches.push({
                        matchName: `Partita ${index + 1}`,
                        teams: [teams[pair[0]], teams[pair[1]]],
                    });
                });

                // Aggiungi una partita casuale per ogni team
                teams.forEach((team, teamIndex) => {
                    const possibleOpponents = teams.filter((_, i) => i !== teamIndex);
                    const randomOpponent = possibleOpponents[Math.floor(Math.random() * possibleOpponents.length)];
                    matches.push({
                        matchName: `Partita extra ${team.teamName}`,
                        teams: [team, randomOpponent],
                    });
                });
            } else {
                alert('Il numero di team deve essere tra 2 e 4.');
                return;
            }

            if (matches != null) {

                for (let i = 0; i < matches.length; i++) {

                    var partecipanti = [];

                    const team1 = matches[i].teams[0];
                    const team2 = matches[i].teams[1];

                    team1.players.forEach(player => partecipanti.push(player));

                    team2.players.forEach(player => partecipanti.push(player));

                    var result = await creaPartitaSquadre(matches[i].matchName, partecipanti.length, partecipanti, idTorneoSquadre);
                    if (!result) {
                        showPopup('errorPopup', 'Errore nella creazione della partita.');
                        return;
                    };
                }

                showPopup('successPopup', 'Torneo creato con successo!');

                location.reload();

            } else {
                alert('Errore nella creazione delle partite.');
                return;
            }

        } else {
            alert('Seleziona un tipo di torneo.');
            return;
        }
    });


});

// Funzione per simulare il recupero dei dati del team
async function ScaricaDatiUtentiTeam(teamName) {
    const response = await fetch('../../PHP/Team/InfoTeam.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `teamName=${teamName}`,
    });

    if (response.ok) {
        var data = await response.json();
        const users = [];
        for (const user of data.utenti) {
            const response = await fetch('../../PHP/Utente/DownloadDatiUtente.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `userid=${user}`,
            });

            if (response.ok) {
                const userData = await response.json();
                users.push({ id: user, username: userData.username });
            }
        }
        return users;
    }
}

async function usernameExist(username) {
    const response = await fetch('../../PHP/Utente/DownloadDatiDaUsername.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `username=${username}`,
    });

    if (response.ok) {
        var data = await response.json();
        return data ? data.userid : false;
    }
}


async function creaPartitaSingolo(nomePartita, numPartecipanti, partecipanti, codiceTorneoSingolo) {
    console.log('Creazione partita singolo:', nomePartita, numPartecipanti, partecipanti, codiceTorneoSingolo);

    let game = generateGame(numPartecipanti);

    let scoresList = [];
    let totalScoresList = [];

    for (let result of game) {
        scoresList.push(result.scores);
        totalScoresList.push(result.totalScores);
    }

    const query = new URLSearchParams({
        gameName: nomePartita,
        numParticipants: numPartecipanti,
        participants: JSON.stringify(partecipanti),
        scoresList: JSON.stringify(scoresList),
        totalScoresList: JSON.stringify(totalScoresList),
        codiceTorneoSingolo: codiceTorneoSingolo,
    }).toString();

    const response = await fetch('../../PHP/Partite/createGame.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: query,
    });

    if (response.ok) {
        return true;
    } else {
        return false;
    }
}


async function creaPartitaSquadre(nomePartita, numPartecipanti, partecipanti, codiceTorneoSquadre) {
    console.log('Creazione partita squadre:', nomePartita, numPartecipanti, partecipanti, codiceTorneoSquadre);

    let game = generateGame(numPartecipanti);

    let scoresList = [];
    let totalScoresList = [];

    for (let result of game) {
        scoresList.push(result.scores);
        totalScoresList.push(result.totalScores);
    }

    const query = new URLSearchParams({
        gameName: nomePartita,
        numParticipants: numPartecipanti,
        participants: JSON.stringify(partecipanti),
        scoresList: JSON.stringify(scoresList),
        totalScoresList: JSON.stringify(totalScoresList),
        codiceTorneoSquadre: codiceTorneoSquadre,
    }).toString();

    const response = await fetch('../../PHP/Partite/createGame.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: query,
    });

    if (response.ok) {
        return true;
    } else {
        return false;
    }
}