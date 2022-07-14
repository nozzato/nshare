<?php
session_start();

// if not logged in
if(!isset($_SESSION['user'])) {
    header('location:/indox');
    exit;
}

// include functions
include_once('/srv/http/nozzato.com/scripts/scripts.php');

// connect to database
include_once('/srv/http/nozzato.com/admin/connect.php');

$_SESSION['page'] = 'files';
?>
<!DOCTYPE html>
<html lang='en'>
<head>

<title>Files: <?= $_SESSION['username'] . '/'; ?> - NShare</title>
<link rel='icon' type='image/gif' href='/assets/favicon.gif'>

<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>

<link rel='stylesheet' href='/styles/w3.css'>
<link rel='stylesheet' href='/styles/nz.css'>
<link rel='stylesheet' href='/styles/icons/css/all.css'>

<script src='/scripts/scripts.js' type='text/javascript'></script>
<script type='text/javascript'>
function openFile(id) {
    window.location.href = '/files/edit?id=' + id;
}
function uploadFile() {
    document.getElementById("upload-form").submit();
}
</script>

</head>
<body style='width:100vw;height:100vh'>

<div id='header'>
    <div class='w3-bar'>

    <?php if(!isset($_SESSION['user'])) { ?>
        <a class='w3-bar-item w3-button nz-brand' href='/index'>NShare</a>
    <?php } else { ?>
        <a class='w3-bar-item w3-button nz-brand w3-mobile' href='/index'>NShare</a>
    <?php } ?>

    <?php if(isset($_SESSION['user'])) { ?>
        <a class='w3-bar-item w3-button' href='/files/index'>
            <i class='fa fa-fw fa-folder-open'></i> Files
        </a>
        <a class='w3-bar-item w3-button' href='/user/users'>
            <i class='fa fa-fw fa-user'></i> Users
        </a>
    <?php } ?>

    <?php if(isset($_SESSION['rank']) && $_SESSION['rank'] == 'admin') { ?>
        <a class='w3-bar-item w3-button' href='/admin/index'>
            <i class='fa fa-fw fa-server'></i> Admin
        </a>
    <?php } ?>

    <?php if(!isset($_SESSION['user'])) { ?>
        <div class='w3-dropdown-click w3-right'>
            <button class='w3-button' onclick='dropdownToggle()'>
                <i class='fa fa-fw fa-door-open'></i> Login <i class='fa fa-fw fa-caret-down'></i>
            </button>
            <div class='w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2 nz-dropdown-left' id='dropdown' style='max-width:100px'>
                <form class='w3-right' action='/user/login.php' method='POST' onsubmit='return loginValidate(this)'>
                    <div style='display:flex'>
                        <i class='fa fa-fw fa-user' style='margin:8px 2px 8px 18px;padding:4px 0.93px 0 0.93px;vertical-align:center'></i>
                        <input class='w3-bar-item w3-input nz-black' id='login-username' type='text' placeholder='Username' name='login_username' style='padding:8px 16px 8px 5px'>
                    </div>
                    <div style='display:flex'>
                        <i class='fa fa-fw fa-key' style='margin:8px 2px 8px 18px;padding-top:4px;vertical-align:center'></i>
                        <input class='w3-bar-item w3-input nz-black' id='login-password' type='password' placeholder='Password' name='login_password' style='padding:8px 16px 8px 5px'>
                    </div>
                    <button class='w3-bar-item w3-button w3-green nz-round-bottom-left' type='submit' name='login_btn'>
                        <i class='fa fa-fw fa-right-to-bracket'></i> Login
                    </button>
                </form>
            </div>
        </div>
    <?php } else { ?>
        <div class='w3-dropdown-hover w3-right'>
            <button class='w3-button'>
                <i class='fa fa-fw fa-door-closed'></i> Account <i class='fa fa-fw fa-caret-down'></i>
            </button>
            <div class='w3-dropdown-content w3-bar-block nz-black nz-round-bottom-left w3-card-2 nz-dropdown-left' style='max-width:100px'>
                <a class='w3-bar-item w3-button' href='/user/index?id=<?= $_SESSION['user']; ?>'>
                    <i class='fa fa-fw fa-user'></i> <?= $_SESSION['username']; ?>
                </a>

            <?php if(!$_SESSION['ban_status'] >= 1) { ?>
                <a class='w3-bar-item w3-button' href='/user/settings'>
                    <i class='fa fa-fw fa-gear'></i> Settings
                </a>
            <?php } ?>

                <form action='/user/logout.php' method='POST'>
                    <button class='w3-bar-item w3-button w3-red nz-round-bottom-left' type='submit' name='logout_btn'>
                        <i class='fa fa-fw fa-right-from-bracket'></i> Logout
                    </button>
                </form>
            </div>
        </div>
    <?php } ?>

    </div>
