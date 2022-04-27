<?php
session_start();

if(isset($_POST['account_signup_btn']) || isset($_POST['account_delete_btn'])) {
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
            $admin    = $_POST['admin'];

            if(strlen($username) > 50) {
                $_SESSION['msg'] = "Error: Username must be 50 characters or less";
                header('location:account.php');
                exit;
            }
            if(strlen($password) > 1024) {
                $_SESSION['msg'] = "Error: Do you really need a password that long?";
                header('location:account.php');
                exit;
            }
            $stmt = $pdo-> prepare("SELECT * FROM `users` WHERE `username` = ?;");
            $stmt-> execute([$username]);

            if(isset($_POST['account_signup_btn'])) {
                $count = $stmt-> rowCount();

                if($count == 0) {
                    $stmt = $pdo-> prepare("INSERT INTO `users` (`username`, `password`, `admin`) VALUES (?, ?, ?);");
                    $stmt-> execute([$username, password_hash($password, PASSWORD_DEFAULT), $admin]);

                    $oldUmask = umask(0);
                    mkdir('files/' . $username, 0777);
                    umask($oldUmask);

                    $_SESSION['msg'] = "Account created";
                    header('location:account.php');
                    exit;
                }
                else {
                    $_SESSION['msg'] = "Error: Account already exists";
                    header('location:account.php');
                    exit;
                }
            } else if(isset($_POST['account_delete_btn'])) {
                $row = $stmt-> fetch(PDO::FETCH_ASSOC);

                function deleteDirectory($dir) {
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
                        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                            return false;
                        }
                    }
                    return rmdir($dir);
                }
                if(password_verify($password, $row['password']) && $_SESSION['admin'] == 1) {
                    $stmt = $pdo-> prepare("DELETE FROM `users` WHERE `username` = ?;");
                    $stmt-> execute([$username]);

                    deleteDirectory('files/' . $username);

                    if($username == $_SESSION['username']) {
                        $_SESSION['msg'] = "Account deleted";
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
                        
                        deleteDirectory('files/' . $username);

                        $_SESSION['msg'] = "Account deleted";
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
    if($_SESSION['page'] == 'home') {
        header('location:index.php');
        exit;
    } else if($_SESSION['page'] == 'account') {
        header('location:account.php');
        exit;
    } else if($_SESSION['page'] == 'public') {
        header('location:files-public.php');
        exit;
    } else if($_SESSION['page'] == 'private') {
        header('location:files-private.php');
        exit;
    } else {
        header('location:index.php');
        exit;
    }
}
?>