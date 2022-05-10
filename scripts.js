/* Make a better system
function clearMsg() {
    setTimeout(() => {
        var msg = document.getElementById("msg");
        msg.innerHTML = "";
    }, 10000);
} */
function showMsg() {
    var username = document.getElementById("username");
    var msg = document.getElementById("msg");
    msg.class = "w3-red";
    msg.innerHTML = username.value;
    return true;
}
function toggleFoxes() {
    var divFoxes = document.getElementById("foxes");
    if (divFoxes.style.display == "none") {
        divFoxes.style.display = "block";
    } else {
        divFoxes.style.display = "none";
    }
}
function dropdownToggle() {
    var dropdown = document.getElementById("dropdown");
    if(dropdown.className.indexOf("w3-show") == -1) {
        dropdown.className += " w3-show";
    } else {
        dropdown.className = dropdown.className.replace(" w3-show", "");
    }
}
function openModal(content) {
    document.getElementById('modal').style.display = "block";
    document.getElementById('modal-content').innerHTML = content;
    document.getElementById('delete-button').value = content;
}
