<?php
session_start();

// include functions
include_once('/srv/http/nozzato.com/scripts/scripts.php');

require('/srv/http/nozzato.com/vendor/autoload.php');
use EmailChecker\EmailChecker;

// if email button clicked
if(isset($_POST['email_btn'])) {
    // if both fields are not empty
    if(!empty($_POST['email_old']) && !empty($_POST['email_new'])) {
        // connect to database
        include_once('/srv/http/nozzato.com/admin/connect.php');

        // set email variables
        $email_old = trim($_POST['email_old']);
        $email_new = trim($_POST['email_new']);

        // if user is specified
        if(!empty($_POST['email_user'])) {
            $email_user = trim($_POST['email_user']);
        // else no user is specified
        } else {
            $email_user = $_SESSION['user'];
        }

        // validate email
        if($email_old == $email_new) {
            $_SESSION['msg'] = 'Error: Email unchanged';
            go_back();
        }
        if(strlen($email_new) > 255) {
            $_SESSION['msg'] = 'Error: New email must be 255 characters or less';
            go_back();
        }
        if(!filter_var($email_new, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['msg'] = 'Error: Invalid email format';
            go_back();
        }
        $checker = new EmailChecker();
        if(!$checker->isValid($email_new)) {
            $_SESSION['msg'] = 'Error: Invalid email domain';
            go_back();
        }

        // select user data
        $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
        $stmt-> execute([$email_user]);
        $row = $stmt-> fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `email` = ?;');
        $stmt-> execute([$email_new]);
        $count = $stmt-> rowCount();

        // if email does not exist
        if($count == 0) {
            // if emails match
            if($email_old == $row['email']) {
                // update email in database
                $stmt = $pdo-> prepare('UPDATE `users` SET `email` = ? WHERE `user_id` = ?;');
                $stmt-> execute([$email_new, $email_user]);

                $_SESSION['msg'] = 'Email changed';
                go_back();
            // else emails do not match
            } else {
                $_SESSION['msg'] = 'Error: Invalid email';
                go_back();
            }
        // else email exists
        } else {
            $_SESSION['msg'] = 'Error: Email already in use';
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