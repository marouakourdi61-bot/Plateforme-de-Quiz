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
    <title>Document</title>
</head>
<body>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Ajouter une Question (Quiz ID: <?php echo htmlspecialchars($_GET['quiz_id'] ?? 'N/A'); ?>)</h2>
    <div class="bg-white rounded-xl shadow-md p-8">
        <form action="save_question.php" method="POST">
            <input type="hidden" name="quiz_id" value="<?php echo htmlspecialchars($_GET['quiz_id'] ?? ''); ?>">
            <div class="space-y-6">
                <div>
                    <label for="question_text" class="block text-sm font-medium text-gray-700">Texte de la Question</label>
                    <textarea name="question_text" id="question_text" required rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                
                <h3 class="text-xl font-semibold text-gray-800 pt-4">Options de Réponse</h3>
                <div id="answers-container" class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <input type="radio" name="correct_answer" value="1" required class="h-5 w-5 text-indigo-600 border-gray-300">
                        <input type="text" name="answers[]" required placeholder="Option 1" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="flex items-center space-x-3">
                        <input type="radio" name="correct_answer" value="2" class="h-5 w-5 text-indigo-600 border-gray-300">
                        <input type="text" name="answers[]" required placeholder="Option 2" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <button type="button" onclick="addAnswerOption()" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    <i class="fas fa-plus mr-1"></i> Ajouter une option
                </button>
            </div>
            
            <div class="mt-8">
                <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                    Enregistrer la Question
                </button>
            </div>
        </form>
    </div>
</div>

    
</body>
</html>


<?php
include '../includes/footer.php';
?>