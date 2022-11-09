<?php
session_start();

// include functions
include_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/scripts.php');

// if delete button or delete selection button clicked
if(isset($_POST['delete_btn']) || isset($_POST['delete_sel_btn'])) {
    // connect to database
    include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/connect.php');

    // if delete button clicked
    if(isset($_POST['delete_btn'])) {
        // set delete variables
        $file_path_server = $_SERVER['DOCUMENT_ROOT'] . '/data/' . $_SESSION['user'] . '/';
        $file_name        = $_POST['delete_btn'];
        $file_server      = $file_path_server . $_POST['delete_btn'];

        // if delete succeeds
        if(unlink($file_server) == true) {
            // delete file from database
            $stmt = $pdo -> prepare('DELETE FROM `files` WHERE `user_id` = ? AND `filename` = ?;');
            $stmt -> execute([$_SESSION['user'], $file_name]);

            $_SESSION['msg'] = ['File deleted', 'false'];
            go_back();
        }
        // else delete fails
        else {
            $_SESSION['msg'] = ['Error: Delete failed', 'true'];
            go_back();
        }
    // else if delete selection button clicked
    } else if(isset($_POST['delete_sel_btn'])) {
        // if no files selected
        if(!isset($_POST['delete_sel_files'])) {
            go_back();
        }

        // set delete variables
        $file_path_server = $_SERVER['DOCUMENT_ROOT'] . '/data/' . $_SESSION['user'] . '/';
        $delete_sel_count = count($_POST['delete_sel_files']);

        // for every file
        for($i = 0; $i < $delete_sel_count; $i++) {
            $stmt = $pdo -> prepare('SELECT * FROM `files` WHERE `file_id` = ?;');
            $stmt -> execute([$_POST['delete_sel_files'][$i]]);
            $row = $stmt -> fetch(PDO::FETCH_ASSOC);

            // if delete succeeds
            if(unlink($file_path_server . $row['filename']) == true) {
                // delete file from database
                $stmt = $pdo -> prepare('DELETE FROM `files` WHERE `user_id` = ? AND `file_id` = ?;');
                $stmt -> execute([$_SESSION['user'], $_POST['delete_sel_files'][$i]]);
            } else {
                $_SESSION['msg'] = ['Error: Delete failed', 'true'];
                go_back();
            }
        }

        if($delete_sel_count == 1) {
            $_SESSION['msg'] = ['File deleted', 'false'];
            go_back();
        } else {
            $_SESSION['msg'] = ['Files deleted', 'false'];
            go_back();
        }
    }
}

go_back();
?>
