<?php
require('../config/database.php');
session_start();

// Comprobacion de que sea admin
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../admin/adminLogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $city = $_POST['city'];
    $coach = $_POST['coach'];
    $img = $_POST['img'];

    // Insert
    $stmt = $_conexion->prepare("INSERT INTO teams (name, city, coach, img) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $city, $coach, $img);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Equipo agregado correctamente";
    } else {
        $_SESSION['error'] = "Error al agregar el equipo: " . $_conexion->error;
    }

    $stmt->close();
    header("Location: ./teams.php");
    exit();
}
