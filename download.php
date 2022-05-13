<?php
session_start();
include_once('functions.php');

if(isset($_POST['file'])) {
    if($_SESSION['page'] == 'public') {
        $filePath = 'files/public/';
    } else if($_SESSION['page'] == 'private') {
        $filePath = 'files/' . $_SESSION['username'] . '/';
    }
    $fileName  = $_POST['file'];
    $file      = $filePath . $fileName;

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);

    exit;
} else {
    page_back();
}
?>