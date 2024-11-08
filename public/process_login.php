<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if ($username === USERNAME && password_verify($password, PASSWORD_HASH)) {
        $_SESSION['authenticated'] = true;

        if ($remember) {
            $token = createRememberToken();
            setcookie('remember_token', $token, time() + 30 * 24 * 60 * 60, '/', '', true, true);
        }

        echo json_encode(['success' => true]);
        exit();
    }
}

echo json_encode(['success' => false]);
