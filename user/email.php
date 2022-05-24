<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(isset($_POST['email_btn'])) {
    if(!empty($_POST['email_old']) && !empty($_POST['email_new'])) {
        $email_old = $email_new = '';

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
        try {
            $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
            $stmt-> execute([$_SESSION['user']]);
            $row = $stmt-> fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
        if($email_old == $row['email']) {
            try {
                $stmt = $pdo-> prepare('UPDATE `users` SET `email` = ? WHERE `user_id` = ?;');
                $stmt-> execute([$email_new, $_SESSION['user']]);
            } catch (\PDOException $e) {
                throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
            }
            $_SESSION['msg'] = 'Email changed';
            go_back();
        } else {
            $_SESSION['msg'] = 'Error: Invalid email';
            go_back();
        }
    } else {
        $_SESSION['msg'] = 'Error: Both fields are required';
        go_back();
    }
}
go_back();
?>
