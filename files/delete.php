<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(isset($_POST['delete_btn'])) {
    $file_server = $file_path_server = '';
    $file_path_server = '/srv/http/nozzato.com/files/' . $_SESSION['username'] . '/';
    $file_name = $_POST['delete_btn'];
    $file_server = $file_path_server . $_POST['delete_btn'];

    $stmt = $pdo-> prepare('DELETE FROM `files` WHERE `user_id` = ? AND `filename` = ?;');
    $stmt-> execute([$_SESSION['user'], $file_name]);

    if(unlink($file_server) == true) {
        $_SESSION['msg'] = 'File deleted';
        go_back();
    } else {
        $_SESSION['msg'] = 'Error: Delete failed';
        //go_back();
    }
}
go_back();
?>
