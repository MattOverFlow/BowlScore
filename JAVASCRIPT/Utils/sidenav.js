document.addEventListener('DOMContentLoaded', function() {
    const openOptionsButtons = document.getElementById("openbtn");
    const closeOptions = document.getElementById("closebtn");

    if (openOptionsButtons != null) {
        openOptionsButtons.addEventListener("click", function () { openSideBar(); });
    }
    if (closeOptions != null) {
        closeOptions.addEventListener("click", function () { closeSideBar(); });
    }

    // Funzione per aprire la barra laterale
    function openSideBar() {
        document.getElementById("mySidebar").style.width = "250px";
    }

    // Funzione per chiudere la barra laterale
    function closeSideBar() {
        console.log("close");
        document.getElementById("mySidebar").style.width = "0";
    }

    // Utilizza un MutationObserver per rilevare le modifiche al DOM
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.id === "openbtn") {
                        node.addEventListener("click", function () { openSideBar(); });
                    }
                    if (node.id === "closebtn") {
                        node.addEventListener("click", function () { closeSideBar(); });
                    }
                });
            }
        });
    });

    // Configura l'osservatore per monitorare le modifiche al DOM
    observer.observe(document.body, { childList: true, subtree: true });
});