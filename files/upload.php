<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');

if(isset($_FILES['upload_file']) || isset($_POST['upload_btn'])) {
    $file = $file_server = $file_path = $file_path_server = $file_name = $file_info = $file_mime = $file_type = '';

    if($_SESSION['page'] == 'public') {
        $file_path = '/files/public/';
        $file_path_server = '/srv/http/nozzato.com/files/public/';
    } else if($_SESSION['page'] == 'private') {
        $file_path = '/files/' . $_SESSION['username'] . '/';
        $file_path_server = '/srv/http/nozzato.com/files/' . $_SESSION['username'] . '/';
    }
    if(isset($_FILES['upload_file'])) {
        $file_name = $_FILES['upload_file']['name'];
        $file      = $file_path . $file_name;
        $file_server  = $file_path_server . $file_name;
        $file_temp = $_FILES['upload_file']['tmp_name'];

        if(move_uploaded_file($file_temp, $file_server)) {
            chmod($file_server, 0775);

            $_SESSION['msg'] = "File uploaded";
            go_back();
        } else {
            $_SESSION['msg'] = "Error: Upload failed";
            go_back();
        }
    } else if(isset($_POST['upload_btn'])) {
        $file_name    = $_POST['upload_btn'];
        $file         = $file_path . $file_name;
        $file_server  = $file_path_server . $file_name;
        $file_content = $_POST['upload_content'];

        if(file_put_contents($file_server, $file_content) != false) {
            $_SESSION['msg'] = "File saved";
            go_back();
        } else {
            $_SESSION['msg'] = "Error: Save failed";
            go_back();
        }
    } else {
        $_SESSION['msg'] = "Error: No file selected";
        go_back();
    }
}
go_back();
?>
