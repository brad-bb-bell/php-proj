<?php
session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Contributions</title>
    <script>
        function handleLogin(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            
            fetch('process_login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (formData.get('remember')) {
                        localStorage.setItem('hasAuth', 'true');
                    }
                    window.location.href = 'index.php';
                } else {
                    alert('Invalid credentials');
                }
            });
        }
    </script>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📈</text></svg>">
</head>
<body class="bg-black p-4">
    <div class="max-w-screen-lg mx-auto">
        <div class="bg-purple-200 max-w-lg rounded-xl mx-auto mt-8">
            <h1 class="text-center text-xl rounded-t-xl border-b border-black py-1">Login</h1>
            <div class="mx-auto p-6 bg-purple-200 rounded-b-xl">
                <form onsubmit="handleLogin(event)" class="grid gap-6">
                    <div class="flex flex-col space-y-2">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required class="p-2 border rounded">
                    </div>
                    <div class="flex flex-col space-y-2">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required class="p-2 border rounded">
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="bg-purple-400 text-black py-2 px-4 rounded hover:bg-purple-500">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>