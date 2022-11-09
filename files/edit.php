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

// select file from GET id
$stmt = $pdo -> prepare('SELECT * FROM `files` WHERE `file_id` = ?;');
$stmt -> execute([$_GET['id']]);
$row = $stmt -> fetch(PDO::FETCH_ASSOC);

// select formatted upload date
$stmt = $pdo-> prepare('SELECT DATE_FORMAT(`upload_date`, "%d-%m-%Y") FROM `files` WHERE `file_id` = ?;');
$stmt-> execute([$_GET['id']]);
$row['upload_date'] = $stmt-> fetchColumn();

// if selected file is private and not owned by user
if($row['privacy'] == 'private' && $row['user_id'] != $_SESSION['user']) {
    $_SESSION['msg'] = ['Error: You do not have permission view this file', 'true'];
    header('location:/files/index');
    exit;
}
// else if selected file is public and not owned by user
else if($row['privacy'] == 'public' && $row['user_id'] != $_SESSION['user']) {
    // set read only permissions
    $perms = 'ro';
}
// else selected file is owned by user
else {
    // set read write permissions
    $perms = 'rw';
}

// set file variables
$file_name = $row['filename'];

// if file is owned by user
if($row['user_id']  == $_SESSION['user']) {
    $user             = $_SESSION['user'];
    $username         = $_SESSION['username'];
    $file_path_server = $_SERVER['DOCUMENT_ROOT'] . '/data/' . $user . '/';
    $file_path        = '/data/' . $user . '/';
}
// else file is not owned by user
else {
    // select username of file owner
    $stmt = $pdo -> prepare('SELECT `username` FROM `users` WHERE `user_id` = ?;');
    $stmt -> execute([$row['user_id']]);
    $username = $stmt -> fetchColumn();

    $file_path_server = $_SERVER['DOCUMENT_ROOT'] . '/data/' . $user . '/';
    $file_path        = '/data/' . $user . '/';
}

$file        = $file_path . $file_name;
$file_server = $file_path_server . $file_name;
$file_info   = new finfo(FILEINFO_MIME);
$file_mime   = $file_info -> buffer(file_get_contents($file_server));
$file_modal  = '"' . $file_name . '"';

// if GET query invalid or no file selected
if(!isset($_GET['id']) || empty($row['filename'])) {
    $_SESSION['msg'] = ['Error: Invalid file', 'true'];
    header('location:/files/index');
    exit;
}

