document.addEventListener("DOMContentLoaded", function() {

    var user = {
        "username": "capo3",
        "amici": [
            { "username": "capo2" }
        ],
        "nome": "Giovanni",
        "cognome": "Bianchi",
        "followers": 10,
    };

    var username = document.getElementById("username");
    var followers = document.getElementById("nAmici");
    var name_surname = document.getElementById("name_surname");

    username.innerHTML = user.username;
    followers.innerHTML = user.followers;
    name_surname.innerHTML = user.nome + " " + user.cognome;

});