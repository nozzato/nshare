<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if (isset($_POST['login_btn'])) {
    if(!empty($_POST['login_username']) && !empty($_POST['login_password'])) {
        $login_username = $login_password = '';

        $login_username = trim($_POST['login_username']);
        $login_password = trim($_POST['login_password']);

        try {
            $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `email` = ? OR `username` = ?;');
            $stmt-> execute([$login_username, $login_username]);
            $row = $stmt-> fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
        if(password_verify($login_password, $row['password'])) {
            $_SESSION['user']     = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['rank']     = $row['rank'];

            $_SESSION['msg'] = 'Logged in';
        } else {
            $_SESSION['msg'] = 'Error: Invalid username or password';
        }
    } else {
        $_SESSION['msg'] = 'Error: Both fields are required';
    }
} 
go_back();
?>