// set filetype
if(substr($file_mime, 0, 4) == 'text' || str_contains($file_mime, 'empty')) {
    $file_type = 'text';
}
else if(substr($file_mime, 0, 5) == 'image') {
    $file_type = 'image';
}
else if(substr($file_mime, 0, 5) == 'video') {
    $file_type = 'video';
}
else if(substr($file_mime, 0, 5) == 'audio') {
    $file_type = 'audio';
}
// else filetype is unknown
else {
    header('location:' . $file);
    exit;
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>Files: <?= $username . '/' . $file_name; ?> - NShare</title>
<link rel='icon' type='image/gif' href='/assets/favicon.gif'>

<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/include.html'); ?>

</head>
<?php if(!isset($_SESSION['msg'])) { ?>
<body>
<?php } else { ?>
<body onload='notify("<?= $_SESSION['msg'][0]; ?>", <?= $_SESSION['msg'][1]; ?>)'>
<?php unset($_SESSION['msg']); } ?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/header.php'); ?>

<div class='w3-container w3-padding-16' id='content'>
    <div class='w3-round w3-card-2 nz-page'>
        <div class='w3-container nz-black nz-round-top'>
            <h2 class='nz-truncate'><?= $username . '/' . $file_name; ?></h2>
        </div>
        <div class='w3-container w3-padding-16'>

        <?php if($file_type == 'text' || $file_type == 'empty') { ?>
            <form id='upload-form' action='/files/upload.php' method='POST'>
                <textarea class='w3-input w3-monospace nz-black w3-border-0 w3-round' rows='20' name='upload_content' autofocus <?php if($_SESSION['ban_status'] >= 1 || $perms == 'ro') { ?>readonly<?php } ?>><?php readfile($file_server); ?></textarea>
            </form>
        <?php } else if($file_type == 'image') { ?>
            <div class='w3-center'>
                <img class='w3-hide-medium w3-hide-small' src='<?= $file; ?>' style='max-width:882px; max-height:450px'>
                <img class='w3-hide-large w3-hide-small' src='<?= $file; ?>' style='max-width:730px; max-height:450px'>
                <img class='w3-hide-large w3-hide-medium' src='<?= $file; ?>' style='max-width:280px; max-height:450px'>
            </div>
        <?php } else if($file_type == 'video') { ?>
            <div class='w3-center'>
                <video class='w3-hide-medium w3-hide-small' controls style='max-width:882px; max-height:450px'>
                    <source src='<?= $file; ?>'>
                </video>
                <video class='w3-hide-large w3-hide-small' controls style='max-width:730px; max-height:450px'>
                    <source src='<?= $file; ?>'>
                </video>
                <video class='w3-hide-large w3-hide-medium' controls style='max-width:280px; max-height:450px'>
                    <source src='<?= $file; ?>'>
                </video>
            </div>
        <?php } else if($file_type == 'audio') { ?>
            <div class='w3-center'>
                <audio class='w3-block' controls>
                    <source src='<?= $file; ?>'>
                </audio>
            </div>
        <?php } ?>

            <p></p>
            <div class='w3-responsive'>
                <table class='nz-table'>
                    <tr>
                        <td><b>Name</b><br><?= $row['filename']; ?></td>
                        <td class='nz-truncate'><b>File ID</b><br><?= $row['file_id']; ?></td>
                        <td><b>Size</b><br><?= human_filesize($row['size']); ?></td>
                        <td><b>Date</b><br><?= $row['upload_date']; ?></td>

                    <?php if(!$_SESSION['ban_status'] >= 1 || $row['user_id'] != $_SESSION['user']) { ?>
                        <td><b>Privacy</b><br><?= ucfirst($row['privacy']); ?></td>
                    <?php } ?>

                    </tr>
                </table>
            </div>
            <p></p>
            <div class='w3-bar' style='display:flex;justify-content:center'>

        <?php if(!$_SESSION['ban_status'] >= 1 && $perms == 'rw') {
            if($file_type == 'text') { ?>
                <button class='w3-button w3-bar-item w3-green w3-round' form='upload-form' value='<?= $file_name; ?>' name='upload_btn' style='margin-right:5px'>
                    <i class='fa fa-floppy-disk'></i> Save
                </button>
            <?php } ?>

                <button class='w3-button w3-bar-item w3-red w3-round' onclick='openModal("delete", <?= $file_modal ?>)' style='margin-right:5px'>
                    <i class='fa fa-fw fa-trash-can'></i> Delete
                </button>
        <?php } ?>

                <form action='/files/download.php' method='POST'>
                    <button class='w3-button w3-bar-item w3-blue w3-round' value='<?= $file_name; ?>' name='download_btn' style='margin-right:5px'>
                        <i class='fa fa-fw fa-file-arrow-down'></i> Download
                    </button>
                </form>
                <button class='w3-button w3-bar-item w3-blue-grey w3-round' onclick='goBack()'>
                    <i class='fa fa-fw fa-folder-open'></i> Back
                </button>
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
                <form action='/files/delete.php' method='POST'>
                    <button class='w3-button w3-bar-item w3-red w3-round w3-margin-bottom' id='delete-button' type='submit' name='delete_btn' style='margin-right:5px'>
                        <i class='fa fa-fw fa-trash-can'></i> Delete
                    </button>
                </form>
                <button class='w3-button w3-bar-item w3-blue-grey w3-round w3-margin-bottom' onclick='document.getElementById("delete-modal").style.display="none"'>
                    <i class='fa fa-fw fa-ban'></i> Cancel
                </button>
            </footer>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common/notification.html'); ?>

</body>
</html>
