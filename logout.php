<?php
session_start();

if(isset($_POST['logout_btn'])) {
    session_destroy();
}
header('location:index.php');
exit;
?>