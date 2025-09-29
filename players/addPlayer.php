<?php
require('../config/database.php');
session_start();

// Comprobacion de que sea admin
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../admin/adminLogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $age = $_POST['age'];
    $team_id = $_POST['team_id'];
    
    // Comprobar que no hay ningun campo vacio
    if (empty($name) || empty($position) || empty($age) || empty($team_id)) {
        $_SESSION['error'] = "Todos los campos son obligatorios";
        header("Location: ../players.php");
        exit();
    }
    
    // Insert                       
    $stmt = $_conexion->prepare("INSERT INTO players (name, position, age, team_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $name, $position, $age, $team_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Jugador agregado correctamente";
    } else {
        $_SESSION['error'] = "Error al agregar el jugador: " . $_conexion->error;
    }
    
    $stmt->close();
    header("Location: ./players.php");
    exit();
}
?>