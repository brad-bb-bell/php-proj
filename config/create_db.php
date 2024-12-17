<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'contributions';

try {
    $database = new PDO("mysql:host=$servername;", $username, $password);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $database->exec($sql);
} catch (PDOException $e) {
    echo $e->getMessage();
}

// Each block has its own try-catch because you want to handle database creation errors separately from table creation errors - they could fail for different reasons.

try {
    $database = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE TABLE IF NOT EXISTS Transactions (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    account VARCHAR(40) NOT NULL,
    account_type VARCHAR(40) NOT NULL,
    asset_class VARCHAR(40) NOT NULL,
    amount DOUBLE NOT NULL
    )";
    $database->exec($sql);
} catch (PDOException $e) {
    echo 'Error creating table';
    echo $e->getMessage();
}