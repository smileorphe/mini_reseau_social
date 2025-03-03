<?php
$dsn = 'mysql:host=localhost;dbname=reseau';
$user = 'root';
$password = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$conn = new PDO($dsn, $user, $password, $options);
?>