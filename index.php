<?php
session_start();

$_SESSION['page'] = 'home';
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
                ' . $_SESSION['username'] . ' <i class="fa fa-caret-down"></i>
            </button>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2" style="translate: -85px;">
                <a class="w3-bar-item w3-button" href="account.php">Account</a>
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
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2" id="dropdown" style="translate: -73px;">
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

<div class="w3-container w3-center" style="margin-bottom: 38.5px;" id="content">
    <pre id="arch-logo">
                  -`                 
                 .o+`                
                `ooo/                
               `+oooo:               
              `+oooooo:              
              -+oooooo+:             
            `/:-:++oooo+:            
           `/++++/+++++++:           
          `/++++++++++++++:          
         `/+++ooooooooooooo/`        
        ./ooosssso++osssssso+`       
       .oossssso-````/ossssss+`      
      -osssssso.      :ssssssso.     
     :osssssss/        osssso+++.    
    /ossssssss/        +ssssooo/-    
  `/ossssso+/:-        -:/+osssso+-  
 `+sso+:-`                 `.-/+oso: 
`++:.                           `-/+/
.`                                 `/
</pre>
</div>

<div class="nz-black w3-bottom" id="footer">
    <div class="w3-bar">
        <a class="w3-bar-item w3-button nz-text-black w3-hover-none" onclick="toggleFoxes()" href="javascript:void(0)">fox.exe</a>
    </div>
    <div class="w3-container">
        <div id="foxes" style="display: none;">
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