<?php
session_start();

// include functions
include_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/scripts.php');

// if ban button clicked
if(isset($_POST['ban_btn'])) {
    // if both fields are not empty
    if(!empty($_POST['ban_user']) && !empty($_POST['ban_reason'])) {
        // connect to database
        include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/connect.php');

        // set ban variables
        $ban_user = trim($_POST['ban_user']);
        $ban_reason = trim($_POST['ban_reason']);

        // select user data
        $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
        $stmt-> execute([$ban_user]);
        $row = $stmt-> fetch(PDO::FETCH_ASSOC);

        // validate ban
        if(strlen($ban_reason) > 255) {
            $_SESSION['msg'] = ['Error: Reason must be 255 characters or less', 'true'];
            go_back();
        }
        if(empty($row)) {
            $_SESSION['msg'] = ['Error: Invalid user', 'true'];
            go_back();
        }
        if($ban_user == $_SESSION['user']) {
            $_SESSION['msg'] = ['Error: You cannot ban yourself', 'true'];
            go_back();
        }
        if($row['role'] == 'admin') {
            $_SESSION['msg'] = ['Error: You cannot ban admins', 'true'];
            $_SESSION['msg_urgent'] = 'true';
            go_back();
        }
        if($row['ban_status'] == 1) {
            $_SESSION['msg'] = ['Error: Account already banned', 'true'];
            go_back();
        }

        // insert ban into database
        $stmt = $pdo-> prepare('UPDATE `users` SET `ban_status` = ?, `ban_judge` = ?, `ban_date` = NOW(), `ban_reason` = ? WHERE `user_id` = ?;');
        $stmt-> execute([1, $_SESSION['user'], $ban_reason, $ban_user]);

        $_SESSION['msg'] = ['Account banned', 'false'];
        go_back();
    // else both fields are empty
    } else {
        $_SESSION['msg'] = ['Error: Both fields are required', 'true'];
        go_back();
    }
}

go_back();
?>
