<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(isset($_POST['close_btn'])) {
    if(!empty($_POST['close_username']) && !empty($_POST['close_password'])) {
        $username = $password = '';

        $username = trim($_POST['close_username']);
        $password = trim($_POST['close_password']);

        try {
            $stmt = $pdo-> prepare("SELECT * FROM `users` WHERE `username` = ?;");
            $stmt-> execute([$username]);
            $row = $stmt-> fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
        if(password_verify($password, $row['password']) && $_SESSION['rank'] == 'admin') {
            try {
                $stmt = $pdo-> prepare("DELETE FROM `users` WHERE `username` = ?;");
                $stmt-> execute([$username]);
            } catch (\PDOException $e) {
                throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
            }
            remove_dir('/srv/http/nozzato.com/files/' . $username);

            if($username == $_SESSION['username']) {
                $_SESSION['delete_logout'] = 1;
                header('location:logout.php');
                exit;
            } else {
                $_SESSION['msg'] = "Account deleted";
                go_back();
            }
        } else if(password_verify($password, $row['password']) && $_SESSION['rank'] == 'member') {
            if($username == $_SESSION['username']) {
                try {
                    $stmt = $pdo-> prepare("DELETE FROM `users` WHERE `username` = ?;");
                    $stmt-> execute([$username]);
                } catch (\PDOException $e) {
                    throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
                }
                remove_dir('/srv/http/nozzato.com/files/' . $username);

                $_SESSION['delete_logout'] = 1;
                header('location:logout.php');
                exit;
            } else {
                $_SESSION['msg'] = "Error: Invalid username or password";
                go_back();
            }
        }
    } else {
        $_SESSION['msg'] = "Error: Both fields are required";
        go_back();
    }
}
go_back();
?>
