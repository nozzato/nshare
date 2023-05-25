<?php
session_start();

// if banned
if(isset($_SESSION['ban_status']) && $_SESSION['ban_status'] >= 1) {
    header('location:/status/banned.php');
    exit;
}

// include functions
include_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/scripts.php');

// connect to database
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/connect.php');

$_SESSION['page'] = 'home';
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>

<?php if(!isset($_SESSION['user'])) { ?>
    Welcome - NShare
<?php } else { ?>
    Dashboard - NShare
<?php } ?>

</title>
<link rel='icon' type='image/gif' href='/assets/favicon.ico'>

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

<?php if(!isset($_SESSION['user'])) { ?>
    <h1><b>Welcome to NShare</b></h1>
    <h2>A file sharing and editing website</h2>
    <br>
    <p>Don't have an account? <a class='w3-text-blue' href='/user/signup.php'>Sign up now!</a></p>
<?php } else {
    $stmt = $pdo-> prepare('SELECT * FROM `files` WHERE `user_id` = ?;');
    $stmt-> execute([$_SESSION['user']]);
    $rows = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    $count = $stmt-> rowCount();
?>
    <h1><b>Welcome, <?= $_SESSION['username']; ?></b></h1>
    <p></p>
<?php
    $db_file_size_total = 0;

    for($i = 0; $i <= $count - 1; $i++) {
        $db_file_size_total += $rows[$i]['size'];
    }
    $db_file_size_left = 5368709120 - $db_file_size_total;
?>
    <span>Used Storage: <?= human_filesize($db_file_size_total); ?> / 5.00G</span>
    <br>
    <span>Available Storage: <?= human_filesize($db_file_size_left); ?></span>
<?php } ?>

</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/notification.html'); ?>

</body>
</html>
