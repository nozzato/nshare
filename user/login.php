<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');

if (isset($_POST['login_btn'])) {
    if(!empty($_POST['login_username']) && !empty($_POST['login_password'])) {
        $username = $password = '';

        $username = trim($_POST['login_username']);
        $password = trim($_POST['login_password']);

        try {
            include_once('/srv/http/nozzato.com/database/connect.php');

            $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `username` = ?;');
            $stmt-> execute([$username]);
            $row = $stmt-> fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
        if(password_verify($password, $row['password'])) {
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
