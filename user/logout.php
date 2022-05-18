<?php
session_start();

if(!isset($_SESSION['delete_logout'])) {
    if($_SESSION['page'] == 'home') {
        session_unset();
        $_SESSION['msg'] = "Logged out";
        header('location:/');
        exit;
    } else if($_SESSION['page'] == 'public') {
        session_unset();
        $_SESSION['msg'] = "Logged out";
        header('location:/files/public.php');
        exit;
    } else if($_SESSION['page'] == 'private') {
        session_unset();
        $_SESSION['msg'] = "Logged out";
        header('location:/files/public.php');
        exit;
    } else if($_SESSION['page'] == 'account') {
        session_unset();
        $_SESSION['msg'] = "Logged out";
        header('location:/');
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
