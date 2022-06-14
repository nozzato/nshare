<?php
session_start();

// include functions
include_once('/srv/http/nozzato.com/scripts/scripts.php');

// if delete button clicked
if(isset($_POST['delete_btn'])) {
    // connect to database
    include_once('/srv/http/nozzato.com/database/connect.php');

    // set delete variables
    $file_path_server = '/srv/http/nozzato.com/files/' . $_SESSION['username'] . '/';
    $file_name        = $_POST['delete_btn'];
    $file_server      = $file_path_server . $_POST['delete_btn'];

    // if delete succeeds
    if(unlink($file_server) == true) {
        // delete file from database
        $stmt = $pdo -> prepare('DELETE FROM `files` WHERE `user_id` = ? AND `filename` = ?;');
        $stmt -> execute([$_SESSION['user'], $file_name]);

        $_SESSION['msg'] = 'File deleted';
        go_back();
    }
    // else delete fails
    else {
        $_SESSION['msg'] = 'Error: Delete failed';
        go_back();
    }
}

go_back();
?>