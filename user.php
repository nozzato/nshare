<?php
session_start();
include_once('functions.php');

if(isset($_POST['user_signup_btn']) || isset($_POST['user_delete_btn'])) {
    if(!empty($_POST['username']) && !empty($_POST['password'])) {
        try {
            include_once('connect.php');

            $username = $password = $admin = '';

            function strip($in) {
                $out = trim($in);
                $out = stripslashes($in);
                $out = htmlspecialchars($in);
                return $out;
            }
            $username = strip($_POST['username']);
            $password = strip($_POST['password']);
            $admin    = $_POST['admin'];

            if(strlen($username) > 50) {
                $_SESSION['msg'] = "Error: Username must be 50 characters or less";
                header('location:account.php');
                exit;
            }
            if(strlen($password) > 72) {
                $_SESSION['msg'] = "Error: Password must be 72 characters or less";
                header('location:account.php');
                exit;
            }
            $stmt = $pdo-> prepare("SELECT * FROM `users` WHERE `username` = ?;");
            $stmt-> execute([$username]);

            if(isset($_POST['user_signup_btn'])) {
                $count = $stmt-> rowCount();

                if($count == 0) {
                    $stmt = $pdo-> prepare("INSERT INTO `users` (`username`, `password`, `admin`) VALUES (?, ?, ?);");
                    $stmt-> execute([$username, password_hash($password, PASSWORD_DEFAULT), $admin]);

                    $old_umask = umask(0);
                    mkdir('files/' . $username, 0775);
                    umask($old_umask);

                    $_SESSION['msg'] = "Account created";
                    header('location:account.php');
                    exit;
                }
                else {
                    $_SESSION['msg'] = "Error: Account already exists";
                    header('location:account.php');
                    exit;
                }
            } else if(isset($_POST['user_delete_btn'])) {
                if(strlen($password) > 72) {
                    $_SESSION['msg'] = "Error: Password must be 72 characters or less";
                    header('location:account.php');
                    exit;
                }
                $row = $stmt-> fetch(PDO::FETCH_ASSOC);

                function remove_dir($dir) {
                    if (!file_exists($dir)) {
                        return true;
                    }
                    if (!is_dir($dir)) {
                        return unlink($dir);
                    }
                    foreach (scandir($dir) as $item) {
                        if ($item == '.' || $item == '..') {
                            continue;
                        }
                        if (!remove_dir($dir . DIRECTORY_SEPARATOR . $item)) {
                            return false;
                        }
                    }
                    return rmdir($dir);
                }
                if(password_verify($password, $row['password']) && $_SESSION['admin'] == 1) {
                    $stmt = $pdo-> prepare("DELETE FROM `users` WHERE `username` = ?;");
                    $stmt-> execute([$username]);

                    remove_dir('files/' . $username);

                    if($username == $_SESSION['username']) {
                        $_SESSION['delete_logout'] = 1;
                        header('location:logout.php');
                        exit;
                    } else {
                        $_SESSION['msg'] = "Account deleted";
                        header('location:account.php');
                        exit;
                    }
                } else if(password_verify($password, $row['password']) && $_SESSION['admin'] == 0) {
                    if($username == $_SESSION['username']) {
                        $stmt = $pdo-> prepare("DELETE FROM `users` WHERE `username` = ?;");
                        $stmt-> execute([$username]);
                        
                        remove_dir('files/' . $username);

                        $_SESSION['delete_logout'] = 1;
                        header('location:logout.php');
                        exit;
                    } else {
                        $_SESSION['msg'] = "Error: Invalid username or password";
                        header('location:account.php');
                        exit;
                    }
                } else {
                    $_SESSION['msg'] = "Error: Invalid username or password";
                    header('location:account.php');
                    exit;
                }
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