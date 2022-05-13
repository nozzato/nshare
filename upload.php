<?php
session_start();
include_once('functions.php');

if(isset($_FILES['file']) || isset($_POST['file'])) {
    if($_SESSION['page'] == 'public') {
        $file_path = 'files/public/';
    } else if($_SESSION['page'] == 'private') {
        $file_path = 'files/' . $_SESSION['username'] . '/';
    }
    if(isset($_FILES['file'])) {
        $file_name = $_FILES['file']['name'];
        $file = $file_path . $file_name;

        if(move_uploaded_file($file_name, $file)) {
            chmod($file, 0775);

            $_SESSION['msg'] = "File uploaded";
        } else {
            $_SESSION['msg'] = "Error: Upload failed";
        }
    } else if(isset($_POST['file'])) {
        $file_name = $_POST['file'];
        $file = $file_path . $file_name;
        $buffer = $_POST['buffer'];

        if(file_put_contents($file, $buffer) != false) {
            //chmod($file, 0775);

            $_SESSION['msg'] = "File saved";
        } else {
            $_SESSION['msg'] = "Error: Save failed";
        }
    } else {
        $_SESSION['msg'] = "Error: No file selected";
    }
    page_back();
} else {
    page_back();
}
?>