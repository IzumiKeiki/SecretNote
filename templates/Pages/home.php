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
            <form action="/save_note" method="post" class="space-y-4">
                <input type="hidden" name="_csrfToken" value="<?= $this->request->getAttribute('csrfToken') ?>">
                <div>
                    <label for="note" class="block text-lg font-medium text-gray-700">Your secret note</label>
                    <textarea type="text" id="note" name="note"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring focus:ring-blue-300"><?= h(isset($secretNote) && $secretNote ? $secretNote->note : '') ?></textarea>
                </div>
                <div>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-500 to-purple-500 text-white mt-4 mb-2 py-1 px-4 text-lg rounded-full shadow-md hover:from-purple-500 hover:to-blue-500 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-purple-300">
                        Save
                    </button>
                </div>
                <a class="text-xl text-gray-600 text-center mb-12 underline block" href="/logout_user">Logout</a>
            </form>
                <form action="/delete_user" method="post" class="text-center" onsubmit="return confirmDelete();">
                <input type="hidden" name="_csrfToken" value="<?= $this->request->getAttribute('csrfToken') ?>">
                <button type="submit" class="text-base text-red-600 px-3 underline">
                    Delete account
                </button>
            </form>
            <script>
                function confirmDelete() {
                    return confirm("Are you sure you want to delete your account? This action cannot be undone.");
                }
            </script>
        </div>
    </div>
</body>
</html>
