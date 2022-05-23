<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');

if(!isset($_SESSION['user'])) {
    header('location:/');
    exit;
}
$file_path_server = '/srv/http/nozzato.com/files/' . $_SESSION['username'] . '/';

if(!isset($_GET['id']) || !file_exists($file_path_server . $_GET['id'])) {
    $_SESSION['msg'] = 'Error: Invalid file';
    header('location:/files/index.php');
    exit;
}
$file_path = '/files/' . $_SESSION['username'] . '/';
$file_name   = $_GET['id'];
$file        = $file_path . $file_name;
$file_server = $file_path_server . $file_name;
$file_info   = new finfo(FILEINFO_MIME);
$file_mime   = $file_info -> buffer(file_get_contents($file_server));

if(substr($file_mime, 0, 4) == 'text') {
    $file_type = 'text';
} else if(substr($file_mime, 0, 5) == 'image') {
    $file_type = 'image';
} else if(substr($file_mime, 0, 5) == 'video') {
    $file_type = 'video';
} else if(substr($file_mime, 0, 5) == 'audio') {
    $file_type = 'audio';
} else {
    if(str_contains($file_mime, 'empty')) {
        $file_type = 'text';
    } else {
        header('location:' . $file);
        exit;
    }
}
$file_modal = '"' . $file_name . '"';
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title><?php echo 'NozzDesk Server - ' . $_SESSION['username'] . '/' . $file_name; ?></title>
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

<div class='w3-container w3-padding-16' id='content' style='margin-bottom:38.5px'>
    <div class='w3-round w3-card-2 nz-page'>

        <div class='w3-container nz-black nz-round-top'>
            <h2 class='nz-truncate'><?php echo $_SESSION['username'] . '/' . $file_name; ?></h2>
        </div>

        <div class='w3-container w3-padding-16'>

            <?php if($file_type == 'text' || $file_type == 'empty') { ?>
                <form action='/files/upload.php' method='POST'>

                    <textarea class='w3-input nz-monospace nz-black w3-border-0 w3-round' rows='20' name='upload_content' autofocus><?php readfile($file_server); ?></textarea>

                    <button class='w3-hide' id='save-btn' type='submit' value='<?php echo $file_name; ?>' name='upload_btn' style='margin-right:5px'>Save</button>

                </form>

            <?php } else if($file_type == 'image') { ?>
                <div class='w3-center'>

                    <img class='w3-hide-medium w3-hide-small' src='<?php echo $file; ?>' style='max-width:882px; max-height:450px'>

                    <img class='w3-hide-large w3-hide-small' src='<?php echo $file; ?>' style='max-width:730px; max-height:450px'>

                    <img class='w3-hide-large w3-hide-medium' src='<?php echo $file; ?>' style='max-width:280px; max-height:450px'>

                </div>

            <?php } else if($file_type == 'video') { ?>
                <div class='w3-center'>

                    <video class='w3-hide-medium w3-hide-small' controls style='max-width:882px; max-height:450px'>
                        <source src='<?php echo $file; ?>'>
                    </video>

                    <video class='w3-hide-large w3-hide-small' controls style='max-width:730px; max-height:450px'>
                        <source src='<?php echo $file; ?>'>
                    </video>

                    <video class='w3-hide-large w3-hide-medium' controls style='max-width:280px; max-height:450px'>
                        <source src='<?php echo $file; ?>'>
                    </video>

                </div>

            <?php } else if($file_type == 'audio') { ?>
                <div class='w3-center'>

                    <audio class='w3-block' controls>
                        <source src='<?php echo $file; ?>'>
                    </audio>

                </div>
            <?php } ?>

            <p></p>
            <div class='w3-bar'>

                <?php if($file_type == 'text') { ?>
                    <label class='w3-button w3-bar-item w3-green w3-round' for='save-btn' style='cursor:pointer; margin-right:5px'>
                        <i class='fa fa-floppy-disk'></i> Save
                    </label>
                <?php } ?>

                <button class='w3-button w3-bar-item w3-red w3-round' onclick='openModal(<?php echo $file_modal ?>)' style='margin-right:5px'>
                    <i class='fa fa-trash-can'></i> Delete
                </button>

                <form action='/files/download.php' method='POST'>
                    <button class='w3-button w3-bar-item w3-blue w3-round' value='<?php echo $file_name; ?>' name='download_btn' style='margin-right:5px'>
                        <i class='fa fa-file-arrow-up'></i> Export
                    </button>
                </form>

                <button class='w3-button w3-bar-item w3-blue-grey w3-round' onclick='goBack()'>
                    <i class='fa fa-folder-open'></i> Back
                </button>

            </div>
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
