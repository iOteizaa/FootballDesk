<?php
require('../config/database.php');
session_start();

// Comprobacion de que sea admin
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../admin/adminLogin.php");
    exit();
}

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Delete
    $stmt = $_conexion->prepare("DELETE FROM players WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Jugador eliminado correctamente";
    } else {
        $_SESSION['error'] = "Error al eliminar el jugador: " . $_conexion->error;
    }

    $stmt->close();
    header("Location: ./players.php");
    exit();
} else {
    $_SESSION['error'] = "ID de jugador no válido";
    header("Location: ./players.php");
    exit();
}
