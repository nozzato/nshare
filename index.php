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
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom w3-card-2 w3-hide-small"><?php
                if(isset($_SESSION['user_id'])) {
                    echo '
                <a class="w3-bar-item w3-button" href="public.php">
                    <i class="fa fa-globe"></i> Public
                </a>
                <a class="w3-bar-item w3-button nz-round-bottom" href="private.php">
                    <i class="fa fa-lock"></i> Private
                </a>';
                } else {
                    echo '
                <a class="w3-bar-item w3-button nz-round-bottom" href="public.php">
                    <i class="fa fa-globe"></i> Public
                </a>';
                } ?>

            </div>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom-right w3-card-2 w3-hide-large w3-hide-medium"><?php
                if(isset($_SESSION['user_id'])) {
                    echo '
                <a class="w3-bar-item w3-button" href="public.php">
                    <i class="fa fa-globe"></i> Public
                </a>
                <a class="w3-bar-item w3-button nz-round-bottom-right" href="private.php">
                    <i class="fa fa-lock"></i> Private
                </a>';
                } else {
                    echo '
                <a class="w3-bar-item w3-button nz-round-bottom-right" href="public.php">
                    <i class="fa fa-globe"></i> Public
                </a>';
                } ?>

            </div>
        </div><?php
        if($_SESSION['admin'] == 1) {
            echo '
        <div class="w3-dropdown-hover">
            <button class="w3-button">
                <i class="fa fa-server"></i> Admin <i class="fa fa-caret-down"></i>
            </button>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom w3-card-2">
                <a class="w3-bar-item w3-button nz-round-bottom" href="adminer.php">
                    <i class="fa fa-database"></i> Database
                </a>
            </div>
        </div>';
        }
        if(isset($_SESSION['user_id'])) {
            echo '
        <div class="w3-dropdown-hover w3-right">
            <button class="w3-button">
                <i class="fas fa-door-closed"></i> Account <i class="fa fa-caret-down"></i>
            </button>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2 nz-dropdown-left" style="max-width:100px">
                <a class="w3-bar-item w3-button" href="account.php">
                    <i class="fas fa-user"></i> ' . $_SESSION['username'] . '
                </a>
                <form action="logout.php" method="POST">
                    <button class="w3-bar-item w3-button w3-red nz-round-bottom-left" type="submit" name="logout_btn">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </div>';
        } else {
            echo '
        <div class="w3-dropdown-click w3-right">
            <button class="w3-button" onclick="dropdownToggle()">
                <i class="fas fa-door-open"></i> Login <i class="fa fa-caret-down"></i>
            </button>
            <div class="w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2 nz-dropdown-left" style="max-width:100px" id="dropdown">
                <form class="w3-right" action="login.php" method="POST">
                    <div style="display:flex">
                        <i class="fas fa-user" style="margin:8px 0 8px 16px; padding-top:4px; vertical-align:center"></i>
                        <input class="w3-bar-item w3-input nz-black" type="text" name="username" placeholder="Username" style="padding:8px 16px 8px 5px">
                    </div>
                    <div style="display:flex">
                        <i class="fas fa-key" style="margin:8px 0 8px 16px; padding-top:4px; vertical-align:center"></i>
                        <input class="w3-bar-item w3-input nz-black" type="password" name="password" placeholder="Password" style="padding:8px 16px 8px 5px">
                    </div>
                    <button class="w3-bar-item w3-button w3-green nz-round-bottom-left" type="submit" name="login_btn">
                        <i class="fa-solid fa-right-to-bracket"></i> Login
                    </button>
                </form>
            </div>
        </div>';
        } ?>

    </div>
</div>

<div class="w3-container w3-center" style="margin-bottom:38.5px" id="content">
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