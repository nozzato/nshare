<?php
session_start();

if($_SESSION['page'] == 'home') {
    header('location:/index.php');
    exit;
} else if ($_SESSION['page'] == 'signup') {
    header('location:/user/signup.php');
    exit;
} else if($_SESSION['page'] == 'profile') {
    header('location:/user/index.php');
    exit;
} else if($_SESSION['page'] == 'settings') {
    header('location:/user/settings.php');
    exit;
} else if($_SESSION['page'] == 'files') {
    header('location:/files/index.php');
    exit;
} else if($_SESSION['page'] == 2) {
    header('location:/admin/index.php');
    exit;
} else {
    header('location:/index.php');
    exit;
}
?>
