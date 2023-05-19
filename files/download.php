<?php
session_start();

// include functions
include_once($_SERVER['DOCUMENT_ROOT'] . '/scripts/scripts.php');

// if download button clicked
if(isset($_POST['download_btn'])) {
    // split CSV into array
    $_POST['download_btn'] = explode(',', $_POST['download_btn']);

    // set download variables
    $file_path_server = $_SERVER['DOCUMENT_ROOT'] . '/data/' . $_POST['download_btn'][1] . '/';
    $file_name        = $_POST['download_btn'][0];
    $file_server      = $file_path_server . $file_name;
    $file_info        = new finfo(FILEINFO_MIME);
    $file_mime        = $file_info -> buffer(file_get_contents($file_server));
    $file_type        = substr($file_mime, 0, strpos($file_mime, ';'));

    // download file
    header('Content-Description: File Transfer');
    header('Content-Type: ' . $file_type);
    header('Content-Disposition: attachment; filename="' . $file_name . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_server));
    readfile($file_server);

    exit;
}

go_back();
?>
