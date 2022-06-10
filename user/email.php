<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(isset($_POST['email_btn'])) {
    if(!empty($_POST['email_old']) && !empty($_POST['email_new'])) {
        $email_user = $email_old = $email_new = '';

        if(!empty($_POST['email_user'])) {
            $email_user = trim($_POST['email_user']);
        } else {
            $email_user = $_SESSION['user'];
        }
        $email_old = trim($_POST['email_old']);
        $email_new = trim($_POST['email_new']);

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
        $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
        $stmt-> execute([$email_user]);
        $row = $stmt-> fetch(PDO::FETCH_ASSOC);
        $count = $stmt-> rowCount();

        if($count == 0) {
            if($email_old == $row['email']) {
                $stmt = $pdo-> prepare('UPDATE `users` SET `email` = ? WHERE `user_id` = ?;');
                $stmt-> execute([$email_new, $email_user]);

                $_SESSION['msg'] = 'Email changed';
                go_back();
            } else {
                $_SESSION['msg'] = 'Error: Invalid email';
                go_back();
            }
        } else {
            $_SESSION['msg'] = 'Error: Email already in use';
            go_back();
        }
    } else {
        $_SESSION['msg'] = 'Error: Both fields are required';
        go_back();
    }
}
go_back();
?>
