<?php
session_start();
require_once '../config/database.php';


 


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
    <!-- Categories Section -->
     <div id="teacherSpace" class="pt-16 ">
        <div id="categories" class="section-content">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 h-[80vh] ">
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
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">HTML/CSS</h3>
                                <p class="text-gray-600 text-sm mt-1">Bases du développement web</p>
                            </div>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-700">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500"><i class="fas fa-clipboard-list mr-2"></i>12 quiz</span>
                            <span class="text-gray-500"><i class="fas fa-user-friends mr-2"></i>45 étudiants</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">JavaScript</h3>
                                <p class="text-gray-600 text-sm mt-1">Programmation côté client</p>
                            </div>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-700">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500"><i class="fas fa-clipboard-list mr-2"></i>8 quiz</span>
                            <span class="text-gray-500"><i class="fas fa-user-friends mr-2"></i>38 étudiants</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">PHP/MySQL</h3>
                                <p class="text-gray-600 text-sm mt-1">Backend et bases de données</p>
                            </div>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-700">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500"><i class="fas fa-clipboard-list mr-2"></i>10 quiz</span>
                            <span class="text-gray-500"><i class="fas fa-user-friends mr-2"></i>42 étudiants</span>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
</body>
</html>


<?php
include '../includes/footer.php';
?>