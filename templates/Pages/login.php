
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen flex items-start justify-center">
        <div class="bg-white mt-20 p-6 rounded shadow-lg w-full max-w-md">
            <h1 class="text-3xl font-bold text-center mb-3">Welcome to Secret Notes</h1>
            <p class="text-xl text-gray-600 text-center mb-12">Your personal, secure place for confidential messages.</p>

            <!-- Login Form -->
            <form action="/" method="post" class="space-y-4">
                <input type="hidden" name="_csrfToken" value="<?= $this->request->getAttribute('csrfToken') ?>">
                <div>
                    <label for="username" class="block text-lg font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" required
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div>
                    <label for="password" class="block text-lg font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-500 to-purple-500 text-white mt-4 mb-2 py-1 px-4 text-lg rounded-full shadow-md hover:from-purple-500 hover:to-blue-500 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-purple-300">
                        Login
                    </button>
                </div>
                <a class="text-xl text-gray-600 text-center mb-12 underline block" href="/regist">Register here</a>
            </form>
        </div>
    </div>
</body>
</html>
