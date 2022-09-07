<?php
session_start();

// include functions
include_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/scripts.php');

// if friend ID sent
if($_GET['friendid']) {
    // connect to database
    include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/connect.php');

    // set unfriend variables
    $friend_id = $_GET['friendid'];

    // select friends array
    $stmt = $pdo-> prepare('SELECT `friends` INTO @friends FROM `friends` WHERE `user_id` = ?;');
    $stmt-> execute([$_SESSION['user']]);

    $stmt = $pdo-> prepare('SELECT @friends "array" ;');
    $stmt-> execute();
    $friends = $stmt-> fetch(PDO::FETCH_ASSOC);

    // remove friend from friends array
    $friends = str_replace(',' . $friend_id,'' , $friends['array']);

    // update friend in database
    $stmt = $pdo-> prepare('UPDATE `friends` SET `friends` = ? WHERE `user_id` = ?;');
    $stmt-> execute([$friends, $_SESSION['user']]);

    // select target friends array
    $stmt = $pdo-> prepare('SELECT `friends` INTO @friends FROM `friends` WHERE `user_id` = ?;');
    $stmt-> execute([$friend_id]);

    $stmt = $pdo-> prepare('SELECT @friends "array" ;');
    $stmt-> execute();
    $friends = $stmt-> fetch(PDO::FETCH_ASSOC);

    // remove user target friends array
    $friends = str_replace(',' . $_SESSION['user'],'' , $friends['array']);

    // update friend in database
    $stmt = $pdo-> prepare('UPDATE `friends` SET `friends` = ? WHERE `user_id` = ?;');
    $stmt-> execute([$friends, $friend_id]);
}

go_back();
?>
