<?php
session_start();
require_once '../config/database.php';

$error_message = '';
$success_message = '';


$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';
    $role  = $_POST['role'] ?? '';

    // valid
    if (empty($name) || empty($email) || empty($pass) || empty($confirm_pass) || empty($role)) {
        $error_message = "remplir tout les champ";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "email est incorecte";
    } elseif ($pass !== $confirm_pass) {
        $error_message = "le mot de passe est incorecte";
    } elseif (strlen($pass) < 6) {
        $error_message = " le mot de passe doit etre plus de 6 chifre";
    } else {

        
        $check = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $check->execute(['email' => $email]);

        if ($check->rowCount() > 0) {
            $error_message = "se email est déja existe";
        } else {

         
            $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
 
            $sql = "INSERT INTO users (name, email, password, role)
                    VALUES (:name, :email, :password, :role)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
                'role' => $role
            ]);

            header("Location: login.php?success=1");
            exit;

        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizMaster - Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[url('https://images.unsplash.com/photo-1434725039720-aaad6dd32dfe?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8Zm9uZCUyMGRlJTIwcGF5c2FnZXxlbnwwfHwwfHx8MA%3D%3D')] bg-center bg-cover bg-no-repeat h-screen flex justify-center items-center relative">

    <div class="absolute inset-0 bg-black opacity-50"></div>

    <form method="POST"  class="relative z-10 text-white text-center flex flex-col gap-4 border-2 rounded-xl px-8 w-full max-w-md py-10 bg-black bg-opacity-30 backdrop-blur-sm">

        <h1 class="text-3xl uppercase font-extrabold mb-4 text-green-400">Inscription</h1>

        <?php if ($error_message): ?>
            <p class="text-red-400 font-semibold border border-red-400 p-2 rounded-md">
                <?= htmlspecialchars($error_message) ?>
            </p>
        <?php elseif ($success_message): ?>
            <p class="text-green-400 font-semibold border border-green-400 p-2 rounded-md">
                <?= htmlspecialchars($success_message) ?>
            </p>
        <?php endif; ?>

        <div class="flex flex-col gap-2 text-left">
            <label for="name" class="font-semibold">Nom Complet</label>
            <input
                class="bg-transparent text-white px-4 py-3 rounded-full border-2 border-gray-400 focus:border-green-600 outline-none"
                type="text"
                name="name"
                id="name"
                placeholder="Votre nom"
                required
                value="<?= htmlspecialchars($name) ?>">
        </div>

        <div class="flex flex-col gap-2 text-left">
            <label for="email" class="font-semibold">Email</label>
            <input
                class="bg-transparent text-white px-4 py-3 rounded-full border-2 border-gray-400 focus:border-green-600 outline-none"
                type="email"
                name="email"
                id="email"
                placeholder="Email"
                required
                value="<?= htmlspecialchars($email) ?>">
        </div>

        <div class="flex flex-col gap-2 text-left">
            <label for="password" class="font-semibold">Mot de passe</label>
            <input
                class="bg-transparent text-white px-4 py-3 rounded-full border-2 border-gray-400 focus:border-green-600 outline-none"
                type="password"
                name="password"
                id="password"
                placeholder="Mot de passe"
                required>
        </div>

        <div class="flex flex-col gap-2 text-left">
            <label for="confirm_password" class="font-semibold">Confirmer le mot de passe</label>
            <input
                class="bg-transparent text-white px-4 py-3 rounded-full border-2 border-gray-400 focus:border-green-600 outline-none"
                type="password"
                name="confirm_password"
                id="confirm_password"
                placeholder="Confirmer le mot de passe"
                required>
        </div>

        <div class="flex flex-col gap-2 text-left">
            <label for="role" class="font-semibold">Inscription en tant que</label>
            <select
                class="bg-white text-black px-4 py-3 rounded-full border-2 border-gray-400 focus:border-green-600 outline-none"
                name="role"
                id="role"
                required>
                <option value="">-- Choisir --</option>
                <option value="etudiant">Étudiant</option>
                <option value="enseignant">Enseignant</option>
            </select>
        </div>

        <button
            type="submit"
            class="bg-green-700 hover:bg-green-600 transition px-6 py-3 rounded-full text-xl font-bold border-2 border-green-700 hover:border-white mt-4">
            S'inscrire
        </button>

        <p class="text-sm mt-4">
            Déjà inscrit ?
            <a href="login.php" class="font-bold text-green-400 hover:text-green-300">
                Connectez-vous ici
            </a>
        </p>

    </form>
</body>
</html>
