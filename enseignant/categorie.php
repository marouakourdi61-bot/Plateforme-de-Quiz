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


$stmt = $pdo->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


$cardsHTML = '';

foreach ($categories as $cat) {
    $cardsHTML .= '
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-xl font-bold text-gray-900">'.htmlspecialchars($cat['nom']).'</h3>
                <p class="text-gray-600 text-sm mt-1">'.htmlspecialchars($cat['description']).'</p>
            </div>
        </div>
    </div>';
}

//edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);

    if ($nom && $description) {
        $stmt = $pdo->prepare("UPDATE categories SET nom = ?, description = ? WHERE id = ?");
        $stmt->execute([$nom, $description, $id]);
        header("Location: categorie.php"); 
        exit;
    }
}


//suprimé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['delete_id'])) {
    
    $delete_id = (int) $_POST['delete_id']; 

    if ($delete_id > 0) {
        
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$delete_id]);

        header("Location: categorie.php");
        exit;
    }
}






// cree 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_category'])) {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if ($name !== '') {
        $stmt = $pdo->prepare(
            "INSERT INTO categories (nom, description) VALUES (?, ?)"
        );
        $stmt->execute([$name, $description]);

        header("Location: categorie.php");
        exit;
    }
}






include '../includes/header.php';
?>







<div id="editCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg p-6 w-96">
    <h2 class="text-xl font-bold mb-4">Modifier la catégorie</h2>
    <form method="POST" action="categorie.php">
      <input type="hidden" name="id" id="editCategoryId">

      <div class="mb-4">
        <label class="block font-bold mb-2">Nom *</label>
        <input type="text" name="nom" id="editCategoryNom" 
               class="w-full px-4 py-2 border rounded-lg" required>
      </div>

      <div class="mb-6">
        <label class="block font-bold mb-2">Description</label>
        <textarea name="description" id="editCategoryDescription" 
                  class="w-full px-4 py-2 border rounded-lg" required></textarea>
      </div>

      <div class="flex gap-3">
        <button type="button" onclick="closeEditModal()" class="flex-1 border py-2 rounded-lg">
          Annuler
        </button>
        <button type="submit" class="flex-1 bg-indigo-600 text-white py-2 rounded-lg">
          Enregistrer
        </button>
      </div>
    </form>
  </div>
</div>




    <!-- Categories Section -->
<div id="teacherSpace" class="pt-16">
    <div id="categories" class="section-content">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 h-[80vh]">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Gestion des Catégories</h2>
                    <p class="text-gray-600 mt-2">Organisez vos quiz par catégories</p>
                </div>
                <button onclick="openModal('createCategoryModal')" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                    <i class="fas fa-plus mr-2"></i>Nouvelle Catégorie
                </button>
            </div>

            <!-- Categories List -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($categories as $cat): ?>
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($cat['nom']) ?></h3>
                                <p class="text-gray-600 text-sm mt-1"><?= htmlspecialchars($cat['description']) ?></p>
                            </div>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-700" onclick="openEditModal(<?= $cat['id'] ?>)">
                                   édit
                                </button>
                                <button class="text-red-600 hover:text-red-700" onclick="deleteCategory(<?= $cat['id'] ?>)">
                                    suprimé
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500"><i class="fas fa-clipboard-list mr-2"> </i>12 quiz</span>
                            <span class="text-gray-500"><i class="fas fa-user-friends mr-2"></i>45 étudiants</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>






<!-- ===== CREATE CATEGORY MODAL ===== -->
<div id="createCategoryModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

  <div class="bg-white rounded-lg p-6 w-96">
    <h2 class="text-xl font-bold mb-4">Nouvelle Catégorie</h2>

    <form method="POST" action="categorie.php">
      <input type="hidden" name="create_category" value="1">

      <div class="mb-4">
        <label class="block font-bold mb-2">Nom *</label>
        <input type="text" name="name"
               class="w-full px-4 py-2 border rounded-lg"
               required>
      </div>

      <div class="mb-6">
        <label class="block font-bold mb-2">Description</label>
        <textarea name="description"
                  class="w-full px-4 py-2 border rounded-lg"></textarea>
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




<script>
function openEditModal(id) {
    
    const categories = <?= json_encode($categories) ?>;
    const cat = categories.find(c => c.id == id);

    if(cat) {
        document.getElementById('editCategoryId').value = cat.id;
        document.getElementById('editCategoryNom').value = cat.nom;
        document.getElementById('editCategoryDescription').value = cat.description;

        document.getElementById('editCategoryModal').classList.remove('hidden');
        document.getElementById('editCategoryModal').classList.add('flex');
    }
}

function closeEditModal() {
    document.getElementById('editCategoryModal').classList.add('hidden');
    document.getElementById('editCategoryModal').classList.remove('flex');
}







function deleteCategory(id) {
    if (confirm("Voulez-vous vraiment supprimer cette catégorie ?")) {
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_id';
        input.value = id;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}





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