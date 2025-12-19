<?php 
<?php
session_start();
require_once '../config/database.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
if ($_SESSION['role'] !== 'etudiant') {
    header("Location: ../auth/login.php");
    exit;
}



include '../includes/header.php';






?>






?>