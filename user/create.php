<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(isset($_POST['create_btn'])) {
    h_captcha($_POST['h-captcha-response']);

    if(!empty($_POST['create_username']) && !empty($_POST['create_password']) && !empty($_POST['create_email'])) {
        $create_email = $create_username = $create_password = $create_rank = '';

        $create_email    = trim($_POST['create_email']);
        $create_username = trim($_POST['create_username']);
        $create_password = trim($_POST['create_password']);
        if(isset($_POST['rank'])) {
            $create_rank = $_POST['create_rank'];
        } else {
            $create_rank = 'member';
        }
        if(strlen($create_email) > 255) {
            $_SESSION['msg'] = 'Error: Email must be 255 characters or less';
            go_back();
        }
        if(!filter_var($create_email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['msg'] = 'Error: Invalid email format';
            go_back();
        }
        if(strlen($create_username) > 50) {
            $_SESSION['msg'] = 'Error: Username must be 50 characters or less';
            go_back();
        }
        if(strlen($create_password) < 8) {
            $_SESSION['msg'] = 'Error: Password must be 8 characters or more';
            go_back();
        }
        if(strlen($create_password) > 72) {
            $_SESSION['msg'] = 'Error: Password must be 72 characters or less';
            go_back();
        }
        try {
            $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `email` = ? OR `username` = ?;');
            $stmt-> execute([$create_email, $create_username]);
            $row = $stmt-> fetch(PDO::FETCH_ASSOC);
            $count = $stmt-> rowCount();
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
        if($count == 0) {
            try {
                $stmt = $pdo-> prepare('INSERT INTO `users` (`email`, `username`, `password`, `rank`) VALUES (?, ?, ?, ?);');
                $stmt-> execute([$create_email, $create_username, password_hash($create_password, PASSWORD_DEFAULT), $create_rank]);
            } catch (\PDOException $e) {
                throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
            }
            $old_umask = umask(0);
            mkdir('/srv/http/nozzato.com/files/' . $create_username, 0775);
            umask($old_umask);

            $_SESSION['msg'] = 'Account created';
            if($_SESSION['page'] == 'signup') {
                header('location:/index.php');
                exit;
            } else if($_SESSION['page'] == 'settings') {
                header('location:/user/settings.php');
                exit;
            }
        } else {
            if($row['email'] == $create_email) {
                $_SESSION['msg'] = 'Error: Email already in use';
                go_back();
            } else if($row['username'] == $create_username) {
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
