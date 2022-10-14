<?php
session_start();

// if not logged in
if(!isset($_SESSION['user'])) {
    header('location:/index');
    exit;
}
// if not admin
if($_SESSION['rank'] == 'member') {
    header('location:/index');
    exit;
}
// if banned
if($_SESSION['ban_status'] >= 1) {
    header('location:/status/banned');
    exit;
}

$_SESSION['page'] = 'admin';
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>Admin: Account - NShare</title>
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
    <?php if(isset($_SESSION['ban_status']) && $_SESSION['ban_status'] == 0) { ?>
        <a class='w3-bar-item w3-button' href='/user/users'>
            <i class='fa fa-fw fa-user'></i> Users
        </a>
    <?php } } ?>

    <?php if(isset($_SESSION['rank']) && $_SESSION['rank'] == 'admin' && isset($_SESSION['ban_status']) && $_SESSION['ban_status'] == 0) { ?>
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

<div class='w3-container w3-padding-16 w3-center' id='content'>
    <div class='w3-bar nz-black w3-round nz-page' style='margin-bottom:10px'>
        <button class='w3-bar-item w3-button page-button w3-dark-gray' id='accountBtn' onclick='openPage("account", "admin")' style='width:100px'>Account</button>
        <a class='w3-bar-item w3-button page-button' id='databaseBtn' href='/admin/adminer/index' style='width:100px'>Database</a>
    </div>
    <div class='page' id='account'>
        <div class='w3-round nz-page w3-card-2'>
            <div class='w3-container nz-black nz-round-top'>
                <h2>Ban Account</h2>
            </div>
            <form class='w3-container w3-padding-16' action='/user/ban.php' method='POST' onsubmit='return banValidate(this)'>
                <input class='w3-input nz-black w3-border-0 w3-round' id='ban-user' type='text' placeholder='User ID' name='ban_user'>
                <p></p>
                <p></p>
                <input class='w3-input nz-black w3-border-0 w3-round' id='ban-reason' type='text' placeholder='Ban Reason' name='ban_reason'>
                <p></p>
                <button class='w3-btn w3-red w3-round' type='submit' name='ban_btn'>
                    <i class='fa fa-fw fa-gavel'></i> Ban
                </button>
            </form>
        </div>
        <br>
        <div class='w3-round nz-page w3-card-2'>
            <div class='w3-container nz-black nz-round-top'>
                <h2>Unban Account</h2>
            </div>
            <form class='w3-container w3-padding-16' action='/user/unban.php' method='POST' onsubmit='return unbanValidate(this)'>
                <input class='w3-input nz-black w3-border-0 w3-round' id='unban-user' type='text' placeholder='User ID' name='unban_user'>
                <p></p>
                <p></p>
                <input class='w3-input nz-black w3-border-0 w3-round' id='unban-reason' type='text' placeholder='Unban Reason' name='unban_reason'>
                <p></p>
                <button class='w3-btn w3-green w3-round' type='submit' name='unban_btn'>
                    <i class='fa fa-fw fa-scale-unbalanced'></i> Unban
                </button>
            </form>
        </div>
        <br>
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
                <p></p>
                <span>Rank</span>
                <input class='w3-radio' id='create-member' type='radio' value='member' name='create_rank' checked>
                <label for='create-member'>Member</label>
                <input class='w3-radio' id='create-admin' type='radio' value='admin' name='create_rank'>
                <label for='create-admin'>Admin</label>
                <br><br>
                <button class='w3-btn w3-green w3-round' type='submit' name='create_btn'>
                    <i class='fa fa-fw fa-user'></i> Create
                </button>
            </form>
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
