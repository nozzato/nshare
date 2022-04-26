<?php
session_start();

if(empty($_SESSION['user_id'])) {
    header('location:index.php');
    exit;
}
$_SESSION['page'] = 'account';
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
        } ?>

        <div class="w3-dropdown-hover w3-right">
            <button class="w3-button">
                <?php echo $_SESSION['username'] ?> <i class="fa fa-caret-down"></i>
            </button>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2">
                <a class="w3-bar-item w3-button" href="account.php">Account</a>
                <form action="logout.php" method="POST">
                    <button class="w3-bar-item w3-button w3-red nz-round-bottom-left" type="submit" name="logout_btn">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="w3-container w3-center" style="margin-bottom:38.5px" id="content">
    <p>
    <div class="w3-round w3-card-2" id="form">
        <div class="w3-container nz-black nz-round-top">
            <h2>Signup or delete account</h2>
        </div>
        <form class="w3-container" action="signup.php" method="POST">
            <p>
            <input class="w3-input nz-black w3-border-0 w3-round" type="text" name="username" placeholder="Username" autofocus>
            <p>
            <input class="w3-input nz-black w3-border-0 w3-round" type="password" name="password" placeholder="Password">
            <p><?php
            if($_SESSION['admin'] == 1) {
                echo '
            <span>Admin?</span>
            <input class="w3-radio" type="radio" name="admin" value="0" id="adminFalse" checked>
            <label for="adminFalse">No</label>
            <input class="w3-radio" type="radio" name="admin" value="1" id="adminTrue">
            <label for="adminTrue">Yes</label>
            <p>
            <input class="w3-btn w3-green w3-round" type="submit" name="account_signup_btn" value="Signup">';
            } ?>

            <input class="w3-btn w3-red w3-round" type="submit" name="account_delete_btn" value="Delete"><?php
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
        </form>
    </div>
    <p>
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