<?php
session_start();

function page_back() {
    if($_SESSION['page'] == 'home') {
        header('location:index.php');
        exit;
    } else if($_SESSION['page'] == 'account') {
        header('location:account.php');
        exit;
    } else if($_SESSION['page'] == 'public') {
        header('location:public.php');
        exit;
    } else if($_SESSION['page'] == 'private') {
        header('location:private.php');
        exit;
    } else {
        header('location:index.php');
        exit;
    }
}
?>