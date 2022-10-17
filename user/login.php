<?php
session_start();

// include functions
include_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/scripts.php');

// if login button pushed
if (isset($_POST['login_btn'])) {
    // if both fields are not empty
    if(!empty($_POST['login_username']) && !empty($_POST['login_password'])) {
        // connect to database
        include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/connect.php');

        // set login variables
        $login_username = trim($_POST['login_username']);
        $login_password = trim($_POST['login_password']);

        // select user data
        $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `email` = ? OR `username` = ?;');
        $stmt-> execute([$login_username, $login_username]);
        $row = $stmt-> fetch(PDO::FETCH_ASSOC);

        // if passwords match
        if(password_verify($login_password, $row['password'])) {
            // set session array
            $_SESSION['user']       = $row['user_id'];
            $_SESSION['username']   = $row['username'];
            $_SESSION['rank']       = $row['rank'];
            $_SESSION['ban_status'] = $row['ban_status'];
            $_SESSION['ban_reason'] = $row['ban_reason'];

            $_SESSION['msg'] = ['Logged in', 'false'];
        // else passwords do not match
        } else {
            $_SESSION['msg'] = ['Error: Invalid username or password', 'true'];
        }
    // else both fields are empty
    } else {
        $_SESSION['msg'] = ['Error: Both fields are required', 'true'];
    }
}

go_back();
?>
