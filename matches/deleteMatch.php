<?php
session_start();
require('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ID partido
    $match_id = intval($_POST['id']);

    // Delete
    $stmt = $_conexion->prepare("DELETE FROM matches WHERE id = ?");
    $stmt->bind_param("i", $match_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Partido eliminado correctamente.";
        header("Location: ./matches.php");
        exit;
    } else {
        $_SESSION['error'] = "No se pudo eliminar el partido.";
        header("Location: ./matches.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Acceso no permitido.";
    header("Location: ./matches.php");
    exit;
}
