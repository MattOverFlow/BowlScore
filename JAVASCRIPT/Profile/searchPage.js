// Funzione per ottenere i parametri dell'URL
function getUrlParams() {
    const params = {};
    const queryString = window.location.search.substring(1);
    const regex = /([^&=]+)=([^&]*)/g;
    let m;
    while (m = regex.exec(queryString)) {
        params[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
    }
    return params;
}

async function fetchUserList(query) {
    const response = await fetch('../../PHP/Utente/userSearchQuery.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `stringa=${query}`,
    });

    const data = await response.json();
    console.log("Ecco i data:\n"+data);
    if(data.error === 'No users found'){
        return null;
    } else {
        return data;
    }
}


// Ottieni i parametri dell'URL
const urlParams = getUrlParams();

// Verifica se il parametro 'type' è 'admin'
const isAdmin = urlParams.type === 'admin';

const sidebar = document.getElementById('mySidebar');

if(isAdmin) {
    sidebar.innerHTML = `
        <a href="../AdminProfile/createGame.php" class="sidebarField">Crea partita</a>
        <a href="../AdminProfile/createTournament.php" class="sidebarField">Crea torneo</a>
        <a href="../AdminProfile/createTeam.php" class="sidebarField">Crea team</a>
        <a href="../AdminProfile/manageTournaments.php" class="sidebarField">Storico tornei</a>
        <a href="../AdminProfile/manageTeams.php" class="sidebarField">Gestione teams</a>
        <a href="../Statistics/generalStatistic.php?type=admin" class="sidebarField">Classifiche generali</a>
        <a href="../UserProfile/searchPage.php?type=admin" class="sidebarField">Cerca utenti</a>                    
        <a href="../../PHP/Utils/Logout.php" class="sidebarField">Logout</a>
    `;
}

var searchInput = document.getElementById("searchInput");
var usernameLabel = document.getElementById("usernameLabel");

searchInput.addEventListener("input", async function() {
    var query = searchInput.value.toLowerCase();
    searchList.innerHTML = '';
    usernameLabel.style.display = 'none';
    if (query || query.length > 0) {
        var userList = await fetchUserList(query);

        userList.forEach(function(user) {
                var row = document.createElement("div");
                row.className = "row col-9 friendlist-row";
                row.innerHTML = `
                    <div class="col-md-3 username">${user.username}</div>
                    <div class="col-md-3 fullname">${user.nome}</div>
                    <div class="col-md-3 fullname">${user.cognome}</div>
                `;
                row.addEventListener("click", function() {
                    window.location.href = isAdmin 
                        ? `../UserProfile/friendPage.php?useridUtente=${user.userid}&type=admin` 
                        : `friendPage.php?useridUtente=${user.userid}`;
                });
                searchList.appendChild(row);
        });

        if (userList === null) {
            usernameLabel.style.display = 'block'; 
        }
    } else {
        usernameLabel.style.display = 'block';
    }
});