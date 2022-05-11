function clearMsg() {
    setTimeout(() => {
        var msg = document.getElementById("msg");
        msg.innerHTML = "";
    }, 10000);
}
function verifyAccount() {
    // If uncommented, bypass login restrictions
    //return true;

    var msg = document.getElementById("msg");
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    if(username === "" || password === "") {
        msg.innerHTML = "Error: Both fields are required";
        msg.className = "w3-text-red";
        clearMsg();
        return false;
    } else if(password.length < 8) {
        msg.innerHTML = "Error: Password must be 8 characters or more";
        msg.className = "w3-text-red";
        clearMsg();
        return false;
    } else if(username.length > 50) {
        msg.innerHTML = "Error: Username must be 50 characters or less";
        msg.className = "w3-text-red";
        clearMsg();
        return false;
    } else if(password.length > 72) {
        msg.innerHTML = "Error: Password must be 72 characters or less";
        msg.className = "w3-text-red";
        clearMsg();
        return false;
    } else {
        return true;
    }
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
