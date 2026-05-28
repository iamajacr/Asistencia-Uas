<?php
$dbHost = '127.0.0.1';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'ceneval_2026'; 

$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
$db->set_charset("utf8mb4");

if ($db->connect_error) {
    header('Content-Type: application/json');
    die(json_encode(['success' => false, 'message' => 'Error de conexión']));
}
?>