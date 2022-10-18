<?php
session_start();

// if not logged in
if(!isset($_SESSION['user'])) {
    header('location:/index');
    exit;
}

// if no GET id
if(!isset($_GET['id'])) {
    header('location:/user/index?id=' . $_SESSION['user']);
    exit;
}

// connect to database
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/connect.php');

// select user data from GET id
$stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
$stmt-> execute([$_GET['id']]);
$row = $stmt-> fetch(PDO::FETCH_ASSOC);

// if GET id matches user
if($_GET['id'] == $_SESSION['user']) {
    // set user variables from session
    $user       = $_SESSION['user'];
    $username   = $_SESSION['username'];
    $role       = $_SESSION['role'];

    if($_SESSION['ban_status'] == 0) {
        $ban_status = 'Unbanned';
    } else {
        $ban_status = 'Banned';
    }
// else GET id does not match user
} else {
    // if user does not exist
    if(empty($row)) {
        $_SESSION['msg'] = ['Error: Invalid user', 'true'];
        header('location:/user/index?id=' . $_SESSION['user']);
        exit;
    }

    // set user variables from database
    $user     = $row['user_id'];
    $username = $row['username'];
    $role     = $row['role'];

    if($row['ban_status'] == 0) {
        $ban_status = 'Unbanned';
    } else {
        $ban_status = 'Banned';
    }
}

$_SESSION['page'] = 'profile';
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>Profile: <?= $username; ?> - NShare</title>
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
            <h2>Profile</h2>
        </div>
        <div class='w3-container w3-padding-16 w3-responsive'>
            <table class='nz-table'>
                <tr>
                    <td><b>Username</b><br><?= $username; ?></td>
                    <td class='nz-truncate'><b>User ID</b><br><?= $user; ?></td>

                <?php if($_SESSION['role'] == 'admin') { ?>
                    <td><b>Role</b><br><?= ucfirst($role); ?></td>
                    <td class='nz-truncate'><b>Ban Status</b><br><?= $ban_status; ?></td>
                <?php } else if($role == 'admin') { ?>
                    <td><b>Role</b><br><?= ucfirst($role); ?></td>
                <?php } else if($row['ban_status'] >= 1) { ?>
                    <td><b>Ban Status</b><br><?= $ban_status; ?></td>
                <?php } ?>

                </tr>
            </table>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/notification.php'); ?>

</body>
</html>
