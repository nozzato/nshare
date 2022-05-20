<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(isset($_POST['close_btn'])) {
    if(!empty($_POST['close_username']) && !empty($_POST['close_password'])) {
        $close_username = $close_password = '';

        $close_username = trim($_POST['close_username']);
        $close_password = trim($_POST['close_password']);

        try {
            $stmt = $pdo-> prepare("SELECT * FROM `users` WHERE `username` = ?;");
            $stmt-> execute([$close_username]);
            $row = $stmt-> fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
        if(password_verify($close_password, $row['password']) && $_SESSION['rank'] == 'admin') {
            try {
                $stmt = $pdo-> prepare("DELETE FROM `users` WHERE `username` = ?;");
                $stmt-> execute([$close_username]);
            } catch (\PDOException $e) {
                throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
            }
            remove_dir('/srv/http/nozzato.com/files/' . $close_username);

            if($close_username == $_SESSION['username']) {
                $_SESSION['close_logout'] = 1;
                header('location:logout.php');
                exit;
            } else {
                $_SESSION['msg'] = "Account deleted";
                go_back();
            }
        } else if(password_verify($close_password, $row['password']) && $_SESSION['rank'] == 'member') {
            if($close_username == $_SESSION['username']) {
                try {
                    $stmt = $pdo-> prepare("DELETE FROM `users` WHERE `username` = ?;");
                    $stmt-> execute([$close_username]);
                } catch (\PDOException $e) {
                    throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
                }
                remove_dir('/srv/http/nozzato.com/files/' . $close_username);

                $_SESSION['close_logout'] = 1;
                header('location:logout.php');
                exit;
            } else {
                $_SESSION['msg'] = "Error: Invalid username or password";
                go_back();
            }
        } else {
            $_SESSION['msg'] = "Error: Invalid username or password";
            go_back();
        }
    } else {
        $_SESSION['msg'] = "Error: Both fields are required";
        go_back();
    }
}
go_back();
?>
