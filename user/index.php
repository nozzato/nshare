<?php
session_start();

if(!isset($_SESSION['user'])) {
    header('location:/');
}
$_SESSION['page'] = 'account';
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>NozzDesk Server - Account</title>
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

        <a class='w3-bar-item w3-button w3-text-blue w3-mobile' href='/'>NozzDesk Server</a>

        <?php if(isset($_SESSION['user'])) { ?>
        <div class='w3-dropdown-hover'>
            <button class='w3-button'>
                <i class='fa fa-folder-open'></i> Files <i class='fa fa-caret-down'></i>
            </button>
            <div class='w3-dropdown-content w3-bar-block nz-black nz-round-bottom w3-card-2 w3-hide-small'>

                <a class='w3-bar-item w3-button nz-round-bottom' href='/files/'>
                    <i class='fa fa-lock'></i> Private
                </a>

            </div>
            <div class='w3-dropdown-content w3-bar-block nz-black nz-round-bottom-right w3-card-2 w3-hide-large w3-hide-medium'>

                <a class='w3-bar-item w3-button nz-round-bottom-right' href='/files/'>
                    <i class='fa fa-lock'></i> Private
                </a>

            </div>
        </div>
        <?php } ?>

        <?php if(isset($_SESSION['rank']) && $_SESSION['rank'] == 'admin') { ?>
        <div class='w3-dropdown-hover'>
            <button class='w3-button'>
                <i class='fa fa-server'></i> Admin <i class='fa fa-caret-down'></i>
            </button>
            <div class='w3-dropdown-content w3-bar-block nz-black nz-round-bottom w3-card-2'>

                <a class='w3-bar-item w3-button nz-round-bottom' href='/database/adminer.php'>
                    <i class='fa fa-database'></i> Database
                </a>

            </div>
        </div>
        <?php }
        
        if(!isset($_SESSION['user'])) { ?>
        <div class='w3-dropdown-click w3-right'>
            <button class='w3-button' onclick='dropdownToggle()'>
                <i class='fa fa-door-open'></i> Login <i class='fa fa-caret-down'></i>
            </button>
            <div class='w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2 nz-dropdown-left' id='dropdown' style='max-width:100px'>

                <form class='w3-right' action='/user/login.php' method='POST' onsubmit='return loginVerify(this)'>

                    <div style='display:flex'>
                        <i class='fa fa-user' style='margin:8px 0 8px 16px;padding:4px 0.93px 0 0.93px;vertical-align:center'></i>
                        <input class='w3-bar-item w3-input nz-black' id='login-username' type='text' placeholder='Username' name='login_username' style='padding:8px 16px 8px 5px'>
                    </div>

                    <div style='display:flex'>
                        <i class='fa fa-key' style='margin:8px 0 8px 16px;padding-top:4px;vertical-align:center'></i>
                        <input class='w3-bar-item w3-input nz-black' id='login-password' type='password' placeholder='Password' name='login_password' style='padding:8px 16px 8px 5px'>
                    </div>

                    <button class='w3-bar-item w3-button w3-green nz-round-bottom-left' type='submit' name='login_btn'>
                        <i class='fa fa-right-to-bracket'></i> Login
                    </button>

                </form>
            </div>
        </div>
        <?php } else { ?>
        <div class='w3-dropdown-hover w3-right'>
            <button class='w3-button'>
                <i class='fa fa-door-closed'></i> Account <i class='fa fa-caret-down'></i>
            </button>
            <div class='w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2 nz-dropdown-left' style='max-width:100px'>

                <a class='w3-bar-item w3-button' href='/user/'>
                    <i class='fa fa-user'></i> <?php echo $_SESSION['username']; ?>
                </a>

                <form action='/user/logout.php' method='POST'>
                    <button class='w3-bar-item w3-button w3-red nz-round-bottom-left' type='submit' name='logout_btn'>
                        <i class='fa fa-right-from-bracket'></i> Logout
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
            <h2>Change password</h2>
        </div>

        <form class='w3-container w3-padding-16' action='/user/password.php' method='POST' onsubmit='return passwordVerify(this)'>

            <input class='w3-input nz-black w3-border-0 w3-round' id='old-password' type='password' placeholder='Old Password' name='password_old'>

            <p></p>
            <input class='w3-input nz-black w3-border-0 w3-round' id='new-password' type='password' placeholder='New Password' name='password_new'>

            <p></p>
            <button class='w3-btn w3-green w3-round' type='submit' name='password_btn'>Change</button>

        </form>
    </div>
    <br>
    <?php if($_SESSION['rank'] == 'admin') { ?>
    <div class='w3-round w3-card-2'>

        <div class='w3-container nz-black nz-round-top'>
            <h2>Create account</h2>
        </div>

        <form class='w3-container w3-padding-16' action='/user/signup.php' method='POST' onsubmit='return signupVerify(this)'>

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
            <h2>Close account</h2>
        </div>

        <form class='w3-container w3-padding-16' action='/user/close.php' method='POST' onsubmit='return closeVerify(this)'>

            <input class='w3-input nz-black w3-border-0 w3-round' id='close-username' type='text' placeholder='Username' name='close_username'>

            <p></p>
            <input class='w3-input nz-black w3-border-0 w3-round' id='close-password' type='password' placeholder='Password' name='close_password'>

            <p></p>
            <button class='w3-btn w3-red w3-round' type='submit' name='close_btn'>Close</button>

        </form>

    </div>
</div>

<div class='nz-black w3-bottom' id='footer'>
    <div class='w3-bar'>

        <a class='w3-bar-item w3-button nz-text-black w3-hover-none' onclick='toggleFoxes()' href='javascript:void(0)'>fox.exe</a>

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
