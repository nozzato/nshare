<?php
session_start();

// include functions
include_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/scripts.php');

// if username button clicked
if(isset($_POST['username_btn'])) {
    // if both fields are not empty
    if(!empty($_POST['username_old']) && !empty($_POST['username_new'])) {
        // connect to database
        include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/connect.php');

        // set username variables
        $username_old = trim($_POST['username_old']);
        $username_new = trim($_POST['username_new']);

        // if user is specified
        if(!empty($_POST['username_user'])) {
            $username_user = trim($_POST['username_user']);
        // else no user is specified
        } else {
            $username_user = $_SESSION['user'];
        }

        // validate username
        if($username_old == $username_new) {
            $_SESSION['msg'] = ['Error: Username unchanged', 'true'];
            go_back();
        }
        if(strlen($username_new) > 50) {
            $_SESSION['msg'] = ['Error: Username must be 50 characters or less', 'true'];
            go_back();
        }

        // select user data
        $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
        $stmt-> execute([$username_user]);
        $row = $stmt-> fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `username` = ?;');
        $stmt-> execute([$username_new]);
        $count = $stmt-> rowCount();

        // if username does not exist
        if($count == 0) {
            // if usernames match
            if($username_old == $row['username']) {
                // update username in database
                $stmt = $pdo-> prepare('UPDATE `users` SET `username` = ? WHERE `user_id` = ?;');
                $stmt-> execute([$username_new, $username_user]);

                // rename user directory
                rename($_SERVER['DOCUMENT_ROOT'] . '/files/' . $username_old, $_SERVER['DOCUMENT_ROOT'] . '/files/' . $username_new);

                // update session array
                $_SESSION['username'] = $username_new;

                $_SESSION['msg'] = ['Username changed', 'false'];
                go_back();
            // else usernames do not match
            } else {
                $_SESSION['msg'] = ['Error: Invalid username', 'true'];
                go_back();
            }
        // else username exists
        } else {
            $_SESSION['msg'] = ['Error: Username already in use', 'true'];
            go_back();
        }
    // else both fields are empty
    } else {
        $_SESSION['msg'] = ['Error: Both fields are required', 'true'];
        go_back();
    }
}

go_back();
?>
