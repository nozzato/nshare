<?php
session_start();

// include functions
include_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/scripts.php');

// if unban button clicked
if(isset($_POST['unban_btn'])) {
    // if both fields are not empty
    if(!empty($_POST['unban_user']) && !empty($_POST['unban_reason'])) {
        // connect to database
        include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/connect.php');

        // set unban variables
        $unban_user = trim($_POST['unban_user']);
        $unban_reason = trim($_POST['unban_reason']);

        // select user data
        $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
        $stmt-> execute([$unban_user]);
        $row = $stmt-> fetch(PDO::FETCH_ASSOC);

        // validate unban
        if(strlen($unban_reason) > 255) {
            $_SESSION['msg'] = ['Error: Reason must be 255 characters or less', 'true'];
            go_back();
        }
        if(empty($row)) {
            $_SESSION['msg'] = ['Error: Invalid user', 'true'];
            go_back();
        }
        if($unban_user == $_SESSION['user']) {
            $_SESSION['msg'] = ['Error: You cannot unban yourself', 'true'];
            go_back();
        }
        if($row['rank'] == 'admin') {
            $_SESSION['msg'] = ['Error: You cannot unban admins', 'true'];
            go_back();
        }
        if($row['ban_status'] == 0) {
            $_SESSION['msg'] = ['Error: Account already unbanned', 'true'];
            go_back();
        }

        // insert unban into database
        $stmt = $pdo-> prepare('UPDATE `users` SET `ban_status` = ?, `ban_judge` = NULL, `ban_date` = NULL, `ban_reason` = NULL WHERE `user_id` = ?;');
        $stmt-> execute([0, $unban_user]);

        $_SESSION['msg'] = ['Account unbanned', 'false'];
        go_back();
    // else both fields are empty
    } else {
        $_SESSION['msg'] = ['Error: Both fields are required', 'true'];
        go_back();
    }
}

go_back();
?>