</div>

<div id='content' style='margin-bottom:38.5px'>
    <div class='w3-container w3-col nz-black' style='width:200px;height:100vh'>
        <div class='w3-display-bottomleft' style='width:168px;bottom:16px;left:16px;'>
        
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

            <span>Storage Used</span>
            <br>
            <span><?= human_filesize($db_file_size_total); ?> / 5.00G
            <br><br>

        <?php if(!$_SESSION['ban_status'] >= 1) { ?>
            <form id='upload-form' action='/files/upload.php' method='POST' enctype='multipart/form-data'>
                <span>Upload Privacy</span>
                <br>
                <input class='w3-radio' id='upload-private' type='radio' value='private' name='upload_privacy' checked>
                <label for='upload-private'>Private</label>
                <br>
                <input class='w3-radio' id='upload-public' type='radio' value='public' name='upload_privacy'>
                <label for='upload-public'>Public</label>
                <br><br>
                <input class='w3-hide' id='upload-file' type='file' name='upload_file[]' onchange='uploadFile()' multiple required>
                <label class='w3-button w3-green w3-round' for='upload-file' style='width:100%'>
                    <i class='fa fa-fw fa-file-arrow-up'></i> Upload
                </label>
            </form>
        <?php } else { ?>
            <p></p>
            <form class='w3-margin-top w3-center' action='/files/download.php' method='POST'>
                <button class='w3-button w3-blue w3-round' name='download_all_btn'>
                    <i class='fa fa-fw fa-file-arrow-down'></i> Download All
                </button>
            </form>
        <?php } ?>

        </div>
    </div>
    <div class='w3-container w3-rest'>
        <table class='nz-table'>
            <tr><td><a href='/files/index'><?= $_SESSION['username'] ?>/</a></td></tr>
        </table>
        <div class='w3-responsive'>
            <table class='nz-table'>
                <tr>
                    <th class='nz-truncate'>Name <i class='fa fa-fw fa-caret-down'></i></th>

                <?php if(!$_SESSION['ban_status'] >= 1) { ?>
                    <th>Privacy</th>
                <?php } ?>

                    <th>Size</th>
                    <th class='nz-truncate'>Date Modified</th>

                <?php if(!$_SESSION['ban_status'] >= 1) { ?>
                    <th>Delete</th>
                <?php } ?>

                </tr>

            <?php for($i = 0; $i < $count; $i++) {
                $file_modal = '"' . $rows[$i]['filename'] . '"';

                // select formatted upload date
                $stmt = $pdo-> prepare('SELECT DATE_FORMAT(`upload_date`, "%d-%m-%Y %h:%i:%s") FROM `files` WHERE `user_id` = ? AND `filename` = ?;');
                $stmt-> execute([$_SESSION['user'], $rows[$i]['filename']]);
                $rows[$i]['upload_date'] = $stmt-> fetchColumn();
            ?>
                <tr>
                    <td class='w3-button' onclick='openFile(<?= $rows[$i]['file_id']; ?>)'><?= $rows[$i]['filename']; ?></td>

                <?php if(!$_SESSION['ban_status'] >= 1) { ?>
                    <td class='w3-button' id='<?= $rows[$i]['file_id']; ?>' onclick='changePrivacy("<?= $rows[$i]['file_id']; ?>")'><?= ucfirst($rows[$i]['privacy']); ?></td>
                <?php } ?>

                <td><?= human_filesize($rows[$i]['size']); ?></td>
                    <td><?= $rows[$i]['upload_date']; ?></td>

                <?php if(!$_SESSION['ban_status'] >= 1) { ?>
                    <td class='w3-button w3-hover-red' onclick='openModal(<?= $file_modal; ?>)'>Delete</td>
                <?php } ?>

                </tr>
            <?php } ?>

            </table>
        </div>
    </div>
</div>
<div class='w3-modal' id='modal'>
    <div class='w3-modal-content nz-dark w3-round w3-card-2'>
        <header class='w3-container nz-black nz-round-top'>
            <h2>Really delete?</h2>
        </header>
        <div class='w3-container'>
            <p class ='m-0 text-center' id='modal-content'></p>
        </div>
        <footer class='w3-container w3-bar'>
            <form action='/files/delete.php' method='POST'>
                <button class='w3-button w3-bar-item w3-red w3-round w3-margin-bottom' id='delete-button' type='submit' name='delete_btn' style='margin-right:5px'>
                    <i class='fa fa-fw fa-trash-can'></i> Delete
                </button>
            </form>
            <button class='w3-button w3-bar-item w3-blue-grey w3-round w3-margin-bottom' onclick='document.getElementById("modal").style.display="none"'>
                <i class='fa fa-fw fa-ban'></i> Cancel
            </button>
        </footer>
    </div>
</div>

</body>
</html>