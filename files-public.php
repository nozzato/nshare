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
<script src="https://kit.fontawesome.com/a0bd1a0a5e.js" crossorigin="anonymous"></script>

<script src="scripts.js" type="text/javascript"></script>

</head>
<body class="nz-dark">

<div class="nz-black" id="header">
    <div class="w3-bar">
        <a class="w3-bar-item w3-button w3-text-blue w3-mobile" href="index.php">NozzDesk Server</a>
        <div class="w3-dropdown-hover">
            <button class="w3-button">
                Files <i class="fa fa-caret-down"></i>
            </button>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom w3-card-2 w3-hide-small"><?php
                if(isset($_SESSION['user_id'])) {
                    echo '
                <a class="w3-bar-item w3-button" href="files-public.php">Public</a>
                <a class="w3-bar-item w3-button nz-round-bottom" href="files-private.php">Private</a>';
                } else {
                    echo '
                <a class="w3-bar-item w3-button nz-round-bottom" href="files-public.php">Public</a>';
                } ?>

            </div>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom-right w3-card-2 w3-hide-large w3-hide-medium"><?php
                if(isset($_SESSION['user_id'])) {
                    echo '
                <a class="w3-bar-item w3-button" href="files-public.php">Public</a>
                <a class="w3-bar-item w3-button nz-round-bottom-right" href="files-private.php">Private</a>';
                } else {
                    echo '
                <a class="w3-bar-item w3-button nz-round-bottom-right" href="files-public.php">Public</a>';
                } ?>

            </div>
        </div><?php
        if($_SESSION['admin'] == 1) {
            echo '
        <div class="w3-dropdown-hover">
            <a class="w3-button" href="">
                Admin <i class="fa fa-caret-down"></i>
            </a>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom w3-card-2">
                <a class="w3-bar-item w3-button nz-round-bottom" href="adminer.php">Database</a>
            </div>
        </div>';
        }
        if(isset($_SESSION['user_id'])) {
            echo '
        <div class="w3-dropdown-hover w3-right">
            <button class="w3-button">
                Account <i class="fa fa-caret-down"></i>
            </button>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2 nz-dropdown-left" style="max-width:100px">
                <a class="w3-bar-item w3-button" href="account.php">' . $_SESSION['username'] . '</a>
                <form action="logout.php" method="POST">
                    <button class="w3-bar-item w3-button w3-red nz-round-bottom-left" type="submit" name="logout_btn">Logout</button>
                </form>
            </div>
        </div>';
        } else {
            echo '
        <div class="w3-dropdown-click w3-right">
            <button class="w3-button" onclick="dropdownToggle()">
                Login <i class="fa fa-caret-down"></i>
            </button>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2" id="dropdown" style="translate:-73px">
                <form class="w3-right" action="login.php" method="POST">
                    <input class="w3-bar-item w3-input nz-black" type="text" name="username" placeholder="Username">
                    <input class="w3-bar-item w3-input nz-black" type="password" name="password" placeholder="Password">
                    <button class="w3-bar-item w3-button w3-green nz-round-bottom-left" type="submit" name="login_btn">Login</button>
                </form>
            </div>
        </div>';
        } ?>

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
                echo '
            <div class="w3-bar" style="margin-bottom:5px">';
                if($_SESSION['admin'] == 1) {
                    echo '
                <button class="w3-button w3-bar-item w3-red w3-round" onclick="openModal(' . $file_modal . ')" style="margin-right:5px; padding-left:17.76px; padding-right:17.76px">Delete</button>';
                }
                echo '
                <form action="files/public/' . $file . '" method="POST">
                    <input class="w3-button w3-bar-item w3-blue-grey w3-round" type="submit" value="' . $file . '">
                </form>
            </div>';
            } ?>

            <form class="w3-margin-top" action="upload.php" method="POST" enctype="multipart/form-data">
                <input class="w3-button w3-green w3-round" type="submit" name="file_upload_btn" value="Upload">
                <label class="w3-button w3-blue-grey w3-round" for="upload-file" style="cursor:pointer">Browse...</label>
                <input class="w3-hide" type="file" name="files_file" id="upload-file" required>
            </form><?php
            if(isset($_SESSION['msg'])) {
                if(substr($_SESSION['msg'], 0, 6) == 'Error:') {
                    echo '
            <p class="w3-text-red">' . $_SESSION['msg'] . '</p>';
                } else {
                    echo '
            <p>' . $_SESSION['msg'] . '</p>';
                }
            unset($_SESSION['msg']);
            } ?>

            <p>
        </div>
    </div>
    <div id="modal" class="w3-modal">
        <div class="w3-modal-content nz-dark w3-round w3-card-2">
            <header class="w3-container nz-black"> 
                <h2>Really delete?</h2>
            </header>
            <div class="w3-container">
                <p class ="m-0 text-center" id="modalContent"></p>
            </div>
            <footer class="w3-container w3-bar">
                <form action="delete.php" method="POST">
                    <button class="w3-button w3-bar-item w3-red w3-round w3-margin-bottom" type="submit" name="file" id="deleteButton" style="margin-right:5px">Delete</button>
                </form>
                <button class="w3-button w3-bar-item w3-blue-grey w3-round w3-margin-bottom" onclick="document.getElementById('modal').style.display='none'">Cancel</button>
            </footer>
        </div>
    </div>
</div>

<div class="nz-black w3-bottom" id="footer">
    <div class="w3-bar">
        <a class="w3-bar-item w3-button nz-text-black w3-hover-none" onclick="toggleFoxes()" href="javascript:void(0)">fox.exe</a>
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