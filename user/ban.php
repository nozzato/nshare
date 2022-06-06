<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');
include_once('/srv/http/nozzato.com/database/connect.php');

if(isset($_POST['ban_btn'])) {
    if(!empty($_POST['ban_user']) && !empty($_POST['ban_reason'])) {
        $ban_user = $ban_reason = '';

        $ban_user = trim($_POST['ban_user']);
        $ban_reason = trim($_POST['ban_reason']);

        if($ban_user == $_SESSION['user']) {
            $_SESSION['msg'] = 'Error: You cannot ban yourself';
            go_back();
        }
        if(strlen($ban_reason) > 255) {
            $_SESSION['msg'] = 'Error: Reason must be 255 characters or less';
            go_back();
        }
        try {
            $stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` = ?;');
            $stmt-> execute([$ban_user]);
            $row = $stmt-> fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
        if(empty($row)) {
            $_SESSION['msg'] = 'Error: Invalid user';
            go_back();
        }
        if($row['rank'] == 'admin') {
            $_SESSION['msg'] = 'Error: You cannot ban admins';
            go_back();
        }
        try {
            $stmt = $pdo-> prepare('UPDATE `users` SET `ban_status` = ?, `ban_date` = CURDATE(), `ban_reason` = ? WHERE `user_id` = ?;');
            $stmt-> execute([1, $ban_reason, $ban_user]);
        } catch (\PDOException $e) {
            throw new \PDOException($e-> getMessage(), (int)$e-> getCode());
        }
        $_SESSION['msg'] = 'Account banned';
        go_back();
    } else {
        $_SESSION['msg'] = 'Error: Both fields are required';
        go_back();
    }
}
go_back();
?>
