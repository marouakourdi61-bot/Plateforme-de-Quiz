<?php 
include '../config/database.php';

?>


<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
if ($_SESSION['role'] !== 'enseignant') {
    header("Location: ../auth/login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>

    <title>Document</title>
</head>
<body>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Créer un Nouveau Quiz</h2>
    <div class="bg-white rounded-xl shadow-md p-8">
        <form action="save_quiz.php" method="POST">
            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Titre du Quiz</label>
                    <input type="text" name="title" id="title" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Catégorie</label>
                    <select name="category" id="category" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Sélectionnez une catégorie</option>
                        <option value="1">HTML/CSS</option>
                        <option value="2">JavaScript</option>
                        </select>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
            </div>
            <div class="mt-8">
                <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                    Enregistrer le Quiz et Ajouter des Questions
                </button>
                <a href="manage_quizzes.php" class="mt-3 block text-center text-sm text-gray-600 hover:text-indigo-600">Annuler</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>


<?php
include '../includes/footer.php';
?>