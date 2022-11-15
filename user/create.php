<?php
session_start();

// include functions
include_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/scripts.php');

require($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
use EmailChecker\EmailChecker;

if(isset($_POST['create_btn'])) {
    // if not admin
    if($_SESSION['role'] != 2) {
        // verify captcha
        h_captcha($_POST['h-captcha-response']);
    }

    // if all fields are not empty
    if(!empty($_POST['create_username']) && !empty($_POST['create_password']) && !empty($_POST['create_email'])) {
        // connect to database
        include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/connect.php');

        // set create variables
        $create_email    = trim($_POST['create_email']);
        $create_username = trim($_POST['create_username']);
        $create_password = trim($_POST['create_password']);

        // if role is specified
        if(isset($_POST['create_role'])) {
            $create_role = $_POST['create_role'];
        // else no role is specified
        } else {
            $create_role = 0;
        }
        
        // validate account
        if(strlen($create_email) > 255) {
            $_SESSION['msg'] = ['Error: Email must be 255 characters or less', 'true'];
            go_back();
        }
        if(!filter_var($create_email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['msg'] = ['Error: Invalid email format', 'true'];
            go_back();
        }
        $checker = new EmailChecker();
        if(!$checker->isValid($create_email)) {
            $_SESSION['msg'] = ['Error: Invalid email domain', 'true'];
            go_back();
        }
        if(strlen($create_username) > 50) {
            $_SESSION['msg'] = ['Error: Username must be 50 characters or less', 'true'];
            go_back();
        }
        if(strlen($create_password) < 8) {
            $_SESSION['msg'] = ['Error: Password must be 8 characters or more', 'true'];
            go_back();
        }
        if(strlen($create_password) > 72) {
            $_SESSION['msg'] = ['Error: Password must be 72 characters or less', 'true'];
            go_back();
        }

        // check if user exists
        $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `email` = ? OR `username` = ?;');
        $stmt-> execute([$create_email, $create_username]);
        $row = $stmt-> fetch(PDO::FETCH_ASSOC);
        $count = $stmt-> rowCount();

        // if user does not exist
        if($count == 0) {
            // insert user into database
            $stmt = $pdo-> prepare('INSERT INTO `users` (`email`, `username`, `password`, `role`, `ban_status`, `creation_date`) VALUES (?, ?, ?, ?, 0, NOW());');
            $stmt-> execute([$create_email, $create_username, password_hash($create_password, PASSWORD_DEFAULT), $create_role]);

            $stmt = $pdo-> prepare('SELECT `user_id` FROM `users` WHERE `username` = ?');
            $stmt-> execute([$create_username]);
            $create_user = $stmt-> fetch(PDO::FETCH_ASSOC);
            $create_user = $create_user['user_id'];

            $stmt = $pdo-> prepare('INSERT INTO `friends` (`user_id`, `friends`) VALUES (?, ?);');
            $stmt-> execute([$create_user, '[0]']);

            // create user directory
            $old_umask = umask(0);
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/data/' . $create_user, 0775);
            umask($old_umask);

            $_SESSION['msg'] = ['Account created', 'false'];

            if($_SESSION['page'] == 'signup') {
                header('location:/index.php');
                exit;
            } else if($_SESSION['page'] == 'settings') {
                header('location:/user/settings.php');
                exit;
            }
        // else user exists
        } else {
            if($row['email'] == $create_email) {
                $_SESSION['msg'] = ['Error: Email already in use', 'true'];
                go_back();
            } else if($row['username'] == $create_username) {
                $_SESSION['msg'] = ['Error: Username already in use', 'true'];
                go_back();
            }
        }
    // else all fields are empty
    } else {
        $_SESSION['msg'] = ['Error: All fields are required', 'true'];
        go_back();
    }
}

go_back();
?>
