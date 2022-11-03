<?php
session_start();

// if not logged in
if(!isset($_SESSION['user'])) {
    header('location:/index');
    exit;
}
// if banned
if($_SESSION['ban_status'] >= 1) {
    header('location:/status/banned');
    exit;
}

// connect to database
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/connect.php');

// select users and order alphabetically
$stmt = $pdo-> prepare('SELECT * FROM `users` WHERE `user_id` != ? ORDER BY length(username), username;');
$stmt-> execute([$_SESSION['user']]);
$rows = $stmt-> fetchAll(PDO::FETCH_ASSOC);
$count = $stmt-> rowCount();

// select friends array
$stmt = $pdo-> prepare('SELECT `friends` INTO @friends FROM `friends` WHERE `user_id` = ?;');
$stmt-> execute([$_SESSION['user']]);

$stmt = $pdo-> prepare('SELECT @friends "array" ;');
$stmt-> execute();
$friends = $stmt-> fetch(PDO::FETCH_ASSOC);
$friends = json_decode($friends['array']);

// select length of friends array
$stmt = $pdo-> prepare('SELECT JSON_LENGTH(@friends) "length";');
$stmt-> execute();
$tmp = $stmt-> fetch(PDO::FETCH_ASSOC);
$friends_length = $tmp['length'];

$_SESSION['page'] = 'users';
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>Users: Friends - NShare</title>
<link rel='icon' type='image/gif' href='/assets/favicon.gif'>

<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/include.html'); ?>
<script type='text/javascript'>
function openProfile(id) {
    window.location.href = '/user/index?id=' + id;
}
</script>

</head>
<?php if(!isset($_SESSION['msg'])) { ?>
<body>
<?php } else { ?>
<body onload='notify("<?= $_SESSION['msg'][0]; ?>", <?= $_SESSION['msg'][1]; ?>)'>
<?php unset($_SESSION['msg']); } ?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/header.php'); ?>

<div class='w3-container w3-padding-16 w3-center' id='content'>
    <div class='w3-bar nz-black w3-round nz-page' style='margin-bottom:10px'>
        <button class='w3-bar-item w3-button page-button w3-dark-gray' id='friendsBtn' onclick='openPage("friends", "users")' style='width:100px'>Friends</button>
        <button class='w3-bar-item w3-button page-button' id='exploreBtn' onclick='openPage("explore", "users")' style='width:100px'>Explore</button>
    </div>
    <div class='page' id='friends'>
        <div class='w3-round w3-card-2 nz-page'>
            <div class='w3-container nz-black nz-round-top'>
                <h2>Friends</h2>
            </div>
            <div class='w3-container w3-padding-16'>
                <div class='w3-responsive'>
                    <table class='nz-table'>
                        <tr>
                            <th class='nz-truncate'>Username <i class='fa fa-fw fa-caret-down'></i></th>
                            <th class='nz-truncate'>User ID</th>
                            <th class='nz-truncate'>Friend Status</th>
                        </tr>

                <?php for($i = 0; $i < $count; $i++) { ?>
                    <?php if(in_array($rows[$i]['user_id'], $friends)) { ?>
                        <tr>
                            <td class='w3-button' onclick='openProfile(<?= $rows[$i]['user_id']; ?>)'><?= $rows[$i]['username']; ?></td>
                            <td><?= $rows[$i]['user_id']; ?></td>
                            <td class='w3-button w3-button w3-hover-red remove-friend-button' id='<?= $rows[$i]['user_id']; ?>' onclick='removeFriend(<?= $rows[$i]['user_id']; ?>)'>
                                <span>Friends</span>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class='page' id='explore' style='display:none'>
        <div class='w3-round w3-card-2 nz-page'>
            <div class='w3-container nz-black nz-round-top'>
                <h2>Explore</h2>
            </div>
            <div class='w3-container w3-padding-16'>
                <div class='w3-responsive'>
                    <table class='nz-table'>
                        <tr>
                            <th class='nz-truncate'>Username <i class='fa fa-fw fa-caret-down'></i></th>
                            <th class='nz-truncate'>User ID</th>
                            <th class='nz-truncate'>Friend Status</th>
                        </tr>

                    <?php for($i = 0; $i < $count; $i++) { ?>
                        <tr>
                            <td class='w3-button' onclick='openProfile(<?= $rows[$i]['user_id']; ?>)'><?= $rows[$i]['username']; ?></td>
                            <td><?= $rows[$i]['user_id']; ?></td>
                            
                        <?php if(in_array($rows[$i]['user_id'], $friends)) { ?>
                            <td id='<?= $rows[$i]['user_id']; ?>'>
                        <?php } else { ?>
                            <td class='w3-button w3-button w3-hover-green add-friend-button' id='<?= $rows[$i]['user_id']; ?>' onclick='addFriend(<?= $rows[$i]['user_id']; ?>)'>
                        <?php } ?>

                        <?php if(in_array($rows[$i]['user_id'], $friends)) { ?>
                            Friends
                        <?php } else { ?>
                            <span>Not Friends</span>
                        <?php } ?>

                            </td>
                        </tr>
                    <?php } ?>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/notification.html'); ?>

</body>
</html>
