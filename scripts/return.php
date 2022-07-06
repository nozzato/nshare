<?php
session_start();

if($_SESSION['page'] == 'home') {
    header('location:/index');
    exit;
} else if ($_SESSION['page'] == 'signup') {
    header('location:/user/signup');
    exit;
} else if($_SESSION['page'] == 'profile') {
    header('location:/user/index');
    exit;
} else if($_SESSION['page'] == 'settings') {
    header('location:/user/settings');
    exit;
} else if($_SESSION['page'] == 'files') {
    header('location:/files/index');
    exit;
} else if($_SESSION['page'] == 'admin') {
    header('location:/admin/index');
    exit;
} else {
    header('location:/index');
    exit;
}
?>