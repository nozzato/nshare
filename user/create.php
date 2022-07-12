<?php
session_start();

// include functions
include_once('/srv/http/nozzato.com/scripts/scripts.php');

if(isset($_POST['create_btn'])) {
    // verify captcha
    h_captcha($_POST['h-captcha-response']);

    // if all fields are not empty
    if(!empty($_POST['create_username']) && !empty($_POST['create_password']) && !empty($_POST['create_email'])) {
        // connect to database
        include_once('/srv/http/nozzato.com/admin/connect.php');

        // set create variables
        $create_email    = trim($_POST['create_email']);
        $create_username = trim($_POST['create_username']);
        $create_password = trim($_POST['create_password']);

        // if rank is specified
        if(isset($_POST['create_rank'])) {
            $create_rank = $_POST['create_rank'];
        // else no rank is specified
        } else {
            $create_rank = 'member';
        }
        
        // validate account
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

        // check if user exists
        $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `email` = ? OR `username` = ?;');
        $stmt-> execute([$create_email, $create_username]);
        $row = $stmt-> fetch(PDO::FETCH_ASSOC);
        $count = $stmt-> rowCount();

        // if user does not exist
        if($count == 0) {
            // insert user into database
            $stmt = $pdo-> prepare('INSERT INTO `users` (`email`, `username`, `password`, `rank`, `ban_status`, `creation_date`) VALUES (?, ?, ?, ?, 0, NOW());');
            $stmt-> execute([$create_email, $create_username, password_hash($create_password, PASSWORD_DEFAULT), $create_rank]);

            $stmt = $pdo-> prepare('SELECT `user_id` FROM `users` WHERE `username` = ?');
            $stmt-> execute([$create_username]);
            $create_user = $stmt-> fetchColumn(PDO::FETCH_ASSOC);

            $stmt = $pdo-> prepare('INSERT INTO `friends` (`user_id`, `friends`) VALUES (?, ?);');
            $stmt-> execute([$create_user, '[0]']);

            // create directory
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
        // else user exists
        } else {
            if($row['email'] == $create_email) {
                $_SESSION['msg'] = 'Error: Email already in use';
                go_back();
            } else if($row['username'] == $create_username) {
                $_SESSION['msg'] = 'Error: Username already in use';
                go_back();
            }
        }
    // else all fields are empty
    } else {
        $_SESSION['msg'] = 'Error: All fields are required';
        go_back();
    }
}

go_back();
?>