<?php
session_start();

// include functions
include_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/scripts.php');

// if folder button clicked
if(isset($_POST['folder_btn'])) {
    // connect to database
    include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/connect.php');

    // set folder variables
    $folder_path        = '/data/' . $_SESSION['user'] . '/';
    $folder_path_server = $_SERVER['DOCUMENT_ROOT'] . '/data/' . $_SESSION['user'] . '/';
    $folder_name        = $_POST['folder_name'];
    $folder             = $folder_path . $folder_name;
    $folder_server      = $folder_path_server . $folder_name;

    // validate folder
    if(strlen($folder_name) > 1023) {
        $_SESSION['msg'] = ['Error: Folder name must be 1023 characters or less', 'true'];
        go_back();
    }

    // check if folder exists
    $stmt = $pdo-> prepare('SELECT * FROM `files` WHERE `user_id` = ? AND `foldername` = ?;');
    $stmt-> execute([$_SESSION['user'], $folder_name]);
    $count = $stmt-> rowCount();

    // if folder does not exist
    if($count == 0) {
        // insert folder into database
        $stmt = $pdo-> prepare('INSERT INTO `files` (`user_id`, `foldername`) VALUES (?, ?);');
        $stmt-> execute([$_SESSION['user'], $folder_name]);
    // else folder exists
    } else {
        // update folder in database
        $stmt = $pdo-> prepare('UPDATE `files` SET `user_id` = ?, `foldername` = ? WHERE `user_id` = ? AND `foldername` = ?;');
        $stmt-> execute([$_SESSION['user'], $folder_name, $_SESSION['user'], $folder_name]);
    }

    // create folder
    $old_umask = umask(0);
    mkdir($folder_server, 0775);
    umask($old_umask);

    $_SESSION['msg'] = ['Folder created', 'false'];
    go_back();
}

go_back();
?>
