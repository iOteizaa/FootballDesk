<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FootballDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/teamStyles.css">
    <script src="../js/teamDetails.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require('../config/database.php');
    session_start();

    // Id para mostrar pagina de cada equipo
    if (!isset($_GET['id'])) {
        echo "<p>No se especificó ningún equipo.</p>";
        exit;
    }

    $team_id = intval($_GET['id']);

    // Consulta del equipo seleccionado
    $stmt = $_conexion->prepare("SELECT * FROM teams WHERE id = ?");
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $team_result = $stmt->get_result();
    $team = $team_result->fetch_assoc();
    if (!$team) {
        echo "<p>Equipo no encontrado.</p>";
        exit;
    }

    // Constulta de las estadisticas del equipo
    $stmt_stats = $_conexion->prepare("SELECT * FROM team_stats WHERE team_id = ?");
    $stmt_stats->bind_param("i", $team_id);
    $stmt_stats->execute();
    $stats_result = $stmt_stats->get_result();
    $stats = $stats_result->fetch_assoc();

    // Consulta de los jugadores del equipo
    $stmt_players = $_conexion->prepare("SELECT * FROM players WHERE team_id = ?");
    $stmt_players->bind_param("i", $team_id);
    $stmt_players->execute();
    $players_result = $stmt_players->get_result();
    ?>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                FootballDesk
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link active" href="./teams.php">Equipos</a></li>
                    <li class="nav-item"><a class="nav-link" href="../players/players.php">Jugadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="../matches/matches.php">Partidos</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/adminLogin.php">Admin</a></li>
                </ul>

                <?php
                if (isset($_SESSION["usuario_id"])) {
                    echo '<span class="nav-link">Hola, ' . $_SESSION["usuario"] . '</span>';
                    echo '<a href="../admin/logout.php" class="btn btn-danger">Cerrar sesión</a>';
                }
                ?>

            </div>
        </div>
    </nav>

    <!-- Cabecera -->
    <div class="hero-section text-center">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center mb-4">
                <h1 class="display-4 fw-bold">Detalles del Equipo</h1>
            </div>
            <p class="lead">Información sobre el <?= htmlspecialchars($team['name']) ?></p>
        </div>
    </div>

    <!-- Mensajes -->
    <div class="container mt-3">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </div>

    <!-- Contenido principal -->
    <div class="container my-5">
        <div class="row">
            <!-- Informacion y Estadisticas a la izquierda -->
            <div class="col-md-4">
                <div class="card mb-3">
                    <img src="<?= $team['img'] ?>" class="card-img-top" alt="<?= htmlspecialchars($team['name']) ?>">
                    <div class="card-body text-center">
                        <h2 class="card-title"><?= htmlspecialchars($team['name']) ?></h2>
                        <p class="card-text"><i class="bi bi-geo-alt me-2"></i><?= htmlspecialchars($team['city']) ?></p>
                        <p class="card-text"><i class="bi bi-person me-2"></i>Entrenador: <?= htmlspecialchars($team['coach']) ?></p>
                    </div>
                </div>
                <?php if (isset($_SESSION["usuario_id"])): ?>
                    <div class="d-grid gap-2 mb-3">
                        <button class="btn btn-warning edit-team-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#editTeamModal"
                            data-id="<?= $team['id'] ?>"
                            data-name="<?= htmlspecialchars($team['name']) ?>"
                            data-city="<?= htmlspecialchars($team['city']) ?>"
                            data-coach="<?= htmlspecialchars($team['coach']) ?>"
                            data-img="<?= htmlspecialchars($team['img']) ?>"
                            data-matches-played="<?= $stats['matches_played'] ?? 0 ?>"
                            data-wins="<?= $stats['wins'] ?? 0 ?>"
                            data-draws="<?= $stats['draws'] ?? 0 ?>"
                            data-losses="<?= $stats['losses'] ?? 0 ?>"
                            data-goals-for="<?= $stats['goals_for'] ?? 0 ?>"
                            data-goals-against="<?= $stats['goals_against'] ?? 0 ?>"
                            data-points="<?= $stats['points'] ?? 0 ?>">
                            <!-- Si la sesion esta iniciada sale el boton de editar y Partidos sino solo Partidos -->
                            <i class="bi bi-pencil-square me-1"></i> Editar Equipo
                        </button>
                        <a href="../matches/matches.php?team_id=<?= $team['id'] ?>" class="btn btn-primary">
                            <i class="bi bi-calendar-event me-1"></i> Ver Partidos
                        </a>
                    </div>
                <?php else: ?>
                    <div class="d-grid mb-3">
                        <a href="../matches/matches.php?team_id=<?= $team['id'] ?>" class="btn btn-primary">
                            <i class="bi bi-calendar-event me-1"></i> Ver Partidos
                        </a>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Estadísticas</h5>
                    </div>
                    <div class="card-body">
                        <!-- Si no existe el dato se pone 0 -->
                        <div class="d-flex justify-content-between mb-2"><span>Partidos jugados:</span><strong><?= $stats['matches_played'] ?? 0 ?></strong></div>
                        <div class="d-flex justify-content-between mb-2"><span>Victorias:</span><strong><?= $stats['wins'] ?? 0 ?></strong></div>
                        <div class="d-flex justify-content-between mb-2"><span>Empates:</span><strong><?= $stats['draws'] ?? 0 ?></strong></div>
                        <div class="d-flex justify-content-between mb-2"><span>Derrotas:</span><strong><?= $stats['losses'] ?? 0 ?></strong></div>
                        <div class="d-flex justify-content-between mb-2"><span>Goles a favor:</span><strong><?= $stats['goals_for'] ?? 0 ?></strong></div>
                        <div class="d-flex justify-content-between mb-2"><span>Goles en contra:</span><strong><?= $stats['goals_against'] ?? 0 ?></strong></div>
                        <div class="d-flex justify-content-between"><span>Puntos:</span><strong><?= $stats['points'] ?? 0 ?></strong></div>
                    </div>
                </div>
            </div>

            <!-- Jugadores a la derecha -->
            <div class="col-md-8">
                <h3>Jugadores</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Posición</th>
                                <th>Edad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($player = $players_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($player['name']) ?></td>
                                    <td><?= htmlspecialchars($player['position']) ?></td>
                                    <td><?= htmlspecialchars($player['age']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Editar equipo y estadisticas -->
    <div class="modal fade" id="editTeamModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Editar Equipo y Estadísticas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="./editTeam.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre del equipo</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ciudad</label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Entrenador</label>
                                <input type="text" name="coach" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Imagen URL</label>
                                <input type="text" name="img" class="form-control">
                            </div>
                        </div>
                        <hr>
                        <h5>Estadísticas</h5>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Partidos jugados</label>
                                <input type="number" name="matches_played" class="form-control" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Victorias</label>
                                <input type="number" name="wins" class="form-control" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Empates</label>
                                <input type="number" name="draws" class="form-control" min="0">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Derrotas</label>
                                <input type="number" name="losses" class="form-control" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Goles a favor</label>
                                <input type="number" name="goals_for" class="form-control" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Goles en contra</label>
                                <input type="number" name="goals_against" class="form-control" min="0">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Puntos</label>
                                <input type="number" name="points" class="form-control" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>