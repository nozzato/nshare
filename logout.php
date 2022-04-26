<?php
session_start();

session_destroy();

if($_SESSION['page'] == 'home') {
    header('location:index.php');
    exit;
} else if($_SESSION['page'] == 'account') {
    header('location:index.php');
    exit;
} else if($_SESSION['page'] == 'public') {
    header('location:files-public.php');
    exit;
} else if($_SESSION['page'] == 'private') {
    header('location:files-public.php');
    exit;
} else {
    header('location:index.php');
    exit;
}
exit;
?>