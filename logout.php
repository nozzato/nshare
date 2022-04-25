<?php
session_start();

if(isset($_POST['logout_btn'])) {
    session_destroy();
}
if($_SESSION['page'] == 'home') {
    header('location:index.php');
} else if($_SESSION['page'] == 'account') {
    header('location:index.php');
} else if($_SESSION['page'] == 'public') {
    header('location:files-public.php');
} else if($_SESSION['page'] == 'private') {
    header('location:files-public.php');
} else {
    header('location:index.php');
}
exit;
?>