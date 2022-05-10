<?php
session_start();
include_once('functions.php');

if(isset($_POST['account_change_password_btn'])) {
    if(!empty($_POST['old_password']) && !empty($_POST['new_password'])) {
        try {
            include_once('connect.php');

            $username = $password = '';

            function strip($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            $old_password = strip($_POST['old_password']);
            $new_password = strip($_POST['new_password']);

            if(strlen($new_password) > 1024) {
                $_SESSION['msg'] = "Error: Do you really need a password that long?";
                header('location:account.php');
                exit;
            }
            if($old_password == $new_password) {
                $_SESSION['msg'] = "Error: Password unchanged";
                header('location:account.php');
                exit;
            }
            $stmt = $pdo-> prepare("SELECT * FROM `users` WHERE `user_id` = ?;");
            $stmt-> execute([$_SESSION['user_id']]);
            $row = $stmt-> fetch(PDO::FETCH_ASSOC);

            $password = $row['password'];

            if(password_verify($old_password, $row['password'])) {
                $stmt = $pdo-> prepare("UPDATE `users` SET `password` = ? WHERE `user_id` = ?;");
                $stmt-> execute([password_hash($new_password, PASSWORD_DEFAULT), $_SESSION['user_id']]);

                $_SESSION['msg'] = "Password changed";
                header('location:account.php');
                exit;
            } else {
                $_SESSION['msg'] = "Error: Invalid username or password";
                header('location:account.php');
                exit;
            }
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
    } else {
        $_SESSION['msg'] = "Error: Both fields are required";
        header('location:account.php');
        exit;
    }
} else {
    page_back();
}
?>