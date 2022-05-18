<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');

if(isset($_POST['delete_btn'])) {
    $file_server = $file_path_server = '';

    if($_SESSION['page'] == 'public') {
        $file_path_server = '/srv/http/nozzato.com/files/public/';
    } else if($_SESSION['page'] == 'private') {
        $file_path_server = '/srv/http/nozzato.com/files/' . $_SESSION['username'] . '/';
    }
    $file_server = $file_path_server . $_POST['delete_btn'];

    if(unlink($file_server) == true) {
        $_SESSION['msg'] = 'File deleted';
        go_back();
    } else {
        $_SESSION['msg'] = 'Error: Delete failed';
        go_back();
    }
}
go_back();
?>
