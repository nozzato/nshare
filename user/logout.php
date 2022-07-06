<?php
session_start();

// if user did not close their account
if(!isset($_SESSION['close_logout'])) {
    if($_SESSION['page'] == 'home') {
        session_unset();
        $_SESSION['msg'] = 'Logged out';
        header('location:/index');
        exit;
    } else if($_SESSION['page'] == 'profile') {
        session_unset();
        $_SESSION['msg'] = 'Logged out';
        header('location:/index');
        exit;
    } else if($_SESSION['page'] == 'settings') {
        session_unset();
        $_SESSION['msg'] = 'Logged out';
        header('location:/index');
        exit;
    } else if($_SESSION['page'] == 'files') {
        session_unset();
        $_SESSION['msg'] = 'Logged out';
        header('location:/index');
        exit;
    } else if($_SESSION['page'] == 'admin') {
        session_unset();
        $_SESSION['msg'] = 'Logged out';
        header('location:/index');
        exit;
    } else {
        session_unset();
        $_SESSION['msg'] = 'Logged out';
        header('location:/index');
        exit;
    }
// else user closed their account
} else {
    session_unset();
    $_SESSION['msg'] = 'Account deleted';
    header('location:/index');
    exit;
}
?>