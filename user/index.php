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
    $rank       = $_SESSION['rank'];

    if($_SESSION['ban_status'] == 0) {
        $ban_status = 'Unbanned';
    } else {
        $ban_status = 'Banned';
    }
// else GET id does not match user
} else {
    // if user does not exist
    if(empty($row)) {
        $_SESSION['msg'] = 'Error: Invalid user';
        $_SESSION['msg_urgent'] = 'true';
        header('location:/user/index?id=' . $_SESSION['user']);
        exit;
    }

    // set user variables from database
    $user     = $row['user_id'];
    $username = $row['username'];
    $rank     = $row['rank'];

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

<link rel='stylesheet' href='/styles/w3.css'>
<link rel='stylesheet' href='/styles/nz.css'>
<link rel='stylesheet' href='/styles/icons/css/all.css'>

<script src='/scripts/scripts.js' type='text/javascript'></script>

</head>
<?php if(!isset($_SESSION['msg'])) { ?>
<body>
<?php } else { ?>
<body onload='notify("<?= $_SESSION['msg']; ?>", <?= $_SESSION['msg_urgent']; ?>)'>
<?php unset($_SESSION['msg']); } ?>

<div id='header'>
    <div class='w3-bar'>

    <?php if(!isset($_SESSION['user'])) { ?>
        <a class='w3-bar-item w3-button nz-brand' href='/index'>NShare</a>
    <?php } else { ?>
        <a class='w3-bar-item w3-button nz-brand w3-mobile' href='/index'>NShare</a>
    <?php } ?>

    <?php if(isset($_SESSION['user'])) { ?>
        <a class='w3-bar-item w3-button' href='/files/index'>
            <i class='fa fa-fw fa-folder-open'></i> Files
        </a>
        <a class='w3-bar-item w3-button' href='/user/users'>
            <i class='fa fa-fw fa-user'></i> Users
        </a>
    <?php } ?>

    <?php if(isset($_SESSION['rank']) && $_SESSION['rank'] == 'admin') { ?>
        <a class='w3-bar-item w3-button' href='/admin/index'>
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
                <a class='w3-bar-item w3-button' href='/user/index?id=<?= $_SESSION['user']; ?>'>
                    <i class='fa fa-fw fa-user'></i> <?= $_SESSION['username']; ?>
                </a>

            <?php if(!$_SESSION['ban_status'] >= 1) { ?>
                <a class='w3-bar-item w3-button' href='/user/settings'>
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
    <div class='w3-round w3-card-2 nz-page'>
        <div class='w3-container nz-black nz-round-top'>
            <h2>Profile</h2>
        </div>
        <div class='w3-container w3-padding-16 w3-responsive'>
            <table class='nz-table'>
                <tr>
                    <td><b>Username</b><br><?= $username; ?></td>
                    <td class='nz-truncate'><b>User ID</b><br><?= $user; ?></td>

                <?php if($_SESSION['rank'] == 'admin') { ?>
                    <td><b>Rank</b><br><?= ucfirst($rank); ?></td>
                    <td class='nz-truncate'><b>Ban Status</b><br><?= $ban_status; ?></td>
                <?php } else if($rank == 'admin') { ?>
                    <td><b>Rank</b><br><?= ucfirst($rank); ?></td>
                <?php } else if($row['ban_status'] >= 1) { ?>
                    <td><b>Ban Status</b><br><?= $ban_status; ?></td>
                <?php } ?>

                </tr>
            </table>
        </div>
    </div>
</div>

<div class='w3-hide w3-center nz-dark w3-border w3-border-green w3-round w3-card-2' style='position:fixed;bottom:16px;right:16px;min-width:200px' id='msg'>
    <div class='w3-container'>
        <h3 id='msg-text'></h3>
    </div>
    <div class='w3-green' id='msg-progress-bar' style='height:4px;width:100%'></div>
</div>

</body>
</html>
