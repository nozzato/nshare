<?php
session_start();

if(isset($_FILES['file'])){
    if($_SESSION['page'] == 'public') {
        $filePath = 'files/public/';
    } else if($_SESSION['page'] == 'private') {
        $filePath = 'files/' . $_SESSION['username'] . '/';
    }
    $fileName = $filepath . $_FILES['file']['name'];

    if(move_uploaded_file($_FILES['file']['tmp_name'], $filePath . $fileName)) {
        $_SESSION['msg'] = "File uploaded";
    } else {
        echo "Possible file upload attack!<br>";
    }
    if(empty($errors) == false){
        echo "Error log:<br>";
        print_r($_FILES['file']['errors']);
        exit;
    }
    if($_SESSION['page'] == 'public') {
        header('location:files-public.php');
        exit;
    } else if($_SESSION['page'] == 'private') {
        header('location:files-private.php');
        exit;
    }
} else {
    header('location:files-private.php');
    exit;
}
?>