<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Login</title>
</head>
<body class="flex items-center justify-center h-screen bg-blue-300">
  <form action="login.php" method="POST" class="bg-white p-6 rounded-lg shadow-lg w-80">
    <h1 class="text-xl font-bold mb-4 align-center">Login</h1>
    <input type="text" name="username" placeholder="Username" class="w-full border rounded px-3 py-2 mb-3" required>
    <input type="password" name="password" placeholder="Password" class="w-full border rounded px-3 py-2 mb-3" required>
    <button type="submit" class="w-full bg-blue-300 text-white rounded py-2 hover:bg-blue-600">Login</button>
  </form>
</body>
</html>
