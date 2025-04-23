<?php
$host = "localhost";
$dbname = "dbq2w9la5awxx0";
$username = "u2pjrffy26hcd";
$password = "ukkuf75gqg3q";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
