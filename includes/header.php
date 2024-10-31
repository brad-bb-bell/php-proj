<?php
    $title = $title ?? "Contributions";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="/php-proj/assets/css/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📈</text></svg>">
</head>
<header class="bg-purple-400 text-black p-4 w-full">
    <div class="max-w-screen-lg mx-auto flex items-center">
        <span class="font-semibold">📈 Contributions 📈</span>
        <nav class="ml-12">
            <ul class="list-none p-0 m-0">
                <li class="inline mr-7">
                    <a href="/php-proj/public/index.php" class="text-gray-800 hover:text-black no-underline">Home</a>
                </li>
                <li class="inline mr-7">
                    <a href="/php-proj/public/transactions.php" class="text-gray-800 hover:text-black no-underline">All Transactions</a>
                </li>
                <li class="inline mr-7">
                    <a href="/php-proj/public/contributions.php" class="text-gray-800 hover:text-black no-underline">Contributions by Type</a>
                </li>
            </ul>
        </nav>
    </div>
</header>
<main class="p-8 w-full mx-auto bg-black min-h-[calc(100dvh-7rem)]">