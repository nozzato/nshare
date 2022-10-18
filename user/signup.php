<?php
session_start();

// if logged in
if(isset($_SESSION['user'])) {
    header('location:/index');
    exit;
}

$_SESSION['page'] = 'signup';
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>Signup - NShare</title>
<link rel='icon' type='image/gif' href='/assets/favicon.gif'>

<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/include.php'); ?>

</head>
<?php if(!isset($_SESSION['msg'])) { ?>
<body>
<?php } else { ?>
<body onload='notify("<?= $_SESSION['msg'][0]; ?>", <?= $_SESSION['msg'][1]; ?>)'>
<?php unset($_SESSION['msg']); } ?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/header.php'); ?>

<div class='w3-container w3-padding-16 w3-center' id='content'>
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
            <br>
            <div id='h-captcha' class='h-captcha' data-sitekey='fc621593-608b-4635-be8e-9f43bb5d1e46' data-theme='dark'></div>
            <p></p>
            <button class='w3-btn w3-green w3-round' type='submit' name='create_btn'>
                <i class='fa fa-fw fa-user'></i> Signup
            </button>
        </form>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/notification.php'); ?>

</body>
</html>
