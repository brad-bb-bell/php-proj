<?php
session_start();

function isAuthenticated() {
    // Check if user is logged in via session
    if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
        return true;
    }

    // Check if user has valid remember-me token
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        if (validateRememberToken($token)) {
            $_SESSION['authenticated'] = true;
            return true;
        }
    }

    return false;
}

function validateRememberToken($token) {
    // Simple token validation - in production you'd want this more secure
    $expected = hash('sha256', SECRET_KEY . USERNAME);
    return hash_equals($expected, $token);
}

function createRememberToken() {
    return hash('sha256', SECRET_KEY . USERNAME);
}
