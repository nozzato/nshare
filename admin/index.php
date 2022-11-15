<?php
session_start();

// if not logged in
if(!isset($_SESSION['user'])) {
    header('location:/index');
    exit;
}
// if not admin
if($_SESSION['role'] == 0) {
    header('location:/index');
    exit;
}
// if banned
if($_SESSION['ban_status'] >= 1) {
    header('location:/status/banned');
    exit;
}

$_SESSION['page'] = 2;
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>Admin: Account - NShare</title>
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
        <button class='w3-bar-item w3-button page-button w3-dark-gray' id='accountBtn' onclick='openPage("account", "admin")' style='width:100px'>Account</button>
        <a class='w3-bar-item w3-button page-button' id='databaseBtn' href='/vendor/vrana/adminer/adminer/index' style='width:100px'>Database</a>
    </div>
    <div class='page' id='account'>
        <div class='w3-round nz-page w3-card-2'>
            <div class='w3-container nz-black nz-round-top'>
                <h2>Ban Account</h2>
            </div>
            <form class='w3-container w3-padding-16' action='/user/ban.php' method='POST' onsubmit='return banValidate(this)'>
                <input class='w3-input nz-black w3-border-0 w3-round' id='ban-user' type='text' placeholder='User ID' name='ban_user'>
                <p></p>
                <p></p>
                <input class='w3-input nz-black w3-border-0 w3-round' id='ban-reason' type='text' placeholder='Ban Reason' name='ban_reason'>
                <p></p>
                <button class='w3-btn w3-red w3-round' type='submit' name='ban_btn'>
                    <i class='fa fa-fw fa-gavel'></i> Ban
                </button>
            </form>
        </div>
        <br>
        <div class='w3-round nz-page w3-card-2'>
            <div class='w3-container nz-black nz-round-top'>
                <h2>Unban Account</h2>
            </div>
            <form class='w3-container w3-padding-16' action='/user/unban.php' method='POST' onsubmit='return unbanValidate(this)'>
                <input class='w3-input nz-black w3-border-0 w3-round' id='unban-user' type='text' placeholder='User ID' name='unban_user'>
                <p></p>
                <p></p>
                <input class='w3-input nz-black w3-border-0 w3-round' id='unban-reason' type='text' placeholder='Unban Reason' name='unban_reason'>
                <p></p>
                <button class='w3-btn w3-green w3-round' type='submit' name='unban_btn'>
                    <i class='fa fa-fw fa-scale-unbalanced'></i> Unban
                </button>
            </form>
        </div>
        <br>
        <div class='w3-round w3-card-2 nz-page'>
            <div class='w3-container nz-black nz-round-top'>
                <h2>Create Account</h2>
            </div>
            <form class='w3-container w3-padding-16' action='/user/create.php' method='POST' onsubmit='return createValidate(this)'>
                <input class='w3-input nz-black w3-border-0 w3-round' id='create-email' type='text' placeholder='Email' name='create_email'>
                <p></p>
                <input class='w3-input nz-black w3-border-0 w3-round' id='create-username' type='text' placeholder='Username' name='create_username'>
                <p></p>
                <input class='w3-input nz-black w3-border-0 w3-round' id='create-password' type='password' placeholder='Password' name='create_password'>
                <p></p>
                <span>Role</span>
                <input class='w3-radio' id='create-member' type='radio' value=0 name='create_role' checked>
                <label for='create-member'>Member</label>
                <input class='w3-radio' id='create-admin' type='radio' value=2 name='create_role'>
                <label for='create-admin'>Admin</label>
                <br><br>
                <button class='w3-btn w3-green w3-round' type='submit' name='create_btn'>
                    <i class='fa fa-fw fa-user'></i> Create
                </button>
            </form>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/notification.html'); ?>

</body>
</html>
