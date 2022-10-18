<?php
session_start();

// include functions
include_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/scripts.php');

// if logged in
if(!isset($_SESSION['user'])) {
    go_back();
}

// connect to database
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/connect.php');

// select user data
$stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
$stmt-> execute([$_SESSION['user']]);
$row = $stmt-> fetch(PDO::FETCH_ASSOC);

// select username of judge
$stmt = $pdo-> prepare('SELECT `username` FROM `users` WHERE `user_id` = ?;');
$stmt-> execute([$row['ban_judge']]);
$ban_judge = $stmt-> fetchColumn();

// select formatted ban date
$stmt = $pdo-> prepare('SELECT DATE_FORMAT(`ban_date`, "%d-%m-%Y") FROM `users` WHERE `user_id` = ?;');
$stmt-> execute([$_SESSION['user']]);
$row['ban_date'] = $stmt-> fetchColumn();

// set ban date variable
$stmt = $pdo-> prepare('SELECT `ban_date` INTO @ban_date FROM `users` WHERE `user_id` = ?;');
$stmt-> execute([$_SESSION['user']]);

// select formatted ban date deadline
$stmt = $pdo-> prepare('SELECT DATE_FORMAT(DATE_ADD(@ban_date, INTERVAL 7 DAY), "%D of %M");');
$stmt-> execute();
$ban_date_deadline = $stmt-> fetchColumn();

// if not banned
if($row['ban_status'] == 0) {
    go_back();
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>Account Banned - NShare</title>
<link rel='icon' type='image/gif' href='/assets/favicon.gif'>

<meta charset='utf-8'>
<meta name="viewport" content='width=device-width, initial-scale=1'>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/include.php'); ?>

</head>
<?php if(!isset($_SESSION['msg'])) { ?>
<body>
<?php } else { ?>
<body onload='notify("<?= $_SESSION['msg'][0]; ?>", <?= $_SESSION['msg'][1]; ?>)'>
<?php unset($_SESSION['msg']); } ?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/header.php'); ?>

<div class='w3-container w3-padding-16'>
    <div class='w3-center'>
        <h1 class='w3-text-red'><b>Account Banned</b></h1>
        <p>We have found your account in violation of our rules.</p>
        <p>Your files will be deleted automatically on the <b><?= $ban_date_deadline; ?></b>.<br><a class='w3-text-blue' href='/files/index'>Click here</a> to download your files before they are deleted.</p>
        <span>Username:</span>
        <a class='w3-text-light-blue' href='/user/index?id=<?= $_SESSION['user']; ?>'><?= $_SESSION['username']; ?></a>
        <br>
        <span>Banned By:</span>
        <a class='w3-text-light-blue' href='/user/index?id=<?= $row['ban_judge']; ?>'><?= $ban_judge; ?></a>
        <br>
        <span>Ban Date:</span>
        <span><?= $row['ban_date']; ?></span>
        <br>
        <span>Ban Reason:</span>
        <span><?= $row['ban_reason']; ?></span>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/notification.php'); ?>

</body>
</html>
