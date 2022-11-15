<?php
session_start();

// if not logged in
if(!isset($_SESSION['user'])) {
    header('location:/index');
    exit;
}
// if banned
if($_SESSION['ban_status'] >= 1) {
    header('location:/status/banned');
    exit;
}

$_SESSION['page'] = 'settings';
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>Settings: Account - NShare</title>
<link rel='icon' type='image/gif' href='/assets/favicon.gif'>

<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/include.html'); ?>

</head>
<?php if(!isset($_SESSION['msg'])) { ?>
<body>
<?php } else { ?>
<body onload='notify("<?= $_SESSION['msg'][0]; ?>", <?= $_SESSION['msg'][1]; ?>)'>
<?php unset($_SESSION['msg']); } ?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/header.php'); ?>

<div class='w3-container w3-padding-16 w3-center' id='content'>
    <div class='w3-bar nz-black w3-round nz-page' style='margin-bottom:10px'>
        <button class='w3-bar-item w3-button page-button w3-dark-gray' id='accountBtn' onclick='openPage("account", "settings")' style='width:100px'>Account</button>
        <button class='w3-bar-item w3-button page-button' id='filesBtn' onclick='openPage("files", "settings")' style='width:100px'>Files</button>
    </div>
    <div class='page' id='account'>
        <div class='w3-round w3-card-2 nz-page'>
            <div class='w3-container nz-black nz-round-top'>
                <h2>Change Email</h2>
            </div>
            <form class='w3-container w3-padding-16' action='/user/email.php' method='POST' onsubmit='return emailValidate(this)'>

            <?php if($_SESSION['role'] == 2) { ?>
                <input class='w3-input nz-black w3-border-0 w3-round' type='text' placeholder='User ID (optional)' name='email_user'>
                <p></p>
            <?php } ?>

                <input class='w3-input nz-black w3-border-0 w3-round' id='email-old' type='text' placeholder='Old Email' name='email_old'>
                <p></p>
                <input class='w3-input nz-black w3-border-0 w3-round' id='email-new' type='text' placeholder='New Email' name='email_new'>
                <p></p>
                <button class='w3-btn w3-green w3-round' type='submit' name='email_btn'>
                    <i class='fa fa-fw fa-envelope'></i> Change
                </button>
            </form>
        </div>
        <br>
        <div class='w3-round w3-card-2 nz-page'>
            <div class='w3-container nz-black nz-round-top'>
                <h2>Change Username</h2>
            </div>
            <form class='w3-container w3-padding-16' action='/user/username.php' method='POST' onsubmit='return usernameValidate(this)'>

            <?php if($_SESSION['role'] == 2) { ?>
                <input class='w3-input nz-black w3-border-0 w3-round' type='text' placeholder='User ID (optional)' name='username_user'>
                <p></p>
            <?php } ?>

                <input class='w3-input nz-black w3-border-0 w3-round' id='username-old' type='text' placeholder='Old Username' name='username_old'>
                <p></p>
                <input class='w3-input nz-black w3-border-0 w3-round' id='username-new' type='text' placeholder='New Username' name='username_new'>
                <p></p>
                <button class='w3-btn w3-green w3-round' type='submit' name='username_btn'>
                    <i class='fa fa-fw fa-tag'></i> Change
                </button>
            </form>
        </div>
        <br>
        <div class='w3-round w3-card-2 nz-page'>
            <div class='w3-container nz-black nz-round-top'>
                <h2>Change Password</h2>
            </div>
            <form class='w3-container w3-padding-16' action='/user/password.php' method='POST' onsubmit='return passwordValidate(this)'>

            <?php if($_SESSION['role'] == 2) { ?>
                <input class='w3-input nz-black w3-border-0 w3-round' type='text' placeholder='User ID (optional)' name='password_user'>
                <p></p>
            <?php } ?>

                <input class='w3-input nz-black w3-border-0 w3-round' id='password-old' type='password' placeholder='Old Password' name='password_old'>
                <p></p>
                <input class='w3-input nz-black w3-border-0 w3-round' id='password-new' type='password' placeholder='New Password' name='password_new'>
                <p></p>
                <button class='w3-btn w3-green w3-round' type='submit' name='password_btn'>
                    <i class='fa fa-fw fa-key'></i> Change
                </button>
            </form>
        </div>
        <br>
        <div class='w3-round w3-card-2 nz-page'>
            <div class='w3-container nz-black nz-round-top'>
                <h2>Close Account</h2>
            </div>
            <form class='w3-container w3-padding-16' action='/user/close.php' method='POST' onsubmit='return closeValidate(this)'>

            <?php if($_SESSION['role'] == 2) { ?>
                <input class='w3-input nz-black w3-border-0 w3-round' type='text' placeholder='User ID (optional)' name='close_user'>
                <p></p>
            <?php } ?>

                <input class='w3-input nz-black w3-border-0 w3-round' id='close-password' type='password' placeholder='Password' name='close_password'>
                <p></p>
                <button class='w3-btn w3-red w3-round' type='submit' name='close_btn'>
                    <i class='fa fa-fw fa-user-slash'></i> Close
                </button>
            </form>
        </div>
    </div>
    <div class='page' id='files' style='display:none'>
        <div class='w3-round w3-card-2 nz-page'>
            <div class='w3-container nz-black nz-round-top'>
                <h2>Title</h2>
            </div>
            <div class='w3-padding-16'>
                <span>Content</span>
            </div>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/notification.html'); ?>

</body>
</html>
