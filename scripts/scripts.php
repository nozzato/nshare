<?php
function go_back() {
    if($_SESSION['page'] == 'home') {
        header('location:/index.php');
        exit;
    } else if($_SESSION['page'] == 'profile') {
        header('location:/user/index.php');
        exit;
    } else if($_SESSION['page'] == 'settings') {
        header('location:/user/settings.php');
        exit;
    } else if($_SESSION['page'] == 'files') {
        header('location:/files/index.php');
        exit;
    } else {
        header('location:/index.php');
        exit;
    }
}

function remove_dir($dir) {
    if (!file_exists($dir)) {
        return true;
    }
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (!remove_dir($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    return rmdir($dir);
}

function human_filesize($bytes, $decimals = 2) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}
?>
