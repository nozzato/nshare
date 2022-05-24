<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(isset($_POST['password_btn'])) {
    if(!empty($_POST['password_old']) && !empty($_POST['password_new'])) {
        $password_old = $password_new = '';

        $password_old = trim($_POST['password_old']);
        $password_new = trim($_POST['password_new']);

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
        try {
            $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
            $stmt-> execute([$_SESSION['user']]);
            $row = $stmt-> fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
        if(password_verify($password_old, $row['password'])) {
            try {
                $stmt = $pdo-> prepare('UPDATE `users` SET `password` = ? WHERE `user_id` = ?;');
                $stmt-> execute([password_hash($password_new, PASSWORD_DEFAULT), $_SESSION['user']]);
            } catch (\PDOException $e) {
                throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
            }
            $_SESSION['msg'] = 'Password changed';
            go_back();
        } else {
            $_SESSION['msg'] = 'Error: Invalid password';
            go_back();
        }
    } else {
        $_SESSION['msg'] = 'Error: Both fields are required';
        go_back();
    }
}
go_back();
?>
