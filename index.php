<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FootballDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./css/styles.css">

    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    session_start();
    ?>

    <?php
    // Mensaje iniciar sesion
    if (isset($_SESSION['show_toast']) && $_SESSION['show_toast']): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toast = document.getElementById('toast');
                toast.textContent = '<?php echo addslashes($_SESSION['toast_message']); ?>';
                toast.style.display = 'block';

                // Eliminar el toast despues de la animación
                setTimeout(function() {
                    toast.style.display = 'none';
                }, 3000);

                // Limpiar la sesión
                <?php
                unset($_SESSION['show_toast']);
                unset($_SESSION['toast_message']);
                ?>
            });
        </script>
    <?php endif; ?>
</head>

<body>
    <div id="toast-container">
        <div id="toast" class="toast"></div>
    </div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="./index.php">
                FootballDesk
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./teams/teams.php">Equipos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./players/players.php">Jugadores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./matches/matches.php">Partidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./admin/adminLogin.php">Admin</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <?php
                    if (isset($_SESSION["usuario_id"])) {
                        echo '<span class="nav-link">Hola, ' . $_SESSION["usuario"] . '</span>';
                        echo '<a href="./admin/logout.php" class="btn btn-danger">Cerrar sesión</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Bienvenido a FootballDesk</h1>
            <p class="lead">Gestiona equipos, jugadores y partidos de forma sencilla</p>
            <a href="./teams/teams.php" class="btn btn-primary btn-lg mt-3">Ver Equipos</a>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="bi bi-people-fill display-4 text-primary"></i>
                        <h3 class="card-title">Equipos</h3>
                        <p class="card-text">Consulta la información detallada de todos los equipos registrados en el sistema.</p>
                        <a href="./teams/teams.php" class="btn btn-outline-primary">Explorar equipos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="bi bi-person-badge display-4 text-warning"></i>
                        <h3 class="card-title">Jugadores</h3>
                        <p class="card-text">Accede a los datos de los jugadores, sus posiciones y estadísticas.</p>
                        <a href="./players/players.php" class="btn btn-outline-warning">Ver jugadores</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="bi bi-calendar-event display-4 text-success"></i>
                        <h3 class="card-title">Partidos</h3>
                        <p class="card-text">Revisa el calendario de partidos y los resultados de los encuentros disputados.</p>
                        <a href="./matches/matches.php" class="btn btn-outline-success">Ver partidos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>