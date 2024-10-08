<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap');

        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-300">
    <div class="flex items-center justify-center h-screen">
        <div class="w-full max-w-md bg-[#2A3132] rounded-lg shadow-lg p-8">
            <h1 class="text-4xl font-bold text-white text-center mb-8">Login Pages</h1>
            <form action="proses_login.php" method="post">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 font-bold mb-2 text-white">Username</label>
                    <input type="text" name="username" id="username" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded-md py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold mb-2 text-white">Password</label>
                    <input type="password" name="password" id="password" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded-md py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Login</button>
                    <a href="requestOTP.php" class="text-blue-500 hover:underline">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
