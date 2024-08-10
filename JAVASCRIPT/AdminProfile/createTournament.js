document.addEventListener('DOMContentLoaded', function() {
    const tournamentTypeSelect = document.getElementById('tournamentType');
    const additionalFieldsContainer = document.getElementById('additionalFieldsContainer');

    tournamentTypeSelect.addEventListener('change', function() {
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

            // Aggiungi un'opzione vuota non selezionabile e selezionata di default al menu a tendina
            const emptyOption = document.createElement('option');
            emptyOption.value = '';
            emptyOption.text = 'Seleziona il numero di partecipanti';
            emptyOption.disabled = true;
            emptyOption.selected = true;
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

            participantsSelect.addEventListener('change', function() {
                // Rimuovi eventuali campi username aggiunti in precedenza
                const usernameFields = document.getElementById('usernameFields');
                if (usernameFields) {
                    usernameFields.remove();
                }

                // Crea un nuovo div per i campi username
                const newUsernameFields = document.createElement('div');
                newUsernameFields.id = 'usernameFields';
                newUsernameFields.className = 'col-6 mt-3 d-flex flex-column align-items-center';

                for (let i = 1; i <= participantsSelect.value; i++) {
                    const usernameLabel = document.createElement('label');
                    usernameLabel.for = `username${i}`;
                    usernameLabel.innerText = `Username partecipante ${i}:`;
                    newUsernameFields.appendChild(usernameLabel);

                    const usernameInput = document.createElement('input');
                    usernameInput.type = 'text';
                    usernameInput.id = `username${i}`;
                    usernameInput.name = `username${i}`;
                    usernameInput.className = 'form-control ml-2';
                    newUsernameFields.appendChild(usernameInput);
                }

                additionalFieldsContainer.appendChild(newUsernameFields);
            });
        } else if (tournamentTypeSelect.value === 'squadre') {
            // Aggiungi campo per la dimensione dei team
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

            // Aggiungi campi per i nomi delle squadre
            const newTeamFields = document.createElement('div');
            newTeamFields.className = 'col-6 mt-3 d-flex flex-column align-items-center';

            for (let i = 1; i <= 4; i++) {
                const teamLabel = document.createElement('label');
                teamLabel.for = `teamName${i}`;
                teamLabel.innerText = `Nome squadra ${i}:`;
                newTeamFields.appendChild(teamLabel);

                const teamInput = document.createElement('input');
                teamInput.type = 'text';
                teamInput.id = `teamName${i}`;
                teamInput.name = `teamName${i}`;
                teamInput.className = 'form-control ml-2';
                newTeamFields.appendChild(teamInput);
            }
            additionalFieldsContainer.appendChild(newTeamFields);
        }
    });
});