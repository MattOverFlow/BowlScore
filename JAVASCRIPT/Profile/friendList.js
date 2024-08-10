document.addEventListener("DOMContentLoaded", function() {
    
    var users = [
        { "username": "capo1", "nome" : "Capo", "cognome" : "Capo",},
        { "username": "capo2", "nome" : "Capo", "cognome" : "Capo",},
        { "username": "capo3", "nome" : "Capo", "cognome" : "Capo",},
        { "username": "capo4", "nome" : "Capo", "cognome" : "Capo",},
        { "username": "capo5", "nome" : "Capo", "cognome" : "Capo",},
    ];

    var user = {
        "username": "Franzop09",
        "nome": "Mario",
        "cognome": "Rossi",
        "isFriend": true
    };

    var list = document.getElementById("usersList");
    var username = document.getElementById("username");

    username.innerHTML = user.username;

    if(users.length == 0){
        return;
    }

    users.forEach(function(user) {
        var noUsers = document.getElementById("usernameLabel");
        noUsers.style.display = 'none';
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
        list.appendChild(row);
    });
});