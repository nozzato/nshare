<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(isset($_FILES['upload_file']) || isset($_POST['upload_btn'])) {
    $file_path = '/files/' . $_SESSION['username'] . '/';
    $file_path_server = '/srv/http/nozzato.com/files/' . $_SESSION['username'] . '/';
    if(isset($_FILES['upload_file'])) {
        $file_name    = $_FILES['upload_file']['name'];
        $file         = $file_path . $file_name;
        $file_server  = $file_path_server . $file_name;
        $file_temp    = $_FILES['upload_file']['tmp_name'];
        $file_size    = filesize($file_temp);
        $file_privacy = $_POST['upload_privacy'];

        if(strlen($file_name) < 1) {
            $_SESSION['msg'] = "Error: Filename must be 1 character or more";
            go_back();
        }
        if(strlen($file_name) > 1023) {
            $_SESSION['msg'] = "Error: Filename must be 1023 characters or less";
            go_back();
        }
        if(move_uploaded_file($file_temp, $file_server)) {
            try {
                $stmt = $pdo-> prepare("SELECT * FROM `files` WHERE `user_id` = ? AND `filename` = ?;");
                $stmt-> execute([$_SESSION['user'], $file_name]);
                $count = $stmt-> rowCount();
            } catch (\PDOException $e) {
                throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
            }
            if($count == 0) {
                try {
                    $stmt = $pdo-> prepare("INSERT INTO `files` (`user_id`, `filename`, `size`, `privacy`) VALUES (?, ?, ?, ?);");
                    $stmt-> execute([$_SESSION['user'], $file_name, $file_size, $file_privacy]);
                } catch (\PDOException $e) {
                    throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
                }
            } else {
                try {
                    $stmt = $pdo-> prepare("UPDATE `files` SET `user_id` = ?, `filename` = ?, `size` = ?, `privacy` = ? WHERE `user_id` = ? AND `filename` = ?;");
                    $stmt-> execute([$_SESSION['user'], $file_name, $file_size, $file_privacy, $_SESSION['user'], $file_name]);
                } catch (\PDOException $e) {
                    throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
                }
            }
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

        if(strlen($file_name) < 1) {
            $_SESSION['msg'] = "Error: Filename must be 1 character or more";
            go_back();
        }
        if(strlen($file_name) > 1023) {
            $_SESSION['msg'] = "Error: Filename must be 1023 characters or less";
            go_back();
        }
        if(file_put_contents($file_server, $file_content) != false) {
            $file_size = filesize($file_server);

            try {
                $stmt = $pdo-> prepare("UPDATE `files` SET `size` = ? WHERE `user_id` = ? AND `filename` = ?;");
                $stmt-> execute([$file_size, $_SESSION['user'], $file_name]);
            } catch (\PDOException $e) {
                throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
            }
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
