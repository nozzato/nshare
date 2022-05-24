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
        msgClear();
        return true;
    }
}
function signupVerify() {
    var msg = document.getElementById('msg');
    var signupEmail = document.getElementById('signup-email').value;
    var signupUsername = document.getElementById('signup-username').value;
    var signupPassword = document.getElementById('signup-password').value;

    if(signupUsername === '' || signupPassword === '' || signupEmail === '') {
        msg.innerHTML = 'Error: All fields are required';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else if(signupEmail.length > 255) {
        msg.innerHTML = 'Error: Email must be 255 characters or less';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else if(signupUsername.length > 50) {
        msg.innerHTML = 'Error: Username must be 50 characters or less';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else if(signupPassword.length < 8) {
        msg.innerHTML = 'Error: Password must be 8 characters or more';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else if(signupPassword.length > 72) {
        msg.innerHTML = 'Error: Password must be 72 characters or less';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else {
        msgClear();
        return true;
    }
}
function emailVerify() {
    var msg = document.getElementById('msg');
    var oldEmail = document.getElementById('old-email').value;
    var newEmail = document.getElementById('new-email').value;

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
        msgClear();
        return true;
    }
}
function passwordVerify() {
    var msg = document.getElementById('msg');
    var oldPassword = document.getElementById('old-password').value;
    var newPassword = document.getElementById('new-password').value;

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
        msgClear();
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
        msgClear();
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
