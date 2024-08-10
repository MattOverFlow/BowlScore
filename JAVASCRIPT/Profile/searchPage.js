document.addEventListener("DOMContentLoaded", function() {

    var userList = [
        {
            "username": "capo1",
            "nome": "Mario",
            "cognome": "Rossi",
            "isFriend": true
        },
        {
            "username": "capo2",
            "nome": "Luigi",
            "cognome": "Verdi",
            "isFriend": false
        },
        {
            "username": "capo3",
            "nome": "Giovanni",
            "cognome": "Bianchi",
            "isFriend": true
        }
    ];

    var searchList = document.getElementById("searchList");
    var searchInput = document.getElementById("searchInput");
    var usernameLabel = document.getElementById("usernameLabel");

    searchInput.addEventListener("input", function() {
        var query = searchInput.value.toLowerCase();
        searchList.innerHTML = ''; // Clear previous results
        usernameLabel.style.display = 'none'; // Hide the "Username non trovato" message

        var found = false;

        userList.forEach(function(user) {
                var row = document.createElement("div");
                row.className = "row col-9 friendlist-row";
                row.innerHTML = `
                    <div class="col-md-3 username">${user.username}</div>
                    <div class="col-md-3 fullname">${user.nome}</div>
                    <div class="col-md-3 fullname">${user.cognome}</div>
                `;
                row.addEventListener("click", function() {
                    window.location.href = `friendPage.php?username=${user.username}`;
                });
                searchList.appendChild(row);
        });
        if (!found) {
            usernameLabel.style.display = 'block'; // Show the "Username non trovato" message
        }
    });

});