<?php
session_start();

// if not logged in
if(!isset($_SESSION['user'])) {
    header('location:/index');
    exit;
}

// include functions
include_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/scripts.php');

// connect to database
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/connect.php');

$_SESSION['page'] = 'files';
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>Files: <?= $_SESSION['username'] . '/'; ?> - NShare</title>
<link rel='icon' type='image/gif' href='/assets/favicon.ico'>

<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/include.html'); ?>
<script type='text/javascript'>
function openFile(id) {
    window.location.href = '/files/edit?id=' + id;
}
function uploadFile() {
    document.getElementById('upload-form').submit();
    document.getElementById('upload-modal-content').style.display='none'
    document.getElementById('loader').classList.remove('w3-hide');
}
</script>

</head>
<?php if(!isset($_SESSION['msg'])) { ?>
<body>
<?php } else { ?>
<body onload='notify("<?= $_SESSION['msg'][0]; ?>", <?= $_SESSION['msg'][1]; ?>)'>
<?php unset($_SESSION['msg']); } ?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/header.php'); ?>

<div class='w3-row' id='content'>
    <div class='w3-container w3-col nz-black' style='width:200px;height:calc(100vh - 38.5px);font-size:18px;padding-top:8px'>
<!--
        <div style='padding:8px'>
            <tr><td><a href='/files/index' style='font-size:20px'>Folders</a></td></tr>
            <ul>
                <li>Documents</li>
                <li>Music</li>
                <li>Pictures</li>
                <li>Videos</li>
            </ul>
        </div>
-->
    </div>
    <div class='w3-container w3-rest' style='padding-top:8px'>
        <div class='w3-padding'>
            <tr><td><a href='/files/index' style='font-size:20px'><?= $_SESSION['username'] ?>/</a></td></tr>
        </div>
        <div class='w3-responsive'>
            <table class='nz-table'>
                <tr>
                    <th><input id='check-master' type='checkbox' onclick='selectAll()'></th>
                    <th class='nz-truncate'>Name <i class='fa fa-fw fa-caret-down'></i></th>

                <?php if(!$_SESSION['ban_status'] >= 1) { ?>
                    <th>Visibility</th>
                <?php } ?>

                    <th>Size</th>
                    <th class='nz-truncate'>Date Modified</th>
                </tr>

            <?php
                // select user's files and order alphabetically
                $stmt = $pdo-> prepare('SELECT * FROM `files` WHERE `user_id` = ? ORDER BY `filename` ASC;');
                $stmt-> execute([$_SESSION['user']]);
                $rows = $stmt-> fetchAll(PDO::FETCH_ASSOC);
                $count = $stmt-> rowCount();

                $db_file_size_total = 0;

                for($i = 0; $i <= $count - 1; $i++) {
                    $db_file_size_total += $rows[$i]['size'];
                }
            ?>

            <?php for($i = 0; $i < $count; $i++) {
                // select formatted upload date
                $stmt = $pdo-> prepare('SELECT DATE_FORMAT(`upload_date`, "%d-%m-%Y %h:%i:%s") FROM `files` WHERE `user_id` = ? AND `filename` = ?;');
                $stmt-> execute([$_SESSION['user'], $rows[$i]['filename']]);
                $rows[$i]['upload_date'] = $stmt-> fetchColumn();
                ?>

                <tr>
                    <td style='width:62px'><input form='delete-sel-form' type='checkbox' name='delete_sel_files[]' value='<?= $rows[$i]['file_id']; ?>' onclick='checkSelectAll()'></td>
                    <td class='w3-button' id='file-<?= $rows[$i]['file_id']; ?>' onclick='openFile(<?= $rows[$i]['file_id']; ?>)'><?= $rows[$i]['filename']; ?></td>

                <?php if(!$_SESSION['ban_status'] >= 1) { ?>
                    <td class='w3-button' id='<?= $rows[$i]['file_id']; ?>' onclick='changePrivacy("<?= $rows[$i]['file_id']; ?>")'><?= ucfirst($rows[$i]['privacy']); ?></td>
                <?php } ?>

                <td><?= human_filesize($rows[$i]['size']); ?></td>
                    <td class='nz-truncate'><?= $rows[$i]['upload_date']; ?></td>
                </tr>
            <?php } ?>

            </table>
        </div>
        <div class='w3-bar w3-bottom w3-padding-16'>
            <label class='w3-button w3-bar-item w3-green w3-round' onclick='openModal("upload")' style='margin-right:5px'>
                <i class='fa fa-fw fa-file-arrow-up'></i> Upload
            </label>
            <button class='w3-button w3-bar-item w3-red w3-round w3-disabled w3-hover-red' id='delete-btn' onclick='openModalDeleteSel()' style='margin-right:16px'>
                <i class='fa fa-fw fa-trash-can'></i> Delete
            </button>
            <div class='w3-bar-item' style='padding:4px 0 4px 0'>
                <span style='font-size:20px'><?= human_filesize($db_file_size_total); ?> / <b>5.00G</b></span>
            </div>
        </div>
    </div>
</div>
<div class='w3-modal' id='delete-modal'>
    <div class='w3-modal-content nz-dark w3-round w3-card-2'>
        <header class='w3-container nz-black nz-round-top'>
            <h2>Really delete?</h2>
        </header>
        <div class='w3-container'>
            <p class ='m-0 text-center' id='delete-modal-content'></p>
        </div>
        <footer class='w3-container w3-bar'>
            <form id='delete-sel-form' action='/files/delete.php' method='POST'>
                <button class='w3-button w3-bar-item w3-red w3-round w3-margin-bottom' type='submit' name='delete_sel_btn' style='margin-right:5px'>
                    <i class='fa fa-fw fa-trash-can'></i> Delete
                </button>
            </form>
            <button class='w3-button w3-bar-item w3-blue-grey w3-round w3-margin-bottom' onclick='document.getElementById("delete-modal").style.display="none"'>
                <i class='fa fa-fw fa-ban'></i> Cancel
            </button>
        </footer>
    </div>
</div>
<div class='w3-modal' id='upload-modal'>
    <div class='w3-modal-content nz-dark w3-round w3-card-2' id='upload-modal-content'>
        <header class='w3-container nz-black nz-round-top'>
            <h2>Upload</h2>
        </header>
        <div class='w3-container'>
        </div>
        <footer class='w3-bar w3-container w3-padding-16'>
            <form id='upload-form' action='/files/upload.php' method='POST' enctype='multipart/form-data'>
                <input class='w3-radio' id='upload-private' type='radio' value='private' name='upload_privacy' checked>
                <label for='upload-private' style='margin-right:5px'>Private</label>
                <input class='w3-radio' id='upload-public' type='radio' value='public' name='upload_privacy'>
                <label for='upload-public'>Public</label>
                <input class='w3-hide' id='upload-file' type='file' name='upload_file[]' onchange='uploadFile()' multiple required>
                <label class='w3-button w3-bar-item w3-green w3-round' for='upload-file' style='margin-right:5px'>
                    <i class='fa fa-fw fa-file-arrow-up'></i> Browse
                </label>
                <button class='w3-button w3-bar-item w3-blue-grey w3-round' onclick='document.getElementById("upload-modal").style.display="none"' style='margin-right:16px'>
                    <i class='fa fa-fw fa-ban'></i> Cancel
                </button>
            </form>
        </footer>
    </div>
</div>
<div class='w3-hide' id='loader'></div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/notification.html'); ?>

</body>
</html>
