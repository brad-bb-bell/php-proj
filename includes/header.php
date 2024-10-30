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
<header class="bg-purple-400 text-black p-4 w-full">
    <div class="max-w-screen-lg mx-auto flex">
        <span class="">📈 Contributions 📈</span>
        <nav class="ml-12">
            <ul class="list-none p-0 m-0">
                <li class="inline mr-4">
                    <a href="/php-proj/public/index.php" class="text-black no-underline">Home</a>
                </li>
                <li class="inline mr-4">
                    <a href="/php-proj/public/transactions.php" class="text-black no-underline">Transactions</a>
                </li>
            </ul>
        </nav>
    </div>
</header>
<main class="p-8 w-full mx-auto bg-black min-h-[calc(100dvh-7rem)]">