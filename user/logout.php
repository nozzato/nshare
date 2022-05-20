<?php
session_start();

if(!isset($_SESSION['close_logout'])) {
    if($_SESSION['page'] == 'home') {
        session_unset();
        $_SESSION['msg'] = "Logged out";
        header('location:/');
        exit;
    } else if($_SESSION['page'] == 'profile') {
        session_unset();
        $_SESSION['msg'] = "Logged out";
        header('location:/');
        exit;
    } else if($_SESSION['page'] == 'settings') {
        session_unset();
        $_SESSION['msg'] = "Logged out";
        header('location:/');
        exit;
    } else if($_SESSION['page'] == 'files') {
        session_unset();
        $_SESSION['msg'] = "Logged out";
        header('location:/files/');
        exit;
    } else {
        session_unset();
        $_SESSION['msg'] = "Logged out";
        header('location:/');
        exit;
    }
} else {
    session_unset();
    $_SESSION['msg'] = "Account deleted";
    header('location:/');
    exit;
}
?>
