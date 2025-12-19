<?php
session_start();
require_once '../config/database.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
if ($_SESSION['role'] !== 'enseignant') {
    header("Location: ../auth/login.php");
    exit;
}



//crée une categorie

 $message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (empty($name)) {
        $message = "Nom obligatoire";
    } else {

        $stmt = $pdo->prepare(
    "INSERT INTO categories (nom, description) VALUES (?, ?)"
);
        $stmt->execute([$name, $description]);

        $message = "Catégorie ajoutée";
    }
}

//crée un quiz








?>

<?php include '../includes/header.php'; ?>









<div id="teacherSpace" class="pt-20">
<div id="dashboard" class="section-content h-[40vh]">


<div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-4xl font-bold mb-4">Tableau de bord Enseignant</h1>
        <p class="text-xl text-indigo-100 mb-6">Gérez vos quiz</p>

        <div class="flex gap-4">
            <button onclick="openModal('createCategoryModal')"
                class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition">
                <i class="fas fa-folder-plus mr-2"></i>Nouvelle Catégorie
            </button>

            
                <a  onclick="openModal('createQuizModal')" href="add_quiz.php" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                <i class="fas fa-plus mr-2"></i>Créer un Quiz
            </a>
        </div>
    </div>
</div>

<!-- ================= MODAL CATEGORIE ================= -->
<div id="createCategoryModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Nouvelle Catégorie</h3>
                <button onclick="closeModal('createCategoryModal')">
                    <i class="fas fa-times text-xl text-gray-500"></i>
                </button>
            </div>


            




            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block font-bold mb-2">Nom *</label>
                    <input type="text" name="name"
                     class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div class="mb-6">
                    <label class="block font-bold mb-2">Description</label>
                    <textarea name="description" class="w-full px-4 py-2 border rounded-lg"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button"
                        onclick="closeModal('createCategoryModal')"
                        class="flex-1 border py-2 rounded-lg">
                        Annuler
                    </button>
                    <button type="submit"
                        class="flex-1 bg-indigo-600 text-white py-2 rounded-lg">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= MODAL QUIZ ================= -->


</div>
</div>



 <!-- Stats Cards -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8  h-[45vh]">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Quiz</p>
                                <p class="text-3xl font-bold text-gray-900">24</p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <i class="fas fa-clipboard-list text-blue-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Catégories</p>
                                <p class="text-3xl font-bold text-gray-900">8</p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <i class="fas fa-folder text-purple-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Étudiants Actifs</p>
                                <p class="text-3xl font-bold text-gray-900">156</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-lg">
                                <i class="fas fa-user-graduate text-green-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Taux Réussite</p>
                                <p class="text-3xl font-bold text-gray-900">87%</p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-lg">
                                <i class="fas fa-chart-line text-yellow-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



<!-- ================= JS ================= -->
<script>
function openModal(id) {
    const modal = document.getElementById(id);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal(id) {
    const modal = document.getElementById(id);
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// close when click outside
window.addEventListener('click', function(e) {
    if (e.target.classList.contains('bg-opacity-50')) {
        e.target.classList.add('hidden');
        e.target.classList.remove('flex');
    }
});
</script>





<?php
include '../includes/footer.php';
?>