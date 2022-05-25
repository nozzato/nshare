<?php
session_start();

if(!isset($_SESSION['user'])) {
    header('location:/');
    exit;
}
$_SESSION['page'] = 'settings';
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>Settings: Account - NShare</title>
<link rel='icon' type='image/gif' href='/media/favicon.gif'>

<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>

<link rel='stylesheet' href='/styles/w3.css'>
<link rel='stylesheet' href='/styles/nz.css'>
<link rel='stylesheet' href='/styles/icons/css/all.css'>

<script src='/scripts/scripts.js' type='text/javascript'></script>

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
    <?php if($_SESSION['rank'] == 'admin') { ?>
    <div class='w3-round w3-card-2'>

        <div class='w3-container nz-black nz-round-top'>
            <h2>Create account</h2>
        </div>

        <form class='w3-container w3-padding-16' action='/user/signup.php' method='POST' onsubmit='return signupVerify(this)'>

            <input class='w3-input nz-black w3-border-0 w3-round' id='signup-email' type='text' placeholder='Email' name='signup_email'>

            <p></p>
            <input class='w3-input nz-black w3-border-0 w3-round' id='signup-username' type='text' placeholder='Username' name='signup_username'>

            <p></p>
            <input class='w3-input nz-black w3-border-0 w3-round' id='signup-password' type='password' placeholder='Password' name='signup_password'>

            <p></p>
            <span>Rank</span>

            <input class='w3-radio' id='signup-member' type='radio' value='member' name='signup_rank' checked>
            <label for='signup-member'>Member</label>

            <input class='w3-radio' id='signup-admin' type='radio' value='admin' name='signup_rank'>
            <label for='signup-admin'>Admin</label>

            <br><br>
            <button class='w3-btn w3-green w3-round' type='submit' name='signup_btn'>Signup</button>

        </form>

    </div>
    <br>
    <?php } ?>
    <div class='w3-round w3-card-2'>

        <div class='w3-container nz-black nz-round-top'>
            <h2>Change email</h2>
        </div>

        <form class='w3-container w3-padding-16' action='/user/email.php' method='POST' onsubmit='return emailVerify(this)'>

            <?php if($_SESSION['rank'] == 'admin') { ?>
                <input class='w3-input nz-black w3-border-0 w3-round' type='text' placeholder='User ID (optional)' name='email_user'>
                <p></p>
            <?php } ?>

            <input class='w3-input nz-black w3-border-0 w3-round' id='email-old' type='text' placeholder='Old Email' name='email_old'>

            <p></p>
            <input class='w3-input nz-black w3-border-0 w3-round' id='email-new' type='text' placeholder='New Email' name='email_new'>

            <p></p>
            <button class='w3-btn w3-green w3-round' type='submit' name='email_btn'>Change</button>

        </form>
    </div>
    <br>
    <div class='w3-round w3-card-2'>

        <div class='w3-container nz-black nz-round-top'>
            <h2>Change username</h2>
        </div>

        <form class='w3-container w3-padding-16' action='/user/username.php' method='POST' onsubmit='return usernameVerify(this)'>

            <?php if($_SESSION['rank'] == 'admin') { ?>
                <input class='w3-input nz-black w3-border-0 w3-round' type='text' placeholder='User ID (optional)' name='username_user'>
                <p></p>
            <?php } ?>

            <input class='w3-input nz-black w3-border-0 w3-round' id='username-old' type='text' placeholder='Old Username' name='username_old'>

            <p></p>
            <input class='w3-input nz-black w3-border-0 w3-round' id='username-new' type='text' placeholder='New Username' name='username_new'>

            <p></p>
            <button class='w3-btn w3-green w3-round' type='submit' name='username_btn'>Change</button>

        </form>
    </div>
    <br>
    <div class='w3-round w3-card-2'>

        <div class='w3-container nz-black nz-round-top'>
            <h2>Change password</h2>
        </div>

        <form class='w3-container w3-padding-16' action='/user/password.php' method='POST' onsubmit='return passwordVerify(this)'>

            <?php if($_SESSION['rank'] == 'admin') { ?>
                <input class='w3-input nz-black w3-border-0 w3-round' type='text' placeholder='User ID (optional)' name='password_user'>
                <p></p>
            <?php } ?>

            <input class='w3-input nz-black w3-border-0 w3-round' id='password-old' type='password' placeholder='Old Password' name='password_old'>

            <p></p>
            <input class='w3-input nz-black w3-border-0 w3-round' id='password-new' type='password' placeholder='New Password' name='password_new'>

            <p></p>
            <button class='w3-btn w3-green w3-round' type='submit' name='password_btn'>Change</button>

        </form>
    </div>
    <br>
    <div class='w3-round w3-card-2'>

        <div class='w3-container nz-black nz-round-top'>
            <h2>Close account</h2>
        </div>

        <form class='w3-container w3-padding-16' action='/user/close.php' method='POST' onsubmit='return closeVerify(this)'>

            <?php if($_SESSION['rank'] == 'admin') { ?>
                <input class='w3-input nz-black w3-border-0 w3-round' type='text' placeholder='User ID (optional)' name='close_user'>
                <p></p>
            <?php } ?>

            <input class='w3-input nz-black w3-border-0 w3-round' id='close-password' type='password' placeholder='Password' name='close_password'>

            <p></p>
            <button class='w3-btn w3-red w3-round' type='submit' name='close_btn'>Close</button>

        </form>

    </div>
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
