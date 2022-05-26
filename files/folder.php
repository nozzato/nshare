<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(isset($_POST['folder_btn'])) {
    $folder_path = '/files/' . $_SESSION['username'] . '/';
    $folder_path_server = '/srv/http/nozzato.com/files/' . $_SESSION['username'] . '/';
    $folder_name    = $_POST['folder_name'];
    $folder         = $folder_path . $folder_name;
    $folder_server  = $folder_path_server . $folder_name;

    if(strlen($folder_name) > 1023) {
        $_SESSION['msg'] = 'Error: Folder name must be 1023 characters or less';
        go_back();
    }
    try {
        $stmt = $pdo-> prepare('SELECT * FROM `files` WHERE `user_id` = ? AND `foldername` = ?;');
        $stmt-> execute([$_SESSION['user'], $folder_name]);
        $count = $stmt-> rowCount();
    } catch (\PDOException $e) {
        throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
    }
    if($count == 0) {
        try {
            $stmt = $pdo-> prepare('INSERT INTO `files` (`user_id`, `foldername`) VALUES (?, ?);');
            $stmt-> execute([$_SESSION['user'], $folder_name]);
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
    } else {
        try {
            $stmt = $pdo-> prepare('UPDATE `files` SET `user_id` = ?, `foldername` = ? WHERE `user_id` = ? AND `foldername` = ?;');
            $stmt-> execute([$_SESSION['user'], $folder_name, $_SESSION['user'], $folder_name]);
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
    }
    chmod($folder_server, 0775);

    $_SESSION['msg'] = 'Folder created';
    go_back();
}
go_back();
?>
