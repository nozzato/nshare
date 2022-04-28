<?php
session_start();

if(empty($_SESSION['delete_logout'])) {
    if($_SESSION['page'] == 'home') {
        session_unset();
        $_SESSION['msg'] = "Logged out";
        header('location:index.php');
        exit;
    } else if($_SESSION['page'] == 'public') {
        session_unset();
        $_SESSION['msg'] = "Logged out";
        header('location:public.php');
        exit;
    } else if($_SESSION['page'] == 'private') {
        session_unset();
        $_SESSION['msg'] = "Logged out";
        header('location:public.php');
        exit;
    } else if($_SESSION['page'] == 'account') {
        session_unset();
        $_SESSION['msg'] = "Logged out";
        header('location:index.php');
        exit;
    } else {
        session_unset();
        $_SESSION['msg'] = "Logged out";
        header('location:index.php');
        exit;
    }
} else {
    if($_SESSION['page'] == 'home') {
        session_unset();
        $_SESSION['msg'] = "Account deleted";
        header('location:index.php');
        exit;
    } else if($_SESSION['page'] == 'public') {
        session_unset();
        $_SESSION['msg'] = "Account deleted";
        header('location:public.php');
        exit;
    } else if($_SESSION['page'] == 'private') {
        session_unset();
        $_SESSION['msg'] = "Account deleted";
        header('location:public.php');
        exit;
    } else if($_SESSION['page'] == 'account') {
        session_unset();
        $_SESSION['msg'] = "Account deleted";
        header('location:index.php');
        exit;
    } else {
        session_unset();
        $_SESSION['msg'] = "Account deleted";
        header('location:index.php');
        exit;
    }
}
?>