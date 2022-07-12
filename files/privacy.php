<?php
session_start();

// include functions
include_once('/srv/http/nozzato.com/scripts/scripts.php');

// if privacy ID sent
if($_GET['fileid'] && $_GET['state']) {
    // connect to database
    include_once('/srv/http/nozzato.com/admin/connect.php');

    // set privacy variables
    $file_id = $_GET['fileid'];

    if($_GET['state'] == 'Private') {
        $privacy_state = 'public';
    } else if($_GET['state'] == 'Public') {
        $privacy_state = 'private';
    }

    // update privacy in database
    $stmt = $pdo-> prepare('UPDATE `files` SET `privacy` = ? WHERE `file_id` = ?;');
    $stmt-> execute([$privacy_state, $file_id]);
}

go_back();
?>