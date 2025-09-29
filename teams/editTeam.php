<?php
require('../config/database.php');
session_start();

// Comprobacion de que sea admin
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../admin/adminLogin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Datos del formulario
    $team_id = intval($_POST['id']);
    $name = $_POST['name'];
    $city = $_POST['city'];
    $coach = $_POST['coach'];
    $img = $_POST['img'];

    $matches_played = intval($_POST['matches_played']);
    $wins = intval($_POST['wins']);
    $draws = intval($_POST['draws']);
    $losses = intval($_POST['losses']);
    $goals_for = intval($_POST['goals_for']);
    $goals_against = intval($_POST['goals_against']);
    $points = intval($_POST['points']);

    // Actualizar datos del equipo
    $stmt_team = $_conexion->prepare("
        UPDATE teams SET name = ?, city = ?, coach = ?, img = ?
        WHERE id = ?
    ");
    $stmt_team->bind_param("ssssi", $name, $city, $coach, $img, $team_id);
    $stmt_team->execute();
    $stmt_team->close();

    // Comprobar si existen estadísticas para este equipo
    $stmt_check = $_conexion->prepare("SELECT id FROM team_stats WHERE team_id = ?");
    $stmt_check->bind_param("i", $team_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    // Si existe se realiza un Update
    if ($stmt_check->num_rows > 0) {
        $stmt_update = $_conexion->prepare("
            UPDATE team_stats SET
                matches_played = ?,
                wins = ?,
                draws = ?,
                losses = ?,
                goals_for = ?,
                goals_against = ?,
                points = ?
            WHERE team_id = ?
        ");

        $stmt_update->bind_param("iiiiiiii", $matches_played, $wins, $draws, $losses, $goals_for, $goals_against, $points, $team_id);
        $stmt_update->execute();
        $stmt_update->close();

        // Si no existe se realiza un insert    
    } else {
        $stmt_insert = $_conexion->prepare("
            INSERT INTO team_stats (team_id, matches_played, wins, draws, losses, goals_for, goals_against, points)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt_insert->bind_param("iiiiiiii", $team_id, $matches_played, $wins, $draws, $losses, $goals_for, $goals_against, $points);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    $stmt_check->close();

    $_SESSION['success'] = "Equipo actualizado correctamente.";
    header("Location: ./teams.php");
    exit;
} else {
    $_SESSION['error'] = "Acceso no permitido.";
    header("Location: ./teams.php");
    exit;
}
