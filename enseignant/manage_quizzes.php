<?php
session_start();
require_once '../config/database.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'enseignant') {
    header("Location: ../auth/login.php");
    exit;
}


include '../includes/header.php';


?>



<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
if ($_SESSION['role'] !== 'enseignant') {
    header("Location: ../auth/login.php");
    exit;
}




$stmt = $pdo->query("
    SELECT * FROM quizzes
    ORDER BY id DESC
");
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {

    $delete_id = $_POST['delete_id'];

    $stmt = $pdo->prepare("DELETE FROM quizzes WHERE id = ?");
    $stmt->execute([$delete_id]);

    header("Location: manage_quizzes.php?deleted=1");
    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {

    $stmt = $pdo->prepare("
        UPDATE quizzes 
        SET title = ?, description = ?, category = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $_POST['title'],
        $_POST['description'],
        $_POST['category'],
        $_POST['update_id']
    ]);

    header("Location: manage_quizzes.php?updated=1");

    exit;
}


$editQuiz = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editQuiz = $stmt->fetch(PDO::FETCH_ASSOC);
}







?>


 
   
<div id="teacherSpace" class="pt-20"></div>
<div id="quiz" class="section-content">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 h-[80vh] ">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Mes Quiz</h2>
                <p class="text-gray-600 mt-2">Créez et gérez vos quiz</p>
            </div>

            <?php if ($editQuiz): ?>
            <form method="POST" class="bg-white p-4 rounded mb-6">
            <input type="hidden" name="update_id" value="<?= $editQuiz['id'] ?>">

            <input type="text" name="title" value="<?= $editQuiz['title'] ?>" required class="border p-2 w-full mb-2">
            <textarea name="description" class="border p-2 w-full mb-2"><?= $editQuiz['description'] ?></textarea>

           <select name="category" class="border p-2 w-full mb-2">
             <option value="HTML / CSS" <?= $editQuiz['category']=='HTML / CSS'?'selected':'' ?>>HTML / CSS</option>
              <option value="JavaScript" <?= $editQuiz['category']=='JavaScript'?'selected':'' ?>>JavaScript</option>
        </select>

    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">
        Modifier
    </button>
</form>
<?php endif; ?>


            <a href="add_quiz.php" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                <i class="fas fa-plus mr-2"></i>Créer un Quiz
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">

            <?php if (isset($_GET['success'])): ?>
            <p class="text-green-600 mb-4">Quiz ajouté avec succès </p>
            <?php endif; ?>




            <?php foreach ($quizzes as $quiz): ?>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">HTML/CSS</span>
                        <div class="flex gap-2">
                            <a href="manage_quizzes.php?edit=<?= $quiz['id'] ?>" class="text-blue-600">
                            <i class="fas fa-edit"></i>
                            </a>

                            <form method="POST" style="display:inline">
                            <input type="hidden" name="delete_id" value="<?= $quiz['id'] ?>">
                            <button type="submit" class="text-red-600 hover:text-red-700"
                            onclick="return confirm('vous ete sure que vous vouler suprimé se quiz')">
                            <i class="fas fa-trash"></i>
                            </button>
                          </form>


                            <a href="add_question.php?quiz_id=1" class="text-green-600 hover:text-green-700"><i class="fas fa-plus-circle"></i></a>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?=$quiz['title'] ?></h3>
                    <p class="text-gray-600 mb-4 text-sm"><?= $quiz['category'] ?></p>
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span><i class="fas fa-question-circle mr-1"></i>20 questions</span>
                        <span><i class="fas fa-user-friends mr-1"></i>45 participants</span>
                    </div>
                    <a href="view_results.php?quiz_id=1" class="w-full block text-center bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                        <i class="fas fa-eye mr-2"></i>Voir les résultats
                    </a>
                </div>

                <?php endforeach; ?>

            </div>
            </div>
    </div>
</div>

















 
        

<?php
include '../includes/footer.php';
?>