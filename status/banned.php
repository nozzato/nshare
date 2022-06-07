<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(!isset($_SESSION['user'])) {
    go_back();
}
try {
    $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
    $stmt-> execute([$_SESSION['user']]);
    $row = $stmt-> fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo-> prepare('SELECT `username` FROM `users` WHERE `user_id` = ?;');
    $stmt-> execute([$row['ban_judge']]);
    $ban_judge = $stmt-> fetchColumn();

    $stmt = $pdo-> prepare('SELECT DATE_FORMAT(`ban_date`, "%d.%m.%Y") FROM `users` WHERE `user_id` = ?;');
    $stmt-> execute([$_SESSION['user']]);
    $row['ban_date'] = $stmt-> fetchColumn();

    $stmt = $pdo-> prepare('SELECT `ban_date` INTO @ban_date FROM `users` WHERE `user_id` = ?;');
    $stmt-> execute([$_SESSION['user']]);

    $stmt = $pdo-> prepare('SELECT DATE_FORMAT(DATE_ADD(@ban_date, INTERVAL 7 DAY), "%D of %M");');
    $stmt-> execute();
    $ban_date_deadline = $stmt-> fetchColumn();
} catch (\PDOException $e) {
    throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
}
if($row['ban_status'] == 0) {
    go_back();
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>Account Banned - NShare</title>
<link rel='icon' type='image/gif' href='/media/favicon.gif'>

<meta charset='utf-8'>
<meta name="viewport" content='width=device-width, initial-scale=1'>

<link rel="stylesheet" href='/styles/w3.css'>
<link rel='stylesheet' href='/styles/nz.css'>

</head>
<body class='nz-dark'>

<div id='header'>
    <div class='w3-bar nz-black'>

        <a class='w3-bar-item w3-button w3-text-blue' href='/status/banned.php'>NShare</a>

        <form action='/user/logout.php' method='POST'>
            <button class='w3-bar-item w3-button w3-text-red w3-right' type='submit' name='logout_btn'>
                <i class='fa fa-fw fa-right-from-bracket'></i> Logout
            </button>
        </form>

    </div>
</div>

<div class='w3-container w3-padding-16' style='margin-bottom:38.5px'>
    <div class='w3-center'>

        <h1 class='w3-text-red'><b>Account Banned</b></h1>

        <p>We have found your account in violation of our rules.</p>

        <p>Your files will be deleted automatically on the <b><?php echo $ban_date_deadline; ?></b>.<br><a class='w3-text-blue' href='/files/index.php'>Click here</a> to download your files before they are deleted.</p>

        <span>Username:</span>
        <span><?php echo $_SESSION['username']; ?><span class='w3-text-gray' title='User ID'>#<?php echo $_SESSION['user']; ?></span></span>

        <br>
        <span>Banned By:</span>
        <span><?php echo $ban_judge; ?><span class='w3-text-gray' title='User ID'>#<?php echo $row['ban_judge']; ?></span></span>

        <br>
        <span>Ban Date:</span>
        <span><?php echo $row['ban_date']; ?></span>

        <br>
        <span>Ban Reason:</span>
        <span><?php echo $row['ban_reason']; ?></span>
    </div>
</div>

<div class='w3-bottom' id='footer'>
    <div class='w3-bar nz-black' style='height:38.5px'>
        <div class='w3-display-bottommiddle' style='bottom:9px'>
            <?php if(isset($_SESSION['msg'])) {
                if(substr($_SESSION['msg'], 0, 6) == 'Error:') { ?>

                <span class='w3-text-red nz-truncate' id='msg'>
                    <?php echo $_SESSION['msg']; ?>
                </span>

                <?php } else { ?>
                <span class='nz-truncate' id='msg'>
                    <?php echo $_SESSION['msg']; ?>
                </span>

                <?php }
            } else { ?>
                <span class='nz-truncate' id='msg'></span>
            <?php }

            unset($_SESSION['msg']); ?>
        </div>
    </div>
</div>

</body>
</html>
