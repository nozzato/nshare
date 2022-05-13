<?php
session_start();
include_once('functions.php');

if(isset($_POST['upload_btn'])) {
    if($_SESSION['page'] == 'public') {
        $file_path = 'files/public/';
    } else if($_SESSION['page'] == 'private') {
        $file_path = 'files/' . $_SESSION['username'] . '/';
    }
    if(isset($_FILES['file'])) {
        $file_name = $_FILES['file']['name'];

        if(move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name)) {
            chmod( $file_path . $file_name, 0775);

            $_SESSION['msg'] = "File uploaded";
        } else {
            $_SESSION['msg'] = "Error: Upload failed";
        }
    } else if(isset($_POST['file'])) {
        $file_name = $_POST['file']['name'];

        if(move_uploaded_file($_POST['file']['tmp_name'], $file_path . $file_name)) {
            chmod( $file_path . $file_name, 0775);

            $_SESSION['msg'] = "File uploaded";
        } else {
            $_SESSION['msg'] = "Error: Upload failed";
        }
    } else {
        $_SESSION['msg'] = "Error: No file selected";
    }
    if($_SESSION['page'] == 'public') {
        header('location:public.php');
        exit;
    } else if($_SESSION['page'] == 'private') {
        header('location:private.php');
        exit;
    }
} else {
    page_back();
}
?>