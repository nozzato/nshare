// database
function changePrivacy(fileID) {
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if(state == 'Private') {
                document.getElementById(fileID).innerHTML = 'Public';
            } else if(state == 'Public') {
                document.getElementById(fileID).innerHTML = 'Private';
            }
        }
    };

    var state = document.getElementById(fileID).innerHTML;

    xmlhttp.open('GET', 'privacy.php?fileid=' + fileID + '&state=' + state);
    xmlhttp.send();
}
function addFriend(friendID) {
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if(state.includes('Not Friends')) {
                document.getElementById(friendID).innerHTML = 'Friends';
                document.getElementById(friendID).classList.remove('w3-button', 'w3-hover-green', 'add-friend-button');
            }
        }
    };

    var state = document.getElementById(friendID).innerHTML

    xmlhttp.open('GET', 'friend.php?friendid=' + friendID);
    xmlhttp.send();
}

// document
function favicon(asset) {
    var link = document.querySelector("link[rel*='icon']") || document.createElement('link');
    link.type = 'image/x-icon';
    link.rel  = 'shortcut icon';
    link.href = '/assets/' + asset;
    document.getElementsByTagName('head')[0].appendChild(link);
}
function copy() {
    var userId = document.getElementById('userId').innerHTML

    navigator.clipboard.writeText(userId);
}

// UI elements
function msgClear() {
    setTimeout(() => {
        msg = document.getElementById('msg').innerHTML = '';
    }, 10000);
}
function dropdownToggle() {
    var dropdown = document.getElementById('dropdown');

    if(dropdown.className.indexOf('w3-show') == -1) {
        dropdown.className += ' w3-show';
    } else {
        dropdown.className = dropdown.className.replace(' w3-show', '');
    }
}
function openModal(type, content) {
    if(type == 'upload') {
        document.getElementById('upload-modal').style.display = 'block';
    } else if(type == 'delete') {
        document.getElementById('delete-modal').style.display = 'block';
        document.getElementById('delete-modal-content').innerHTML = content;
        document.getElementById('delete-button').value = content;
    }
}
function openModalDeleteSel() {
    var array = [];
    var checkboxes = document.querySelectorAll('input[type=checkbox]:checked:not(#check-master)');

    for(var i = 0; i < checkboxes.length; i++) {
        array.push(checkboxes[i].value);
    }

    if(array.length == 0) {
        return;
    } else if(array.length == 1) {
        document.getElementById('delete-modal').style.display = 'block';
        fileName = document.getElementById('file-' + checkboxes[0].value).innerHTML;
        document.getElementById('delete-modal-content').innerHTML = fileName;
    } else {
        document.getElementById('delete-modal').style.display = 'block';
        document.getElementById('delete-modal-content').innerHTML = array.length + ' files';
    }
}
function openPage(page, parentPage) {
    var pages = document.getElementsByClassName('page');
    var buttons = document.getElementsByClassName('page-button');

    for (var i = 0; i < pages.length; i++) {
        pages[i].style.display = 'none';
        buttons[i].classList.remove('w3-dark-gray');
    }
    document.getElementById(page).style.display = 'block';
    document.getElementById(page + 'Btn').classList.add('w3-dark-gray');

    page = page.charAt(0).toUpperCase() + page.slice(1);
    parentPage = parentPage.charAt(0).toUpperCase() + parentPage.slice(1);
    document.title = parentPage + ': ' + page + ' - NShare';
}
function goBack() {
    window.location.href = '/files/index';
}
function selectAll() {
    var checkMaster = document.getElementById('check-master');
    var checkboxes = document.querySelectorAll('input[type=checkbox]:not(#check-master)');
    var checkboxesChecked = document.querySelectorAll('input[type=checkbox]:checked:not(#check-master)');

    if(checkMaster.checked == false && checkboxesChecked.length == checkboxes.length - 1) {
        return;
    }

    if(checkMaster.checked == true) {
        for (var i = 0; i < checkboxes.length; i++) {
            if(checkboxes[i].checked == false) {
                checkboxes[i].click();
            }
        }
    } else {
        for (var i = 0; i < checkboxes.length; i++) {
            if(checkboxes[i].checked == true) {
                checkboxes[i].click();
            }
        }
    }
}
function checkSelectAll() {
    var checkMaster = document.getElementById('check-master');
    var checkboxes = document.querySelectorAll('input[type=checkbox]:not(#check-master)');
    var checkboxesChecked = document.querySelectorAll('input[type=checkbox]:checked:not(#check-master)');

    if(checkMaster.checked == false && checkboxesChecked.length == checkboxes.length) {
        if(checkMaster.checked == false) {
            checkMaster.click();
        }
    } else if(checkboxesChecked.length < checkboxes.length && checkMaster.checked == true) {
        if(checkMaster.checked == true) {
            checkMaster.click();
        }
    }
}

// validation
function loginValidate() {
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
function createValidate() {
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
function emailValidate() {
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
function usernameValidate() {
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
function passwordValidate() {
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
function closeValidate() {
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
function banValidate() {
    var msg = document.getElementById('msg');
    var banUser = document.getElementById('ban-user').value;
    var banReason = document.getElementById('ban-reason').value;

    if(banUser === '' || banReason === '') {
        msg.innerHTML = 'Error: Both fields are required';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else if(banReason.length > 255) {
        msg.innerHTML = 'Error: Ban reason must be 255 characters or less';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else {
        return true;
    }
}
function unbanValidate() {
    var msg = document.getElementById('msg');
    var unbanUser = document.getElementById('unban-user').value;
    var unbanReason = document.getElementById('unban-reason').value;

    if(unbanUser === '' || unbanReason === '') {
        msg.innerHTML = 'Error: Both fields are required';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else if(unbanReason.length > 255) {
        msg.innerHTML = 'Error: Unban reason must be 255 characters or less';
        msg.classList.add('w3-text-red');
        msgClear();
        return false;
    } else {
        return true;
    }
}