<?php
session_start();

// If set to 1, ignore session and grant admin privileges
$override = 0;

if($override == 1) {
    $_SESSION['admin'] = 1;
} else if(empty($_SESSION['user_id'])) {
    header('location:index.php');
    exit;
} else {
    $_SESSION['admin'] = $_SESSION['admin_const'];
}
$_SESSION['page'] = 'account';
?>
<!DOCTYPE html>
<html lang="en">
<head>

<title>NozzDesk Server - Account</title>
<link rel="icon" type="image/gif" href="images/favicon.gif">

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="w3.css">
<link rel="stylesheet" href="nz.css">
<link rel="stylesheet" href="fontawesome-free-6.1.1-web/css/all.css">

<script src="scripts.js" type="text/javascript"></script>

</head>
<body class="nz-dark">

<div class="nz-black" id="header">
    <div class="w3-bar">
        <a class="w3-bar-item w3-button w3-text-blue w3-mobile" href="index.php">NozzDesk Server</a>
        <div class="w3-dropdown-hover">
        <button class="w3-button">
                <i class="fa fa-folder-open"></i> Files <i class="fa fa-caret-down"></i>
            </button>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom w3-card-2 w3-hide-small">
            <?php if(isset($_SESSION['user_id'])) { ?>
                <a class="w3-bar-item w3-button" href="public.php">
                    <i class="fa fa-globe"></i> Public
                </a>
                <a class="w3-bar-item w3-button nz-round-bottom" href="private.php">
                    <i class="fa fa-lock"></i> Private
                </a>
            <?php } else { ?>
                <a class="w3-bar-item w3-button nz-round-bottom" href="public.php">
                    <i class="fa fa-globe"></i> Public
                </a>
            <?php } ?>
            </div>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom-right w3-card-2 w3-hide-large w3-hide-medium">
            <?php if(isset($_SESSION['user_id'])) { ?>
                <a class="w3-bar-item w3-button" href="public.php">
                    <i class="fa fa-globe"></i> Public
                </a>
                <a class="w3-bar-item w3-button nz-round-bottom-right" href="private.php">
                    <i class="fa fa-lock"></i> Private
                </a>
            <?php } else { ?>
                <a class="w3-bar-item w3-button nz-round-bottom-right" href="public.php">
                    <i class="fa fa-globe"></i> Public
                </a>
            <?php } ?>
            </div>
        </div>
    <?php if($_SESSION['admin'] == 1) { ?>
        <div class="w3-dropdown-hover">
            <button class="w3-button">
                <i class="fa fa-server"></i> Admin <i class="fa fa-caret-down"></i>
            </button>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom w3-card-2">
                <a class="w3-bar-item w3-button nz-round-bottom" href="adminer.php">
                    <i class="fa fa-database"></i> Database
                </a>
            </div>
        </div>
    <?php } ?>
    <?php if(isset($_SESSION['user_id'])) { ?>
        <div class="w3-dropdown-hover w3-right">
            <button class="w3-button">
                <i class="fas fa-door-closed"></i> Account <i class="fa fa-caret-down"></i>
            </button>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2 nz-dropdown-left" style="max-width:100px">
                <a class="w3-bar-item w3-button" href="account.php">
                    <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                </a>
                <form action="logout.php" method="POST">
                    <button class="w3-bar-item w3-button w3-red nz-round-bottom-left" type="submit" name="logout_btn">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    <?php } else { ?>
        <div class="w3-dropdown-click w3-right">
            <button class="w3-button" onclick="dropdownToggle()">
                <i class="fas fa-door-open"></i> Login <i class="fa fa-caret-down"></i>
            </button>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2 nz-dropdown-left" style="max-width:100px" id="dropdown">
                <form class="w3-right" action="login.php" method="POST" id="account-form">
                    <div style="display:flex">
                        <i class="fas fa-user" style="margin:8px 0 8px 16px; padding:4px 0.93px 0 0.93px; vertical-align:center"></i>
                        <input class="w3-bar-item w3-input nz-black" type="text" name="username" placeholder="Username" id="username" style="padding:8px 16px 8px 5px">
                    </div>
                    <div style="display:flex">
                        <i class="fas fa-key" style="margin:8px 0 8px 16px; padding-top:4px; vertical-align:center"></i>
                        <input class="w3-bar-item w3-input nz-black" type="password" name="password" placeholder="Password" id="password" style="padding:8px 16px 8px 5px">
                    </div>
                    <button class="w3-bar-item w3-button w3-green nz-round-bottom-left" type="submit" name="login_btn">
                        <i class="fa-solid fa-right-to-bracket"></i> Login
                    </button>
                </form>
            </div>
        </div>
    <?php } ?>
    </div>
