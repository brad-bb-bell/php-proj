<?php
    $title = isset($title) ? $title : "Contributions";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="/php-proj/assets/css/style.css">
</head>
<header class="text-center bg-[#dc91e2] text-black p-4">
    <nav>
        <ul class="list-none p-0 m-0">
            <li class="inline mr-4">
                <a href="/php-proj/public/index.php" class="text-black no-underline">Home</a>
            </li>
            <li class="inline mr-4">
                <a href="/php-proj/public/about.php" class="text-black no-underline">About</a>
            </li>
        </ul>
    </nav>
</header>
<main class="p-8 max-w-screen-lg mx-auto">