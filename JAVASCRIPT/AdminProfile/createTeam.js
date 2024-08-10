document.addEventListener('DOMContentLoaded', function() {
    const teamMembersSelect = document.getElementById('teamMembers');
    const teamMembersContainer = document.getElementById('teamMembersContainer');

    teamMembersSelect.addEventListener('change', function() {
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
});