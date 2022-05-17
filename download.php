<?php
session_start();
include_once('functions.php');

if(isset($_POST['file'])) {
    if($_SESSION['page'] == 'public') {
        $file_path = 'files/public/';
    } else if($_SESSION['page'] == 'private') {
        $file_path = 'files/' . $_SESSION['username'] . '/';
    }
    $file_name  = $_POST['file'];
    $file       = $file_path . $file_name;
    $file_info = new finfo(FILEINFO_MIME);                          //[1]
    $file_mime = $file_info -> buffer(file_get_contents($file));
    $file_type = substr($file_mime, 0, strpos($file_mime, ';'));

    header('Content-Description: File Transfer');
    header('Content-Type: ' . $file_type);
    header('Content-Disposition: attachment; filename="' . $file_name . '"');
    header('Content-Transfer-Encoding: binary');
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