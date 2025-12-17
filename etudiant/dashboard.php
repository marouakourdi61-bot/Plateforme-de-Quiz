<?php 
include '../config/database.php';





session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'etudiant') {
    header("Location: ../auth/login.php");
    exit;
}




?>