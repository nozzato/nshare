<?php
// conversion
function human_filesize($bytes, $decimals = 2) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

// files
function remove_dir($dir) {
    if(!file_exists($dir)) {
        return true;
    }
    if(!is_dir($dir)) {
        return unlink($dir);
    }
    foreach(scandir($dir) as $item) {
        if($item == '.' || $item == '..') {
            continue;
        }
        if(!remove_dir($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    return rmdir($dir);
}

// page location
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
    } else if($_SESSION['page'] == 2) {
        header('location:/admin/index.php');
        exit;
    } else {
        header('location:/index.php');
        exit;
    }
}

// security
function h_captcha($hcr) {
    $data = array(
        'secret' => trim(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/admin/hc_password')),
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
        $_SESSION['msg'] = ['Error: CAPTCHA failed', 'true'];
        go_back();
    }
}
?>
