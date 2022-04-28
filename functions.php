<?php
session_start();

function page_back() {
    if($_SESSION['page'] == 'home') {
        header('location:index.php');
    } else if($_SESSION['page'] == 'account') {
        header('location:account.php');
    } else if($_SESSION['page'] == 'public') {
        header('location:public.php');
    } else if($_SESSION['page'] == 'private') {
        header('location:private.php');
    } else {
        header('location:index.php');
    }
}
?>