<?php if($override == 1) { ?>
    <div class="w3-bar w3-red">
        <span class="w3-bar-item">&nbsp;</span>
        <span class="w3-bar-item nz-text-animate-left" style="white-space:nowrap">WARNING /// SESSION OVERRIDE IN PROGRESS /// WARNING /// SESSION OVERRIDE IN PROGRESS /// WARNING /// SESSION OVERRIDE IN PROGRESS /// WARNING /// SESSION OVERRIDE IN PROGRESS /// WARNING /// SESSION OVERRIDE IN PROGRESS /// WARNING /// SESSION OVERRIDE IN PROGRESS /// WARNING /// SESSION OVERRIDE IN PROGRESS /// WARNING /// SESSION OVERRIDE IN PROGRESS /// WARNING /// SESSION OVERRIDE IN PROGRESS /// WARNING /// SESSION OVERRIDE IN PROGRESS</span>
    </div>
<?php } ?>
</div>

<div class="w3-container w3-center" style="margin-bottom:38.5px" id="content">
    <p></p>
    <div class="w3-round w3-card-2 nz-centre-large" id="password-change-form">
        <div class="w3-container nz-black nz-round-top">
            <h2>Change password</h2>
        </div>
        <form class="w3-container" action="change-password.php" method="POST">
            <p></p>
            <input class="w3-input nz-black w3-border-0 w3-round" type="password" name="old_password" placeholder="Old Password" id="old-password">
            <p></p>
            <input class="w3-input nz-black w3-border-0 w3-round" type="password" name="new_password" placeholder="New Password" id="new-password">
            <p></p>
            <input class="w3-btn w3-green w3-round" type="submit" name="user_password_change_btn" value="Change">
            <p></p>
        </form>
    </div>
    <p></p>
    <div class="w3-round w3-card-2 nz-centre-large" id="account-form">
        <div class="w3-container nz-black nz-round-top">
        <?php if($_SESSION['admin'] == 1) { ?>
            <h2>Delete or create account</h2>
        <?php } else { ?>
            <h2>Delete account</h2>
        <?php } ?>
        </div>
        <form class="w3-container" action="user.php" method="POST" onsubmit="return verifyAccount(this)">
            <p></p>
            <input class="w3-input nz-black w3-border-0 w3-round" type="text" name="username" placeholder="Username" id="username">
            <p></p>
            <input class="w3-input nz-black w3-border-0 w3-round" type="password" name="password" placeholder="Password" id="password">
            <p></p>
        <?php if($_SESSION['admin'] == 1) { ?>
            <span>Admin?</span>
            <input class="w3-radio" type="radio" name="admin" value="0" id="adminFalse" checked>
            <label for="adminFalse">No</label>
            <input class="w3-radio" type="radio" name="admin" value="1" id="adminTrue">
            <label for="adminTrue">Yes</label>
            <p></p>
            <input class="w3-btn w3-red w3-round" type="submit" name="user_delete_btn" value="Delete">
            <input class="w3-btn w3-green w3-round" type="submit" name="user_signup_btn" value="Signup">
        <?php } else { ?>
            <input class="w3-btn w3-red w3-round" type="submit" name="user_delete_btn" value="Delete">
        <?php } ?>
            <p></p>
        </form>
    </div>
    <p></p>
</div>

<div class="nz-black w3-bottom" id="footer">
    <div class="w3-bar">
        <a class="w3-bar-item w3-button nz-text-black w3-hover-none" onclick="toggleFoxes()" href="javascript:void(0)">fox.exe</a>
        <div class="w3-display-bottommiddle" id="msg_box" style="bottom:9px">
        <?php if(substr($_SESSION['msg'], 0, 6) == 'Error:') { ?>
            <span class="w3-text-red" id="msg"><?php echo $_SESSION['msg']; ?></span>
        <?php } else { ?>
            <span id="msg"><?php echo $_SESSION['msg']; ?></span>
        <?php }
        unset($_SESSION['msg']); ?>
        </div>
    </div>
    <div class="w3-container">
        <div id="foxes" style="display:none">
            <div class="nz-fox-animate-right">
                <img src="images/fox-bounce-right.gif">
            </div>
            <div class="nz-fox-animate-left">
                <img src="images/fox-bounce-left.gif">
            </div>
        </div>
    </div>
</div>

</body>
</html>