<?php
session_start();

$_SESSION['page'] = 'public';
?>
<!DOCTYPE html>
<html lang="en">
<head>

<title>NozzDesk Server</title>
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
                <form class="w3-right" action="login.php" method="POST" onsubmit="return showMsg(this)" id="login-form">
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
</div>

<div class="w3-container" style="margin-bottom:38.5px" id="content">
    <p>
    <div class="w3-round w3-card-2" id="files">
        <div class="w3-container nz-black nz-round-top" style="display:flex">
            <h2 style="overflow:hidden; text-overflow:ellipsis">public</h2>
            <h2>/</h2>
        </div>
        <div class="w3-container">
            <p>
        <?php
        $dir = array_slice(scandir('files/public/'), 2);
        foreach($dir as $file) {
            $file_modal = "'" . $file . "'";
        ?>
            <div class="w3-bar" style="margin-bottom:5px">
        <?php if($_SESSION['admin'] == 1) { ?>
                <button class="w3-button w3-bar-item w3-red w3-round" onclick="openModal(<?php echo $file_modal; ?>)" style="margin-right:5px; padding-left:17.76px; padding-right:17.76px">Delete</button>
        <?php } ?>
                <form action="files/public/<?php echo $file; ?>" method="POST">
                    <input class="w3-button w3-bar-item w3-blue-grey w3-round" type="submit" value="<?php echo $file; ?>">
                </form>
            </div>
        <?php } ?>
        <?php if($_SESSION['admin'] == 1) { ?>
            <form class="w3-margin-top" action="upload.php" method="POST" enctype="multipart/form-data">
                <input class="w3-button w3-green w3-round" type="submit" name="files_upload_btn" value="Upload">
                <label class="w3-button w3-blue-grey w3-round" for="upload-file" style="cursor:pointer">Browse...</label>
                <input class="w3-hide" type="file" name="files_file" id="upload-file" required>
            </form>
        <?php } ?>
            <p>
        </div>
    </div>
    <div id="modal" class="w3-modal">
        <div class="w3-modal-content nz-dark w3-round w3-card-2">
            <header class="w3-container nz-black"> 
                <h2>Really delete?</h2>
            </header>
            <div class="w3-container">
                <p class ="m-0 text-center" id="modal-content"></p>
            </div>
            <footer class="w3-container w3-bar">
                <form action="delete.php" method="POST">
                    <button class="w3-button w3-bar-item w3-red w3-round w3-margin-bottom" type="submit" name="file" id="delete-button" style="margin-right:5px">Delete</button>
                </form>
                <button class="w3-button w3-bar-item w3-blue-grey w3-round w3-margin-bottom" onclick="document.getElementById('modal').style.display='none'">Cancel</button>
            </footer>
        </div>
    </div>
</div>

<div class="nz-black w3-bottom" id="footer">
    <div class="w3-bar">
        <a class="w3-bar-item w3-button nz-text-black w3-hover-none" onclick="toggleFoxes()" href="javascript:void(0)">fox.exe</a>
        <div class="w3-display-bottommiddle" style="bottom:9px" id="msg_box">
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
            <div class="fox-right-first">
                <img src="images/fox-bounce-right.gif">
            </div>
            <div class="fox-right">
                <img src="images/fox-bounce-right.gif">
            </div>
            <div class="fox-left-first">
                <img src="images/fox-bounce-left.gif">
            </div>
            <div class="fox-left">
                <img src="images/fox-bounce-left.gif">
            </div>
        </div>
    </div>
</div>

</body>
</html>