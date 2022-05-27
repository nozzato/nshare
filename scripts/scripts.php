<?php
session_start();

function go_back() {
    if($_SESSION['page'] == 'home') {
        header('location:/index.php');
        exit;
    } else if ($_SESSION['page'] == 'signup') {
        header('location:/user/signup.php');
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
function h_captcha($hcr) {
    $data = array(
        'secret' => '0x5cB5398D7A0e9069531e4C520844E4c9728f74ED',
        'response' => $hcr
    );
    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, 'https://hcaptcha.com/siteverify');
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($verify);

    $responseData = json_decode($response);
    if($responseData->success) {
        return;
    } else {
        $_SESSION['msg'] = 'Error: CAPTCHA failed';
        go_back();
    }
}
?>
