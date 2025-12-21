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






if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Quiz 
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = $_POST['category']; 
    $teacher_id = $_SESSION['user_id'];

    //  quiz
    $stmt = $pdo->prepare("
        INSERT INTO quizzes (title, description, teacher_id, category)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$title, $description, $teacher_id, $category]);

   
    
    $quiz_id = $pdo->lastInsertId();

    // Question 
    $q = $_POST['questions'][0];

    $question_text = $q['question'];
    $option_a = $q['options'][0];
    $option_b = $q['options'][1];
    $option_c = $q['options'][2];
    $option_d = $q['options'][3];

    // option vrai
    $letters = ['A', 'B', 'C', 'D'];
    $correct_option = $letters[$q['correct']];

    //  question
    $stmtQ = $pdo->prepare("
        INSERT INTO questions 
        (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmtQ->execute([
        $quiz_id,
        $question_text,
        $option_a,
        $option_b,
        $option_c,
        $option_d,
        $correct_option
    ]);

    
    header("Location: manage_quizzes.php?success=1");
    exit;
}





?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer Quiz</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-5xl mx-auto px-4 py-10">
    <h2 class="text-3xl font-bold mb-6">Créer un Nouveau Quiz</h2>

    <form action="add_quiz.php" method="POST" class="bg-white p-8 rounded-xl shadow space-y-8">

        <!-- Quiz info -->
        <div>
            <label class="block text-sm font-medium">Titre du Quiz *</label>
            <input type="text" name="title" required class="w-full mt-1 p-3 border rounded-md">
        </div>

        <div>
            <label class="block text-sm font-medium">Catégorie *</label>
            <select name="category" required class="w-full mt-1 p-3 border rounded-md">
                <option value="">Sélectionnez</option>
                <option value="1">HTML / CSS</option>
                <option value="2">JavaScript</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" rows="3" class="w-full mt-1 p-3 border rounded-md"></textarea>
        </div>

        <!-- Questions header -->
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold">Questions</h3>

            
            <button type="button"
                class="bg-green-600 text-white px-4 py-2 rounded-lg cursor-not-allowed opacity-80">
                + Ajouter une question
            </button>
        </div>

        <!-- Question 1 -->
        <div class="border rounded-lg p-6 bg-gray-50 space-y-4">
            <h4 class="font-semibold">Question 1</h4>

            <input type="text" name="questions[0][question]" required
                   placeholder="Posez votre question..."
                   class="w-full p-3 border rounded-md">

            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="questions[0][options][]" required placeholder="Option 1"
                       class="p-3 border rounded-md">
                <input type="text" name="questions[0][options][]" required placeholder="Option 2"
                       class="p-3 border rounded-md">
                <input type="text" name="questions[0][options][]" required placeholder="Option 3"
                       class="p-3 border rounded-md">
                <input type="text" name="questions[0][options][]" required placeholder="Option 4"
                       class="p-3 border rounded-md">
            </div>

            <select name="questions[0][correct]" required
                    class="w-full p-3 border rounded-md">
                <option value="">Sélectionner la bonne réponse</option>
                <option value="0">Option 1</option>
                <option value="1">Option 2</option>
                <option value="2">Option 3</option>
                <option value="3">Option 4</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4">
            <a href="manage_quizzes.php"
               class="w-1/2 text-center border py-3 rounded-lg">
                Annuler
            </a>
            <button type="submit"
                    class="w-1/2 bg-indigo-600 text-white py-3 rounded-lg">
                Créer le Quiz
            </button>
        </div>

    </form>
</div>

</body>
</html>




<?php
include '../includes/footer.php';
?>