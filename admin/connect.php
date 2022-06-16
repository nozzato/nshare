<?php
// set database credentials
$hostname    = '127.0.0.1';
$database    = 'nshare';
$charset     = 'utf8mb4';
$db_username = 'noah';
$db_password = 'nozz1234';

$dsn = "mysql:host=$hostname; dbname=$database; charset=$charset;";

// set PDO preferences
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // connect to database
    $pdo = new PDO($dsn, $db_username, $db_password, $options);
}
catch(\PDOException $e) {
    // throw PDO exception
    throw new \PDOException($e -> getMessage(), (int)$e -> getCode());
}
?>