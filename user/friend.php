<?php
session_start();

// include functions
include_once('/srv/http/nozzato.com/scripts/scripts.php');

// if friend ID sent
if($_GET['friendid']) {
    // connect to database
    include_once('/srv/http/nozzato.com/admin/connect.php');

    // set friend variables
    $friend_id = $_GET['friendid'];

    // select friends array
    $stmt = $pdo-> prepare('SELECT `friends` INTO @friends FROM `friends` WHERE `user_id` = ?;');
    $stmt-> execute([$_SESSION['user']]);

    $stmt = $pdo-> prepare('SELECT @friends "array" ;');
    $stmt-> execute();
    $friends = $stmt-> fetch(PDO::FETCH_ASSOC);

    $friends = substr($friends['array'], 0, -1);
    $friends = $friends . ',' . $friend_id . ']';

    // update friend in database
    $stmt = $pdo-> prepare('UPDATE `friends` SET `friends` = ? WHERE `user_id` = ?;');
    $stmt-> execute([$friends, $_SESSION['user']]);
}

go_back();
?>