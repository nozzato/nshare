<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(isset($_POST['close_btn'])) {
    if(!empty($_POST['close_password'])) {
        $close_user = $close_password = '';

        if(!empty($_POST['close_user'])) {
            $close_user = trim($_POST['close_user']);
        } else {
            $close_user = $_SESSION['user'];
        }
        $close_password = trim($_POST['close_password']);

        $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
        $stmt-> execute([$close_user]);
        $row = $stmt-> fetch(PDO::FETCH_ASSOC);

        if(password_verify($close_password, $row['password']) && $_SESSION['rank'] == 'admin') {
            $stmt = $pdo-> prepare('DELETE FROM `users` WHERE `user_id` = ?;');
            $stmt-> execute([$close_user]);

            remove_dir('/srv/http/nozzato.com/files/' . $row['username']);

            if($close_user == $_SESSION['user']) {
                $_SESSION['close_logout'] = 1;
                header('location:logout.php');
                exit;
            } else {
                $_SESSION['msg'] = 'Account deleted';
                go_back();
            }
        } else if(password_verify($close_password, $row['password']) && $_SESSION['rank'] == 'member') {
            $stmt = $pdo-> prepare('DELETE FROM `users` WHERE `user_id` = ?;');
            $stmt-> execute([$close_user]);

            remove_dir('/srv/http/nozzato.com/files/' . $_SESSION['username']);

            $_SESSION['close_logout'] = 1;
            header('location:logout.php');
            exit;
        } else {
            $_SESSION['msg'] = 'Error: Invalid password';
            go_back();
        }
    } else {
        $_SESSION['msg'] = 'Error: Password is required';
        go_back();
    }
}
go_back();
?>
