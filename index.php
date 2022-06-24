<?php
session_start();

// if banned
if(isset($_SESSION['ban_status']) && $_SESSION['ban_status'] >= 1) {
    header('location:/status/banned.php');
    exit;
}

// include functions
include_once('/srv/http/nozzato.com/scripts/scripts.php');

// connect to database
include_once('/srv/http/nozzato.com/admin/connect.php');

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
<link rel='icon' type='image/gif' href='/assets/favicon.gif'>

<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>

<link rel='stylesheet' href='/styles/w3.css'>
<link rel='stylesheet' href='/styles/nz.css'>
<link rel='stylesheet' href='/styles/icons/css/all.css'>

<script src='/scripts/scripts.js' type='text/javascript'></script>

</head>
<body>

<div id='header'>
    <div class='w3-bar'>

    <?php if(!isset($_SESSION['user'])) { ?>
        <a class='w3-bar-item w3-button nz-brand' href='/index.php'>NShare</a>
    <?php } else { ?>
        <a class='w3-bar-item w3-button nz-brand w3-mobile' href='/index.php'>NShare</a>
    <?php } ?>

    <?php if(isset($_SESSION['user'])) { ?>
        <a class='w3-bar-item w3-button' href='/files/index.php'>
            <i class='fa fa-fw fa-folder-open'></i> Files
        </a>
    <?php } ?>

    <?php if(isset($_SESSION['rank']) && $_SESSION['rank'] == 'admin') { ?>
        <a class='w3-bar-item w3-button' href='/admin/index.php'>
            <i class='fa fa-fw fa-server'></i> Admin
        </a>
    <?php } ?>

    <?php if(!isset($_SESSION['user'])) { ?>
        <div class='w3-dropdown-click w3-right'>
            <button class='w3-button' onclick='dropdownToggle()'>
                <i class='fa fa-fw fa-door-open'></i> Login <i class='fa fa-fw fa-caret-down'></i>
            </button>
            <div class='w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2 nz-dropdown-left' id='dropdown' style='max-width:100px'>
                <form class='w3-right' action='/user/login.php' method='POST' onsubmit='return loginValidate(this)'>
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
                <a class='w3-bar-item w3-button' href='/user/index.php?id=<?= $_SESSION['user']; ?>'>
                    <i class='fa fa-fw fa-user'></i> <?= $_SESSION['username']; ?>
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

<div class='w3-container w3-padding-16 w3-center' id='content' style='margin-bottom:38.5px'>

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

<div class='w3-bottom' id='footer'>
    <div class='w3-bar' style='height:38.5px'>
        <div class='w3-display-bottommiddle' style='bottom:9px'>

    <?php if(isset($_SESSION['msg'])) {
        if(substr($_SESSION['msg'], 0, 6) == 'Error:') { ?>
            <span class='w3-text-red nz-truncate' id='msg'>
                <?= $_SESSION['msg']; ?>
            </span>
        <?php } else { ?>
            <span class='nz-truncate' id='msg'>
                <?= $_SESSION['msg']; ?>
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