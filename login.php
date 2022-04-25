<?php
session_start();

if (isset($_POST['login_btn'], $_POST['username'], $_POST['password'])) {
    try {
        require_once 'connect.php';

        $username = $password = '';

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
            $_SESSION['user_id']  = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['admin']    = $row['admin'];

            //$_SESSION['sess_msg'] = "Access granted";
            if($_SESSION['page'] == 'index') {
                header('location:index.php');
                exit;
            } else if($_SESSION['page'] == 'public') {
                header('location:files-public.php');
                exit;
            } else {
                header('location:index.php');
                exit;
            }
        }
        else {
            $_SESSION['msg'] = "Error: Invalid username or password";
            header('location:index.php');
            exit;
        }
    } catch (\PDOException $e) {
        throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
    }
} else {
    header('location:index.php');
    exit;
}
?>