<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(isset($_POST['unban_btn'])) {
    if(!empty($_POST['unban_user']) && !empty($_POST['unban_reason'])) {
        $unban_user = $unban_reason = '';

        $unban_user = trim($_POST['unban_user']);
        $unban_reason = trim($_POST['unban_reason']);

        if($unban_user == $_SESSION['user']) {
            $_SESSION['msg'] = 'Error: You cannot unban yourself';
            go_back();
        }
        if(strlen($unban_reason) > 255) {
            $_SESSION['msg'] = 'Error: Reason must be 255 characters or less';
            go_back();
        }
        try {
            $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
            $stmt-> execute([$unban_user]);
            $row = $stmt-> fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
        if(empty($row)) {
            $_SESSION['msg'] = 'Error: Invalid user';
            go_back();
        }
        if($row['ban_status'] == 0) {
            $_SESSION['msg'] = 'Error: Account already unbanned';
            go_back();
        }
        if($row['rank'] == 'admin') {
            $_SESSION['msg'] = 'Error: You cannot unban admins';
            go_back();
        }
        try {
            $stmt = $pdo-> prepare('UPDATE `users` SET `ban_status` = ?, `ban_judge` = NULL, `ban_date` = NULL, `ban_reason` = NULL WHERE `user_id` = ?;');
            $stmt-> execute([0, $unban_user]);
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
        $_SESSION['msg'] = 'Account unbanned';
        go_back();
    } else {
        $_SESSION['msg'] = 'Error: Both fields are required';
        go_back();
    }
}
go_back();
?>
