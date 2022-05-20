<?php
session_start();

if(!isset($_SESSION['user'])) {
    header('location:/');
    exit;
}
$_SESSION['page'] = 'files';
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>NozzDesk Server - <?php echo $_SESSION['username'] ?>/</title>
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

<div class='w3-container w3-padding-16' id='content' style='margin-bottom:38.5px'>
    <div class='w3-round w3-card-2 nz-centre-large' id='files'>

        <div class='w3-container nz-black nz-round-top'>
            <h2 class='nz-truncate'><?php echo $_SESSION['username'] ?>/</h2>
        </div>

        <div class='w3-container w3-padding-16'>
            <?php $dir = array_slice(scandir('/srv/http/nozzato.com/files/' . $_SESSION['username'] . '/'), 2);
            foreach($dir as $dir_file) {
                $file_modal = '"' . $dir_file . '"'; ?>

                <div class='w3-bar' style='margin-bottom:5px'>

                <button class='w3-button w3-bar-item w3-red w3-round' onclick='openModal(<?php echo $file_modal ?>)' style='margin-right:5px;padding-left:17.76px;padding-right:17.76px'>Delete</button>

                <form action='/files/edit.php' method='POST'>
                    <button class='w3-button w3-bar-item w3-blue-grey w3-round' value='<?php echo $dir_file; ?>' name='edit_btn'><?php echo $dir_file; ?></button>
                </form>

            </div>
            <?php } ?>
                <form class='w3-margin-top' action='/files/upload.php' method='POST' enctype='multipart/form-data'>

                    <button class='w3-button w3-green w3-round' type='submit' name='upload_btn'>Upload</button>

                    <input class='w3-hide' id='upload-file' type='file' name='upload_file' required>

                    <label class='w3-button w3-blue-grey w3-round' for='upload-file' style='cursor:pointer'>Browse...</label>

                    <p></p>
                    <span>Privacy</span>

                    <input class='w3-radio' id='upload-private' type='radio' value='private' name='upload_privacy' checked>
                    <label for='upload-private'>Private</label>

                    <input class='w3-radio' id='upload-public' type='radio' value='public' name='upload_privacy'>
                    <label for='upload-public'>Public</label>

                </form>

        </div>

    </div>
    <div class='w3-modal' id='modal'>
        <div class='w3-modal-content nz-dark w3-round w3-card-2'>

            <header class='w3-container nz-black nz-round-top'> 
                <h2>Really delete?</h2>
            </header>

            <div class='w3-container'>
                <p class ='m-0 text-center' id='modal-content'></p>
            </div>

            <footer class='w3-container w3-bar'>

                <form action='/files/delete.php' method='POST'>
                    <button class='w3-button w3-bar-item w3-red w3-round w3-margin-bottom' id='delete-button' type='submit' name='delete_btn' style='margin-right:5px'>Delete</button>
                </form>

                <button class='w3-button w3-bar-item w3-blue-grey w3-round w3-margin-bottom' onclick='document.getElementById("modal").style.display="none"'>Cancel</button>

            </footer>
        </div>
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
