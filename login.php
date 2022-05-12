<?php
session_start();
include_once('functions.php');

if (isset($_POST['login_btn'])) {
    if(!empty($_POST['username']) && !empty($_POST['password'])) {
        try {
            include_once('connect.php');

            $username = $password = $admin = '';

            function strip($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            $username = strip($_POST['username']);
            $password = strip($_POST['password']);

            $stmt = $pdo-> prepare("SELECT * FROM `users` WHERE `username` = ?;");
            $stmt-> execute([$username]);
            $row = $stmt-> fetch(PDO::FETCH_ASSOC);

            if(password_verify($password, $row['password'])) {
                $_SESSION['user_id']        = $row['user_id'];
                $_SESSION['username']       = $row['username'];
                $_SESSION['admin']          = $row['admin'];

                $_SESSION['msg'] = "Logged in";
            } else {
                $_SESSION['msg'] = "Error: Invalid username or password";
            }
            page_back();
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
    } else {
        $_SESSION['msg'] = "Error: Both fields are required";
        page_back();
    }
} else {
    page_back();
}
?>