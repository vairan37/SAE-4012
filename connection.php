<?php
$servername = "mysql-388232-db.alwaysdata.net"; 
$username = "388232";
$password = "nathanvictor1";
$dbname = "wikimimihamster_admin";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>