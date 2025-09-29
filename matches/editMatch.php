<?php
session_start();
require('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Datos formulario
    $match_id = intval($_POST['id']);
    $home_team_name = $_POST['home_team_name'];
    $away_team_name = $_POST['away_team_name'];
    $match_date = $_POST['match_date'];
    $stadium = $_POST['stadium'];
    $competition = $_POST['competition'];

    // Resultado Vacio = Por jugar
    $home_score = $_POST['home_score'];
    $away_score = $_POST['away_score'];

    // Si esta vacio = NULL
    if ($home_score === '') {
        $home_score = NULL;
    } else {
        $home_score = intval($home_score);
    }

    if ($away_score === '') {
        $away_score = NULL;
    } else {
        $away_score = intval($away_score);
    }

    // Update
    $stmt = $_conexion->prepare("
        UPDATE matches
        SET home_team_name=?, away_team_name=?, match_date=?, stadium=?, competition=?, home_score=?, away_score=?
        WHERE id=?
    ");
    $stmt->bind_param(
        "sssssiii",
        $home_team_name,
        $away_team_name,
        $match_date,
        $stadium,
        $competition,
        $home_score,
        $away_score,
        $match_id
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "Partido actualizado correctamente.";
        header("Location: ./matches.php");
        exit;
    } else {
        $_SESSION['error'] = "No se pudo actualizar el partido.";
        header("Location: ./matches.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Acceso no permitido.";
    header("Location: ./matches.php");
    exit;
}
