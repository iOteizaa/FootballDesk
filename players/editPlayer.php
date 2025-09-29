<?php
require('../config/database.php');
session_start();

// Comprobacion de que sea admin
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../admin/adminLogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $age = $_POST['age'];
    $team_id = $_POST['team_id'];

    // Comprobar que los campos no estan vacios
    if (empty($id) || empty($name) || empty($position) || empty($age) || empty($team_id)) {
        $_SESSION['error'] = "Todos los campos son obligatorios";
        header("Location: ../teams/players.php");
        exit();
    }

    // Update
    $stmt = $_conexion->prepare("UPDATE players SET name=?, position=?, age=?, team_id=? WHERE id=?");
    $stmt->bind_param("ssiii", $name, $position, $age, $team_id, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Jugador actualizado correctamente";
    } else {
        $_SESSION['error'] = "Error al actualizar el jugador: " . $_conexion->error;
    }

    $stmt->close();
    header("Location: ./players.php");
    exit();
}
?>
