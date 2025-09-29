<?php
require('../config/database.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Datos formulario
    $home_team_name = $_POST['home_team_name'];
    $away_team_name = $_POST['away_team_name'];
    $competition = $_POST['competition'];
    $stadium = $_POST['stadium'];
    $match_date = $_POST['match_date'];

    // Insert
    $stmt = $_conexion->prepare("
        INSERT INTO matches (home_team_name, away_team_name, competition, stadium, match_date)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssss", $home_team_name, $away_team_name, $competition, $stadium, $match_date);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Partido agregado correctamente.";
        header("Location: ./matches.php");
        exit;
    } else {
        $_SESSION['error'] = "No se pudo agregar el partido.";
        header("Location: ./matches.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Acceso no permitido.";
    header("Location: ./matches.php");
    exit;
}
