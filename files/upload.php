<?php
session_start();

// include functions
include_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/scripts.php');

// if upload or save button clicked
if(isset($_FILES['upload_file']) || isset($_POST['upload_btn'])) {
    // connect to database
    include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/connect.php');

    // set upload variables
    $file_path = '/data/' . $_SESSION['user'] . '/';
    $file_path_server = $_SERVER['DOCUMENT_ROOT'] . '/data/' . $_SESSION['user'] . '/';

    // if upload button clicked
    if(isset($_FILES['upload_file'])) {
        $file_count = count($_FILES['upload_file']['name']);

        // validate upload
        if($file_count > 100) {
            $_SESSION['msg'] = ['Error: Cannot upload more than 100 files at a time', 'true'];
            go_back();
        }

        $file_privacy = $_POST['upload_privacy'];
        $file_size_total = 0;

        // if 1 file uploaded
        if($file_count == 1) {
            $file_temp = $_FILES['upload_file']['tmp_name'][0];
            $file_name = trim($_FILES['upload_file']['name'][0], '.');
            $file_size = filesize($file_temp);
            $file_size_total = $file_size;

            if(strlen($file_name) > 1023) {
                $_SESSION['msg'] = ['Error: Filename must be 1023 characters or less', 'true'];
                go_back();
            }
        // else multiple files uploaded
        } else {
            // calculate total file size
            for($i = 0; $i < $file_count; $i++) {
                $file_temp = $_FILES['upload_file']['tmp_name'][$i];
                $file_name = trim($_FILES['upload_file']['name'][$i], '.');
                $file_size = filesize($file_temp);
                $file_size_total += $file_size;

                if(strlen($file_name) > 1023) {
                    $_SESSION['msg'] = ['Error: Filenames must be 1023 characters or less', 'true'];
                    go_back();
                }
            }
        }

        // select user's files
        $stmt = $pdo-> prepare('SELECT * FROM `files` WHERE `user_id` = ?;');
        $stmt-> execute([$_SESSION['user']]);
        $rows = $stmt-> fetchAll(PDO::FETCH_ASSOC);
        $count = $stmt-> rowCount();

        // calculate remaining file size
        $db_file_size_total = 0;

        for($i = 0; $i <= $count - 1; $i++) {
            $db_file_size_total += $rows[$i]['size'];
        }

        if($file_size_total + $db_file_size_total > 5368709120) {
            $_SESSION['msg'] = ['Error: Not enough storage space', 'true'];
            go_back();
        }

        // upload files
        for($i = 0; $i < $file_count; $i++) {
            $file_name   = trim($_FILES['upload_file']['name'][$i], '.');
            $file        = $file_path . $file_name;
            $file_server = $file_path_server . $file_name;
            $file_temp   = $_FILES['upload_file']['tmp_name'][$i];
            $file_size   = filesize($file_temp);

            // if upload succeeds
            if(move_uploaded_file($file_temp, $file_server)) {
                // check if file exists
                $stmt = $pdo-> prepare('SELECT * FROM `files` WHERE `user_id` = ? AND `filename` = ?;');
                $stmt-> execute([$_SESSION['user'], $file_name]);
                $count = $stmt-> rowCount();

                // if file does not exist
                if($count == 0) {
                    // insert file into database
                    $stmt = $pdo-> prepare('INSERT INTO `files` (`user_id`, `filename`, `size`, `privacy`, `upload_date`) VALUES (?, ?, ?, ?, NOW());');
                    $stmt-> execute([$_SESSION['user'], $file_name, $file_size, $file_privacy]);
                // else file exists
                } else {
                    // update file in database
                    $stmt = $pdo-> prepare('UPDATE `files` SET `user_id` = ?, `filename` = ?, `size` = ?, `privacy` = ?, `upload_date` = NOW() WHERE `user_id` = ? AND `filename` = ?;');
                    $stmt-> execute([$_SESSION['user'], $file_name, $file_size, $file_privacy, $_SESSION['user'], $file_name]);
                }

                // set file permissions
                chmod($file_server, 0775);
            // else upload fails
            } else {
                $_SESSION['msg'] = ['Error: Upload failed', 'true'];
                go_back();
            }
        }

        if($file_count == 1) {
            $_SESSION['msg'] = ['File uploaded', 'false'];
            go_back();
        } else {
            $_SESSION['msg'] = ['Files uploaded', 'false'];
            go_back();
        }
    // else if save button clicked
    } else if(isset($_POST['upload_btn'])) {

        // set save variables
        $file_name    = $_POST['upload_btn'];
        $file         = $file_path . $file_name;
        $file_server  = $file_path_server . $file_name;
        $file_content = $_POST['upload_content'];

        // validate save
        $file_size = strlen($file_content);

        // select user's files
        $stmt = $pdo-> prepare('SELECT * FROM `files` WHERE `user_id` = ?;');
        $stmt-> execute([$_SESSION['user']]);
        $rows = $stmt-> fetchAll(PDO::FETCH_ASSOC);
        $count = $stmt-> rowCount();

        // calculate remaining file size
        $db_file_size_total = 0;

        for($i = 0; $i <= $count - 1; $i++) {
            $db_file_size_total += $rows[$i]['size'];
        }

        if($file_size + $db_file_size_total > 5368709120) {
            $_SESSION['msg'] = ['Error: Not enough storage space', 'true'];
            go_back();
        }

        // if save succeeds
        if(file_put_contents($file_server, $file_content) != false) {
            $file_size = filesize($file_server);

            // update file in database
            $stmt = $pdo-> prepare('UPDATE `files` SET `size` = ?, `upload_date` = NOW() WHERE `user_id` = ? AND `filename` = ?;');
            $stmt-> execute([$file_size, $_SESSION['user'], $file_name]);

            $_SESSION['msg'] = ['File saved', 'false'];
            go_back();
        // else save fails
        } else {
            $_SESSION['msg'] = ['Error: Save failed', 'true'];
            go_back();
        }
    // else no file selected
    } else {
        $_SESSION['msg'] = ['Error: No file selected', 'true'];
        go_back();
    }
}

go_back();
?>
