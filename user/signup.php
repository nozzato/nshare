<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(isset($_POST['signup_btn'])) {
    if(!empty($_POST['signup_username']) && !empty($_POST['signup_password']) && !empty($_POST['signup_email'])) {
        $signup_email = $signup_username = $signup_password = $signup_rank = '';

        $signup_email    = trim($_POST['signup_email']);
        $signup_username = trim($_POST['signup_username']);
        $signup_password = trim($_POST['signup_password']);
        $signup_rank     = $_POST['signup_rank'];

        if(strlen($signup_email) > 255) {
            $_SESSION['msg'] = 'Error: Email must be 255 characters or less';
            go_back();
        }
        if(!filter_var($signup_email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['msg'] = 'Error: Invalid email format';
            go_back();
        }
        if(strlen($signup_username) > 50) {
            $_SESSION['msg'] = 'Error: Username must be 50 characters or less';
            go_back();
        }
        if(strlen($signup_password) < 8) {
            $_SESSION['msg'] = 'Error: Password must be 8 characters or more';
            go_back();
        }
        if(strlen($signup_password) > 72) {
            $_SESSION['msg'] = 'Error: Password must be 72 characters or less';
            go_back();
        }
        try {
            $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `email` = ? OR `username` = ?;');
            $stmt-> execute([$signup_email, $signup_username]);
            $count = $stmt-> rowCount();
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
        if($count == 0) {
            try {
                $stmt = $pdo-> prepare('INSERT INTO `users` (`email`, `username`, `password`, `rank`) VALUES (?, ?, ?, ?);');
                $stmt-> execute([$signup_email, $signup_username, password_hash($signup_password, PASSWORD_DEFAULT), $signup_rank]);
            } catch (\PDOException $e) {
                throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
            }
            $old_umask = umask(0);
            mkdir('/srv/http/nozzato.com/files/' . $signup_username, 0775);
            umask($old_umask);

            $_SESSION['msg'] = 'Account created';
            go_back();
        } else {
            if(!empty($row['email'])) {
                $_SESSION['msg'] = 'Error: Email already in use';
                go_back();
            } else if(!empty($row['username'])) {
                $_SESSION['msg'] = 'Error: Username already in use';
                go_back();
            }
        }
    } else {
        $_SESSION['msg'] = 'Error: All fields are required';
        go_back();
    }
}
go_back();
?>
