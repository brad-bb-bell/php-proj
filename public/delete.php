<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'contributions';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $database = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'DELETE FROM Transactions WHERE id = :id';
        $stmt = $database->prepare($sql);

        $stmt->bindParam(':id', $_POST['id']);
        $stmt->execute();

        header('Location: ./transactions.php?status=deleted');
        exit();
    } catch (PDOException $e) {
        header('Location: ./transactions.php?status=failed');
    }
}
