<?php
session_start();

// if not logged in
if(!isset($_SESSION['user'])) {
    header('location:/index');
    exit;
}
// if banned
if($_SESSION['ban_status'] >= 1) {
    header('location:/status/banned');
    exit;
}

// connect to database
include_once('/srv/http/nozzato.com/admin/connect.php');

// select users and order alphabetically
$stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` != ? ORDER BY `username` ASC;');
$stmt-> execute([$_SESSION['user']]);
$rows = $stmt-> fetchAll(PDO::FETCH_ASSOC);
$count = $stmt-> rowCount();

// select friends array
$stmt = $pdo-> prepare('SELECT `friends` INTO @friends FROM `friends` WHERE `user_id` = ?;');
$stmt-> execute([$_SESSION['user']]);

$stmt = $pdo-> prepare('SELECT @friends "array" ;');
$stmt-> execute();
$friends = $stmt-> fetch(PDO::FETCH_ASSOC);
$friends = json_decode($friends['array']);

// select length of friends array
$stmt = $pdo-> prepare('SELECT JSON_LENGTH(@friends) "length";');
$stmt-> execute();
$tmp = $stmt-> fetch(PDO::FETCH_ASSOC);
$friends_length = $tmp['length'];

$_SESSION['page'] = 'users';
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>Users: Friends - NShare</title>
<link rel='icon' type='image/gif' href='/assets/favicon.gif'>

<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>

<link rel='stylesheet' href='/styles/w3.css'>
<link rel='stylesheet' href='/styles/nz.css'>
<link rel='stylesheet' href='/styles/icons/css/all.css'>

<script src='/scripts/scripts.js' type='text/javascript'></script>
<script type='text/javascript'>
function openProfile(id) {
    window.location.href = '/user/index?id=' + id;
}
</script>

</head>
<body>

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
    <div class='w3-bar nz-black w3-round nz-page' style='margin-bottom:10px'>
        <button class='w3-bar-item w3-button page-button w3-dark-gray' id='friendsBtn' onclick='openPage("friends", "users")'>Friends</button>
        <button class='w3-bar-item w3-button page-button' id='exploreBtn' onclick='openPage("explore", "users")' style='width:91.9px'>Explore</button>
    </div>
    <div class='page' id='friends'>
        <div class='w3-round w3-card-2 nz-page'>
            <div class='w3-container nz-black nz-round-top'>
                <h2>Friends</h2>
            </div>
            <div class='w3-padding-16'>
                <span>Content</span>
            </div>
        </div>
    </div>
    <div class='page' id='explore' style='display:none'>
        <div class='w3-round w3-card-2 nz-page'>
            <div class='w3-container nz-black nz-round-top'>
                <h2>Explore</h2>
            </div>
            <div class='w3-container w3-padding-16'>
                <div class='w3-responsive'>
                    <table class='nz-table'>
                        <tr>
                            <th class='nz-truncate'>Username <i class='fa fa-fw fa-caret-down'></i></th>
                            <th class='nz-truncate'>User ID</th>
                            <th class='nz-truncate'>Friend Status</th>
                        </tr>

                    <?php for($i = 0; $i < $count; $i++) { ?>
                        <tr>
                            <td class='w3-button' onclick='openProfile(<?= $rows[$i]['user_id']; ?>)'><?= $rows[$i]['username']; ?></td>
                            <td><?= $rows[$i]['user_id']; ?></td>
                            
                        <?php if(in_array($rows[$i]['user_id'], $friends)) { ?>
                            <td id='<?= $rows[$i]['user_id']; ?>'>
                        <?php } else { ?>
                            <td class='w3-button w3-button w3-hover-green add-friend-button' id='<?= $rows[$i]['user_id']; ?>' onclick='addFriend(<?= $rows[$i]['user_id']; ?>)'>
                        <?php } ?>

                        <?php if(in_array($rows[$i]['user_id'], $friends)) { ?>
                            Friends
                        <?php } else { ?>
                            <span>Not Friends</span>
                        <?php } ?>

                            </td>
                        </tr>
                    <?php } ?>

                    </table>
                </div>
            </div>
        </div>
    </div>
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