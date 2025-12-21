<?php 
include '../config/database.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'enseignant') {
    header("Location: ../auth/login.php");
    exit;
}

$quiz_id = $_GET['quiz_id'] ?? null;

// Ajouter 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_question'])) {
    $question_text = $_POST['question_text'];
    $answers = $_POST['answers'];
    $correct_index = $_POST['correct_answer'] - 1;

    $option_a = $answers[0] ?? null;
    $option_b = $answers[1] ?? null;
    $option_c = $answers[2] ?? null;
    $option_d = $answers[3] ?? null;
    $letters = ['A','B','C','D'];
    $correct_option = $letters[$correct_index];

    $stmt = $pdo->prepare("
        INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$quiz_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option]);

    header("Location: add_question.php?quiz_id=$quiz_id&success=1");
    exit;
}

// Modifier 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_question'])) {
    $stmt = $pdo->prepare("
        UPDATE questions
        SET question_text=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_option=?
        WHERE id=?
    ");
    $stmt->execute([
        $_POST['question_text'],
        $_POST['answers'][0],
        $_POST['answers'][1],
        $_POST['answers'][2] ?? null,
        $_POST['answers'][3] ?? null,
        ['A','B','C','D'][$_POST['correct_answer']-1],
        $_POST['update_id']
    ]);

    header("Location: add_question.php?quiz_id=$quiz_id&updated=1");
    exit;
}

// Supprimer 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_question_id'])) {
    $stmt = $pdo->prepare("DELETE FROM questions WHERE id=?");
    $stmt->execute([$_POST['delete_question_id']]);
    header("Location: add_question.php?quiz_id=$quiz_id&deleted=1");
    exit;
}

// Récupére
$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id=? ORDER BY id ASC");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);


$editQuestion = null;
if (isset($_GET['edit_question'])) {
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE id=?");
    $stmt->execute([$_GET['edit_question']]);
    $editQuestion = $stmt->fetch(PDO::FETCH_ASSOC);
}

include '../includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">
        <?= $editQuestion ? 'Modifier Question' : 'Ajouter une Question' ?> (Quiz ID: <?= htmlspecialchars($quiz_id) ?>)
    </h2>

    <?php if(isset($_GET['success'])): ?>
        <p class="text-green-600 mb-4">Question ajoutée avec succès!</p>
    <?php elseif(isset($_GET['updated'])): ?>
        <p class="text-blue-600 mb-4">Question modifiée avec succès!</p>
    <?php elseif(isset($_GET['deleted'])): ?>
        <p class="text-red-600 mb-4">Question supprimée avec succès!</p>
    <?php endif; ?>

    <!-- Formulaire Ajouter / Modifier -->
    <div class="bg-white rounded-xl shadow-md p-8 mb-8">
        <form method="POST">
            <?php if($editQuestion): ?>
                <input type="hidden" name="update_id" value="<?= $editQuestion['id'] ?>">
            <?php endif; ?>

            <div class="space-y-4">
                <textarea name="question_text" required rows="4" placeholder="Texte de la question" class="w-full border p-2 rounded"><?= $editQuestion['question_text'] ?? '' ?></textarea>

                <h3 class="font-semibold">Options</h3>
                <?php
                $options = ['option_a','option_b','option_c','option_d'];
                foreach($options as $index => $opt):
                    $value = $editQuestion[$opt] ?? '';
                    if(!$value && $index>1) continue; 
                ?>
                <div class="flex items-center space-x-3 mb-2">
                    <input type="radio" name="correct_answer" value="<?= $index+1 ?>" <?= (isset($editQuestion) && $editQuestion['correct_option']==['A','B','C','D'][$index])?'checked':'' ?> required>
                    <input type="text" name="answers[]" placeholder="Option <?= $index+1 ?>" value="<?= $value ?>" class="border p-2 rounded w-full">
                </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" name="<?= $editQuestion ? 'update_question' : 'add_question' ?>" class="mt-4 w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                <?= $editQuestion ? 'Modifier Question' : 'Ajouter Question' ?>
            </button>
        </form>
    </div>

    <!-- Liste des questions -->
    <h3 class="text-xl font-bold mb-4">Liste des Questions</h3>
    <?php foreach($questions as $q): ?>
        <div class="p-4 border rounded mb-2 flex justify-between items-center">
            <div>
                <p><?= $q['question_text'] ?></p>
                <small>Réponse correcte: <?= $q['correct_option'] ?></small>
            </div>
            <div class="flex gap-2">
                <a href="add_question.php?quiz_id=<?= $quiz_id ?>&edit_question=<?= $q['id'] ?>" class="text-blue-600">Modifier</a>
                <form method="POST" style="display:inline">
                    <input type="hidden" name="delete_question_id" value="<?= $q['id'] ?>">
                    <button type="submit" onclick="return confirm('Voulez-vous supprimer cette question ?')" class="text-red-600">Supprimer</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include '../includes/footer.php'; ?>

