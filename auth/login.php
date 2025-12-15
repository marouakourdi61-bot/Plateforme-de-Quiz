<?php
session_start();

require_once '../config/database.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'enseignant') {
        header("Location: ../enseignant/dashboard.php");
    } else {
        header("Location: ../etudiant/dashboard.php");
    }
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';

    $query = "SELECT id, password, role FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        if (password_verify($pass, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'enseignant') {
                header("Location: ../enseignant/dashboard.php");
            } else {
                header("Location: ../etudiant/dashboard.php");
            }
            exit;
        } else {
            $error_message = "Mot de passe incorrect.";
        }
    } else {
        $error_message = "Email non trouvé.";
    }
    
    if ($error_message) {
        $error_message = "Email ou mot de passe incorrect."; 
    }

}



?>

<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>QuizMaster - Connexion</title>
  </head>
  <body class="bg-[url('https://images.unsplash.com/photo-1434725039720-aaad6dd32dfe?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8Zm9uZCUyMGRlJTIwcGF5c2FnZXxlbnwwfHwwfHx8MA%3D%3D')] bg-center bg-cover bg-no-repeat h-[100vh] flex justify-center items-center relative">

    <div class="w-screen h-[100vh] bg-black opacity-50 absolute top-0 left-0"></div>

    <form method="POST" action="" class="relative text-white text-center flex items-center flex-col gap-4 border-2 rounded-xl px-8 w-full max-w-md py-10 bg-black bg-opacity-30 backdrop-blur-sm">
      
      <h1 class="text-3xl uppercase font-extrabold mb-4">Se Connecter</h1>
      
      <?php if ($error_message): ?>
          <p class="text-red-400 font-semibold border border-red-400 p-2 rounded-md w-full"><?php echo $error_message; ?></p>
      <?php endif; ?>

      <div class="flex flex-col gap-2 w-full">
          <label class="text-xl font-semibold text-left" for="email">Email</label>
          <input class="bg-transparent text-white outline-none px-4 py-3 rounded-full border-2 border-gray-400 focus:border-purple-600" 
                 type="email" name="email" id="email" placeholder="Entrez votre email" required>
      </div>
      
      <div class="flex flex-col gap-2 w-full">
          <label class="text-xl font-semibold text-left" for="password">Mot de passe</label>
          <input class="bg-transparent text-white outline-none px-4 py-3 rounded-full border-2 border-gray-400 focus:border-purple-600" 
                 type="password" name="password" id="password" placeholder="Mot de passe" required>
      </div>

      <button type="submit" class="bg-purple-700 px-6 py-3 rounded-full text-xl w-3/4 mx-auto mt-6 font-bold hover:bg-purple-600 transition duration-300 border-2 border-purple-700 hover:border-white">
          Connexion
      </button>

      <p class="mt-4 text-sm">
          Pas encore de compte ? 
          <a href="register.php" class="font-bold text-purple-400 hover:text-purple-300">Inscrivez-vous ici</a>.
      </p>
    </form>
    
  </body>
</html>

<?php 
$users_data = [
    ['Mohammed Alami', 'alami@gmail.com', 'enseignant'],
    ['Fatima Zahra', 'fatima@gmail.com', 'enseignant'],
    ['Ahmed Bennani', 'ahmed@gmail.com', 'etudiant'],
    ['Salma Idrissi', 'salma@gmail.com', 'etudiant'],
    ['Youssef Tazi', 'youssef@gmail.com', 'etudiant'],
];

$sql = "INSERT INTO users (name, email, password, role) 
        VALUES (:name, :email, :password, :role) 
        ON DUPLICATE KEY UPDATE name=VALUES(name)"; 
        
 $user       
?>