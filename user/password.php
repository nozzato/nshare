<?php
session_start();

// include functions
include_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/scripts.php');

// if password button pushed
if(isset($_POST['password_btn'])) {
    // if both fields are not empty
    if(!empty($_POST['password_old']) && !empty($_POST['password_new'])) {
        // connect to database
        include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/connect.php');

        // set password variables
        $password_old = trim($_POST['password_old']);
        $password_new = trim($_POST['password_new']);

        // if user is specified
        if(!empty($_POST['password_user'])) {
            $password_user = trim($_POST['password_user']);
        // else no user is specified
        } else {
            $password_user = $_SESSION['user'];
        }

        // validate password
        if($password_old == $password_new) {
            $_SESSION['msg'] = 'Error: Password unchanged';
            go_back();
        }
        if(strlen($password_new) < 8) {
            $_SESSION['msg'] = 'Error: New password must be 8 characters or more';
            go_back();
        }
        if(strlen($password_new) > 72) {
            $_SESSION['msg'] = 'Error: New password must be 72 characters or less';
            go_back();
        }

        // select user data
        $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
        $stmt-> execute([$password_user]);
        $row = $stmt-> fetch(PDO::FETCH_ASSOC);

        // if passwords match
        if(password_verify($password_old, $row['password'])) {
            // update password in database
            $stmt = $pdo-> prepare('UPDATE `users` SET `password` = ? WHERE `user_id` = ?;');
            $stmt-> execute([password_hash($password_new, PASSWORD_DEFAULT), $password_user]);

            $_SESSION['msg'] = 'Password changed';
            go_back();
        // else passwords do not match
        } else {
            $_SESSION['msg'] = 'Error: Invalid password';
            go_back();
        }
    // else both fields are empty
    } else {
        $_SESSION['msg'] = 'Error: Both fields are required';
        go_back();
    }
}

go_back();
?>
