<?php
session_start();
include_once('/srv/http/nozzato.com/scripts/scripts.php');

if(isset($_POST['download_btn'])) {
    $file_server = $file_path_server = $file_name = $file_info = $file_mime = $file_type = '';
    $file_path_server = '/srv/http/nozzato.com/files/' . $_SESSION['username'] . '/';
    $file_name   = $_POST['download_btn'];
    $file_server = $file_path_server . $file_name;
    $file_info   = new finfo(FILEINFO_MIME);
    $file_mime   = $file_info -> buffer(file_get_contents($file_server));
    $file_type   = substr($file_mime, 0, strpos($file_mime, ';'));

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
