function msgClear() {
    setTimeout(() => {
        msg = document.getElementById('msg').innerHTML = '';
    }, 10000);
}

function loginVerify() {
    var msg = document.getElementById('msg');
    var loginUsername = document.getElementById('login-username').value;
    var loginPassword = document.getElementById('login-password').value;

    if(loginUsername === '' || loginPassword === '') {
        msg.innerHTML = 'Error: Both fields are required';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else {
        return true;
    }
}
function createVerify() {
    var msg = document.getElementById('msg');
    var createEmail = document.getElementById('create-email').value;
    var createUsername = document.getElementById('create-username').value;
    var createPassword = document.getElementById('create-password').value;

    if(createUsername === '' || createPassword === '' || createEmail === '') {
        msg.innerHTML = 'Error: All fields are required';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else if(createEmail.length > 255) {
        msg.innerHTML = 'Error: Email must be 255 characters or less';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else if(createUsername.length > 50) {
        msg.innerHTML = 'Error: Username must be 50 characters or less';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else if(createPassword.length < 8) {
        msg.innerHTML = 'Error: Password must be 8 characters or more';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else if(createPassword.length > 72) {
        msg.innerHTML = 'Error: Password must be 72 characters or less';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else {
        return true;
    }
}
function emailVerify() {
    var msg = document.getElementById('msg');
    var oldEmail = document.getElementById('email-old').value;
    var newEmail = document.getElementById('email-new').value;

    if(oldEmail === '' || newEmail === '') {
        msg.innerHTML = 'Error: Both fields are required';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else if(newEmail.length > 255) {
        msg.innerHTML = 'Error: New email must be 255 characters or less';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else {
        return true;
    }
}
function usernameVerify() {
    var msg = document.getElementById('msg');
    var oldUsername = document.getElementById('username-old').value;
    var newUsername = document.getElementById('username-new').value;

    if(oldUsername === '' || newUsername === '') {
        msg.innerHTML = 'Error: Both fields are required';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else if(newUsername.length > 255) {
        msg.innerHTML = 'Error: New username must be 255 characters or less';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else {
        return true;
    }
}
function passwordVerify() {
    var msg = document.getElementById('msg');
    var oldPassword = document.getElementById('password-old').value;
    var newPassword = document.getElementById('password-new').value;

    if(oldPassword === '' || newPassword === '') {
        msg.innerHTML = 'Error: Both fields are required';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else if(newPassword.length < 8) {
        msg.innerHTML = 'Error: New password must be 8 characters or more';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else if(newPassword.length > 72) {
        msg.innerHTML = 'Error: New password must be 72 characters or less';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else {
        return true;
    }
}
function closeVerify() {
    var msg = document.getElementById('msg');
    var closePassword = document.getElementById('close-password').value;

    if(closePassword === '') {
        msg.innerHTML = 'Error: Password is required';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else {
        return true;
    }
}

function dropdownToggle() {
    var dropdown = document.getElementById('dropdown');

    if(dropdown.className.indexOf('w3-show') == -1) {
        dropdown.className += ' w3-show';
    } else {
        dropdown.className = dropdown.className.replace(' w3-show', '');
    }
}
function openModal(content) {
    document.getElementById('modal').style.display = 'block';
    document.getElementById('modal-content').innerHTML = content;
    document.getElementById('delete-button').value = content;
}

function goBack() {
    window.location.href = '/files/index.php';
}

function copy() {
    var userId = document.getElementById('userId').innerHTML

    navigator.clipboard.writeText(userId);
}

function toggleFoxes() {
    var divFoxes = document.getElementById('foxes');

    if (divFoxes.style.display == 'none') {
        divFoxes.style.display = 'block';
    } else {
        divFoxes.style.display = 'none';
    }
}
