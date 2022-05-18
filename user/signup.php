<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(isset($_POST['signup_btn'])) {
    if(!empty($_POST['signup_username']) && !empty($_POST['signup_password'])) {
        $signup_username = $signup_password = $signup_rank = '';

        $signup_username = trim($_POST['signup_username']);
        $signup_password = trim($_POST['signup_password']);
        $signup_rank     = $_POST['signup_rank'];

        if(strlen($signup_username) > 50) {
            $_SESSION['msg'] = "Error: Username must be 50 characters or less";
            go_back();
        }
        if(strlen($signup_username) < 8) {
            $_SESSION['msg'] = "Error: Password must be 8 characters or more";
            go_back();
        }
        if(strlen($signup_username) > 72) {
            $_SESSION['msg'] = "Error: Password must be 72 characters or less";
            go_back();
        }
        try {
            $stmt = $pdo-> prepare("SELECT * FROM `users` WHERE `username` = ?;");
            $stmt-> execute([$signup_username]);
            $count = $stmt-> rowCount();
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
        if($count == 0) {
            try {
                $stmt = $pdo-> prepare("INSERT INTO `users` (`username`, `password`, `rank`) VALUES (?, ?, ?);");
                $stmt-> execute([$signup_username, password_hash($signup_password, PASSWORD_DEFAULT), $signup_rank]);
            } catch (\PDOException $e) {
                throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
            }
            $old_umask = umask(0);
            mkdir('/srv/http/nozzato.com/files/' . $signup_username, 0775);
            umask($old_umask);

            $_SESSION['msg'] = "Account created";
            go_back();
        } else {
            $_SESSION['msg'] = "Error: Account already exists";
            go_back();
        }
    } else {
        $_SESSION['msg'] = "Error: Both fields are required";
        go_back();
    }
}
go_back();
?>