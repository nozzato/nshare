<?php
session_start();

// include functions
include_once('/srv/http/nozzato.com/scripts/scripts.php');

if(isset($_POST['close_btn'])) {
    // if password field is not empty
    if(!empty($_POST['close_password'])) {
        // connect to database
        include_once('/srv/http/nozzato.com/admin/connect.php');

        // set close variables
        $close_password = trim($_POST['close_password']);

        // if user is specified
        if(!empty($_POST['close_user'])) {
            $close_user = trim($_POST['close_user']);
        // else no user is specified
        } else {
            $close_user = $_SESSION['user'];
        }

        // select user data
        $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
        $stmt-> execute([$close_user]);
        $row = $stmt-> fetch(PDO::FETCH_ASSOC);

        // if passwords match and user is admin
        if(password_verify($close_password, $row['password']) && $_SESSION['rank'] == 'admin') {
            // delete user from database
            $stmt = $pdo-> prepare('DELETE FROM `users` WHERE `user_id` = ?;');
            $stmt-> execute([$close_user]);

            // delete directory
            remove_dir('/srv/http/nozzato.com/files/' . $row['username']);

            // if user deleted themselves
            if($close_user == $_SESSION['user']) {
                $_SESSION['close_logout'] = 1;
                header('location:logout.php');
                exit;
            // else user deleted someone else
            } else {
                $_SESSION['msg'] = 'Account deleted';
                go_back();
            }
        // else if passwords match and user is member
        } else if(password_verify($close_password, $row['password']) && $_SESSION['rank'] == 'member') {
            // delete user from database
            $stmt = $pdo-> prepare('DELETE FROM `users` WHERE `user_id` = ?;');
            $stmt-> execute([$close_user]);

            // delete directory
            remove_dir('/srv/http/nozzato.com/files/' . $_SESSION['username']);

            $_SESSION['close_logout'] = 1;
            header('location:logout.php');
            exit;
        // else passwords do not match
        } else {
            $_SESSION['msg'] = 'Error: Invalid password';
            go_back();
        }
    // else password field is empty
    } else {
        $_SESSION['msg'] = 'Error: Password is required';
        go_back();
    }
}

go_back();
?>