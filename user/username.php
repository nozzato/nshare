<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(isset($_POST['username_btn'])) {
    if(!empty($_POST['username_old']) && !empty($_POST['username_new'])) {
        $username_old = $username_new = '';

        $username_old = trim($_POST['username_old']);
        $username_new = trim($_POST['username_new']);

        if($username_old == $username_new) {
            $_SESSION['msg'] = 'Error: Username unchanged';
            go_back();
        }
        if(strlen($username_new) > 50) {
            $_SESSION['msg'] = 'Error: Username must be 50 characters or less';
            go_back();
        }
        try {
            $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
            $stmt-> execute([$_SESSION['user']]);
            $row = $stmt-> fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
        if($username_old == $row['username']) {
            try {
                $stmt = $pdo-> prepare('UPDATE `users` SET `username` = ? WHERE `user_id` = ?;');
                $stmt-> execute([$username_new, $_SESSION['user']]);
            } catch (\PDOException $e) {
                throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
            }
            $_SESSION['msg'] = 'Username changed';
            go_back();
        } else {
            $_SESSION['msg'] = 'Error: Invalid username';
            go_back();
        }
    } else {
        $_SESSION['msg'] = 'Error: Both fields are required';
        go_back();
    }
}
go_back();
?>
