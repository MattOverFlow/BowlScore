// Function to open and close the sidebar
const openOptionsButtons = document.getElementById("openbtn");
const closeOptions = document.getElementById("closebtn");
if (openOptionsButtons != null) {
    openOptionsButtons.addEventListener("click", function () { openSideBar(); });
}
if (closeOptions != null) {
    closeOptions.addEventListener("click", function () { closeSideBar(); });
}

function openSideBar() {
    document.getElementById("mySidebar").style.width = "250px";
}

function closeSideBar() {
    document.getElementById("mySidebar").style.width = "0";
}