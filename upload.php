<?php
session_start();
include_once('functions.php');

if(isset($_POST['files_upload_btn'])) {
    if(isset($_FILES['files_file'])){
        if($_SESSION['page'] == 'public') {
            $filePath = 'files/public/';
        } else if($_SESSION['page'] == 'private') {
            $filePath = 'files/' . $_SESSION['username'] . '/';
        }
        $fileName = $filepath . $_FILES['files_file']['name'];

        if(move_uploaded_file($_FILES['files_file']['tmp_name'], $filePath . $fileName)) {
            $_SESSION['msg'] = "File uploaded";
        } else {
            $_SESSION['msg'] = "Error: Upload failed";
        }
        echo $filePath;
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