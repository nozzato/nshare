<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(!isset($_SESSION['user'])) {
    go_back();
}
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
<link rel='stylesheet' href='/styles/icons/css/all.css'>

</head>
<body class='nz-dark'>

<div id='header'>
    <div class='w3-bar nz-black'>

        <?php if(!isset($_SESSION['user'])) { ?>
            <a class='w3-bar-item w3-button w3-text-blue' href='/index.php'>NShare</a>
        <?php } else { ?>
            <a class='w3-bar-item w3-button w3-text-blue w3-mobile' href='/index.php'>NShare</a>
        <?php } ?>
        <?php if(isset($_SESSION['user'])) { ?>
            <a class='w3-bar-item w3-button' href='/files/index.php'>
                <i class='fa fa-fw fa-folder-open'></i> Files
            </a>
        <?php } ?>

        <?php if(isset($_SESSION['rank']) && $_SESSION['rank'] == 'admin') { ?>
            <a class='w3-bar-item w3-button' href='/database/adminer/adminer.php'>
                <i class='fa fa-fw fa-server'></i> Admin
            </a>
        <?php }

        if(!isset($_SESSION['user'])) { ?>
        <div class='w3-dropdown-click w3-right'>
            <button class='w3-button' onclick='dropdownToggle()'>
                <i class='fa fa-fw fa-door-open'></i> Login <i class='fa fa-fw fa-caret-down'></i>
            </button>
            <div class='w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2 nz-dropdown-left' id='dropdown' style='max-width:100px'>

                <form class='w3-right' action='/user/login.php' method='POST' onsubmit='return loginVerify(this)'>

                    <div style='display:flex'>
                        <i class='fa fa-fw fa-user' style='margin:8px 2px 8px 18px;padding:4px 0.93px 0 0.93px;vertical-align:center'></i>
                        <input class='w3-bar-item w3-input nz-black' id='login-username' type='text' placeholder='Username' name='login_username' style='padding:8px 16px 8px 5px'>
                    </div>

                    <div style='display:flex'>
                        <i class='fa fa-fw fa-key' style='margin:8px 2px 8px 18px;padding-top:4px;vertical-align:center'></i>
                        <input class='w3-bar-item w3-input nz-black' id='login-password' type='password' placeholder='Password' name='login_password' style='padding:8px 16px 8px 5px'>
                    </div>

                    <button class='w3-bar-item w3-button w3-green nz-round-bottom-left' type='submit' name='login_btn'>
                        <i class='fa fa-fw fa-right-to-bracket'></i> Login
                    </button>

                </form>
            </div>
        </div>
        <?php } else { ?>
        <div class='w3-dropdown-hover w3-right'>
            <button class='w3-button'>
                <i class='fa fa-fw fa-door-closed'></i> Account <i class='fa fa-fw fa-caret-down'></i>
            </button>
            <div class='w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2 nz-dropdown-left' style='max-width:100px'>

                <a class='w3-bar-item w3-button' href='/user/index.php?id=<?php echo $_SESSION['user']; ?>'>
                    <i class='fa fa-fw fa-user'></i> <?php echo $_SESSION['username']; ?><span class='w3-text-gray'>#<?php echo $_SESSION['user']; ?></span>
                </a>

                <?php if(!$_SESSION['ban_status'] >= 1) { ?>
                    <a class='w3-bar-item w3-button' href='/user/settings.php'>
                        <i class='fa fa-fw fa-gear'></i> Settings
                    </a>
                <?php } ?>

                <form action='/user/logout.php' method='POST'>
                    <button class='w3-bar-item w3-button w3-red nz-round-bottom-left' type='submit' name='logout_btn'>
                        <i class='fa fa-fw fa-right-from-bracket'></i> Logout
                    </button>
                </form>

            </div>
        </div>
        <?php } ?>
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
