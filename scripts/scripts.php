<?php
function go_back() {
    if($_SESSION['page'] == 'home') {
        header('location:/');
        exit;
    } else if($_SESSION['page'] == 'account') {
        header('location:/user/');
        exit;
    } else if($_SESSION['page'] == 'files') {
        header('location:/files/');
        exit;
    } else {
        header('location:/');
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
?>
