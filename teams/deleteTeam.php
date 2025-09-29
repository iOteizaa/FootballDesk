<?php
session_start();
require('../config/database.php');

// Comprobacion de que sea admin
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../admin/adminLogin.php");
    exit;
}

if (isset($_POST['id'])) {
    $team_id = intval($_POST['id']);

    // Delete
    $stmt = $_conexion->prepare("DELETE FROM teams WHERE id = ?");
    $stmt->bind_param("i", $team_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Equipo eliminado correctamente.";
    } else {
        $_SESSION['error'] = "Error al eliminar el equipo.";
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "No se especificó ningún equipo para eliminar.";
}

header("Location: ./teams.php");
exit;
