<?php
session_start();
include_once('/srv/http/nozzato.com/database/connect.php');

if(!isset($_SESSION['user'])) {
    header('location:/');
    exit;
}
if(!isset($_GET['id'])) {
    header('location:/user/?id=' . $_SESSION['user']);
    exit;
}
if($_GET['id'] == $_SESSION['user']) {
    $user     = $_SESSION['user'];
    $username = $_SESSION['username'];
    $rank     = $_SESSION['rank'];
} else {
    try {
        $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
        $stmt-> execute([$_GET['id']]);
        $row = $stmt-> fetch(PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
    }
    if(empty($row)) {
        $_SESSION['msg'] = 'Error: Invalid user';
        header('location:/user/?id=' . $_SESSION['user']);
        exit;
    }
    $user     = $row['user_id'];
    $username = $row['username'];
    $rank     = $row['rank'];
}
$_SESSION['page'] = 'profile';
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>NozzDesk Server - Profile</title>
<link rel='icon' type='image/gif' href='/media/favicon.gif'>

<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>

<link rel='stylesheet' href='/styles/w3.css'>
<link rel='stylesheet' href='/styles/nz.css'>
<link rel='stylesheet' href='/styles/icons/css/all.css'>

<script src='/scripts/scripts.js' type='text/javascript'></script>

</head>
<body class='nz-dark'>

<div class='nz-black' id='header'>
    <div class='w3-bar'>

        <a class='w3-bar-item w3-button w3-text-blue w3-mobile' href='/index.php'>NozzDesk Server</a>

        <?php if(isset($_SESSION['user'])) { ?>
            <a class='w3-bar-item w3-button' href='/files/index.php'>
                <i class='fa fa-fw fa-folder-open'></i> Files
            </a>
        <?php } ?>

        <?php if(isset($_SESSION['rank']) && $_SESSION['rank'] == 'admin') { ?>
            <a class='w3-bar-item w3-button' href='/database/adminer.php'>
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
                    <i class='fa fa-fw fa-user'></i> <?php echo $_SESSION['username']; ?>
                </a>

                <a class='w3-bar-item w3-button' href='/user/settings.php'>
                    <i class='fa fa-fw fa-gear'></i> Settings
                </a>

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

<div class='w3-container w3-padding-16 nz-page w3-center' id='content' style='margin-bottom:38.5px'>
    <div class='w3-round w3-card-2'>

        <div class='w3-container nz-black nz-round-top'>
            <h2>Profile</h2>
        </div>
        
        <div class='w3-container w3-padding-16 w3-responsive'>
            <table class='w3-table'>
                <tr>
                    <th>Username</th>
                    <th class='w3-tooltip'>
                        User ID
                        <span class='w3-text w3-text-blue w3-tag w3-round w3-margin-left' style='position:absolute'>
                            <a href='javascript:void(0)' onclick='copy()'>Share</a>
                        </span>
                    </th>
                    <th>Rank</th>
                </tr>
                <tr>
                    <td><?php echo $username; ?></td>
                    <td id='userId'><?php echo $user; ?></td>
                    <td><?php echo ucfirst($rank); ?></td>
                </tr>
            </table>
        </div>

    </div>
    <br>
</div>

<div class='nz-black w3-bottom' id='footer'>
    <div class='w3-bar'>

        <a class='w3-bar-item w3-button nz-text-black w3-hover-none' href='javascript:void(0)' onclick='toggleFoxes()'>fox.exe</a>

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
    <div class='w3-container'>
        <div id='foxes' style='display:none'>
            <div class='nz-fox-animate-right nz-ghost'>
                <img src='/media/fox-bounce-right.gif'>
            </div>
            <div class='nz-fox-animate-left nz-ghost'>
                <img src='/media/fox-bounce-left.gif'>
            </div>
        </div>
    </div>

</div>

</body>
</html>
