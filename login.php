<?php
session_start();

if (isset($_POST['login_btn'])) {
    if(!empty($_POST['username']) && !empty($_POST['password'])) {
        try {
            require_once 'connect.php';

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
                $_SESSION['admin_const']    = $row['admin'];

                $_SESSION['msg'] = "Access granted";
            } else {
                $_SESSION['msg'] = "Error: Invalid username or password";
            }
            if($_SESSION['page'] == 'home') {
                header('location:index.php');
            } else if($_SESSION['page'] == 'account') {
                header('location:account.php');
            } else if($_SESSION['page'] == 'public') {
                header('location:files-public.php');
            } else if($_SESSION['page'] == 'private') {
                header('location:files-private.php');
            } else {
                header('location:index.php');
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
    if($_SESSION['page'] == 'home') {
        header('location:index.php');
    } else if($_SESSION['page'] == 'account') {
        header('location:account.php');
    } else if($_SESSION['page'] == 'public') {
        header('location:files-public.php');
    } else if($_SESSION['page'] == 'private') {
        header('location:files-private.php');
    } else {
        header('location:index.php');
    }
}
?>