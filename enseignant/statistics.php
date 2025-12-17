<?php 
include '../config/database.php';

include '../includes/header.php';



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
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Statistiques Avancées</h2>
    <p class="text-gray-600 mb-6">Analyse détaillée des performances des quiz et des étudiants.</p>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Taux de Réussite par Catégorie</h3>
            <div class="h-64 flex items-center justify-center text-gray-400">
                [Zone de graphique: Taux de réussite]
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Top 5 des Quiz</h3>
            <ul class="space-y-3">
                <li class="flex justify-between items-center text-gray-700 border-b pb-2"><span>1. Les Bases de HTML5</span> <span class="font-semibold text-green-600">92%</span></li>
                <li class="flex justify-between items-center text-gray-700 border-b pb-2"><span>2. Introduction à JavaScript</span> <span class="font-semibold text-green-600">85%</span></li>
                </ul>
        </div>
    </div>
</div>

</body>
</html>


<?php
include '../includes/footer.php';
?>


