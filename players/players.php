<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FootballDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/filterPlayers.js"></script>
    <script src="../js/players.js"></script>
    <link rel="stylesheet" href="../css/playersStyles.css">
</head>

<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require('../config/database.php');
session_start();
?>

<?php
// Consulta para obtener los nombres de cada jugador junto al nombre de su respectivo equipo
$sql = "
    SELECT players.*, teams.name AS team_name
    FROM players
    INNER JOIN teams ON players.team_id = teams.id
    ORDER BY FIELD(players.position, 'Portero', 'Defensa', 'Centrocampista', 'Delantero'),
             players.name ASC";
$result = $_conexion->query($sql);
?>

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
                    <li class="nav-item"><a class="nav-link active" href="../index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="../teams/teams.php">Equipos</a></li>
                    <li class="nav-item"><a class="nav-link" href="./players.php">Jugadores</a></li>
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
                <h1 class="display-4 fw-bold">Jugadores Registrados</h1>
            </div>
            <?php if (isset($_SESSION["usuario_id"])): ?>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPlayerModal">
                    <i class="bi bi-plus-circle me-1"></i>Agregar Jugador
                </button>
            <?php endif; ?>
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
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Buscar jugador..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button" id="searchButton">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-md-end">
                    <?php
                    // Obtenemos los nombres de los equipos
                    $teamsResult = $_conexion->query("SELECT id, name FROM teams ORDER BY name ASC");
                    ?>
                    <select class="form-select me-2" id="teamFilter" style="max-width: 200px;">
                        <option value="">Todos los equipos</option>
                        <?php while ($team = $teamsResult->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($team['name']) ?>">
                                <?= htmlspecialchars($team['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <select class="form-select" id="positionFilter" style="max-width: 200px;">
                        <option value="">Todas las posiciones</option>
                        <option value="Portero">Portero</option>
                        <option value="Defensa">Defensa</option>
                        <option value="Centrocampista">Centrocampista</option>
                        <option value="Delantero">Delantero</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Jugadores -->
        <div class="row" id="playersContainer">
            <?php
            while ($player = $result->fetch_assoc()):
                $badgeClass = "bg-secondary"; // Color por defecto

                switch ($player['team_name']) {
                    case 'FC Barcelona':
                        $badgeClass = "bg-primary";
                        break;
                    case 'Real Madrid':
                        $badgeClass = "bg-light text-dark border";
                        break;
                    case 'Atlético de Madrid':
                        $badgeClass = "bg-danger";
                        break;
                    case 'Real Sociedad':
                        $badgeClass = "bg-info text-dark";
                        break;
                    default:
                        $colors = ['bg-success', 'bg-warning text-dark', 'bg-info text-dark', 'bg-danger', 'bg-secondary', 'bg-dark text-white', 'bg-primary'];
                        // Se elige un color con random
                        $badgeClass = $colors[array_rand($colors)];
                        break;
                }
            ?>
                <!-- Jugadores -->
                <div class="col-md-6 col-lg-4 col-xl-3 mb-4"
                    data-team="<?= htmlspecialchars($player['team_name']) ?>"
                    data-position="<?= htmlspecialchars($player['position']) ?>">
                    <div class="card player-card h-100">
                        <div class="card-body text-center">
                            <span class="badge <?= $badgeClass ?> mb-2"><?= htmlspecialchars($player['team_name']) ?></span>
                            <h5 class="card-title"><?= htmlspecialchars($player['name']) ?></h5>
                            <span class="badge bg-secondary position-badge"><?= htmlspecialchars($player['position']) ?></span>
                            <p class="card-text mt-2"><i class="bi bi-person me-2"></i><?= htmlspecialchars($player['age']) ?> años</p>
                            <div class="d-flex justify-content-center gap-2 mt-3">
                                <!-- Boton para editar -->
                                <?php if (isset($_SESSION["usuario_id"])): ?>
                                    <button class="btn btn-warning btn-sm edit-player-btn"
                                        data-id="<?= $player['id'] ?>"
                                        data-name="<?= htmlspecialchars($player['name']) ?>"
                                        data-position="<?= $player['position'] ?>"
                                        data-age="<?= $player['age'] ?>"
                                        data-team-id="<?= $player['team_id'] ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editPlayerModal">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <!-- Boton para eliminar -->
                                    <button class="btn btn-danger btn-sm delete-player-btn"
                                        data-id="<?= $player['id'] ?>"
                                        data-name="<?= htmlspecialchars($player['name']) ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deletePlayerModal">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Modal Añadir Jugador -->
    <div class="modal fade" id="addPlayerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Añadir Nuevo Jugador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="./addPlayer.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Posición</label>
                            <select class="form-select" name="position" required>
                                <option value="">Seleccionar posición</option>
                                <option value="Portero">Portero</option>
                                <option value="Defensa">Defensa</option>
                                <option value="Centrocampista">Centrocampista</option>
                                <option value="Delantero">Delantero</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Edad</label>
                            <input type="number" class="form-control" name="age" min="16" max="50" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Equipo</label>
                            <select class="form-select" name="team_id" required>
                                <option value="">Seleccionar equipo</option>
                                <?php
                                // Obtener todos los equipos registrados
                                $teamsQuery = "SELECT id, name FROM teams ORDER BY name ASC";
                                $teamsResult = $_conexion->query($teamsQuery);
                                while ($team = $teamsResult->fetch_assoc()):
                                ?>
                                    <option value="<?= $team['id'] ?>"><?= htmlspecialchars($team['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Añadir Jugador</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Jugador -->
    <div class="modal fade" id="editPlayerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Editar Jugador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="./editPlayer.php" method="POST" id="editPlayerForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editPlayerId">

                        <div class="mb-3">
                            <label class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" name="name" id="editPlayerName" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Posición</label>
                            <select class="form-select" name="position" id="editPlayerPosition" required>
                                <option value="">Seleccionar posición</option>
                                <option value="Portero">Portero</option>
                                <option value="Defensa">Defensa</option>
                                <option value="Centrocampista">Centrocampista</option>
                                <option value="Delantero">Delantero</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Edad</label>
                            <input type="number" class="form-control" name="age" id="editPlayerAge" min="16" max="50" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Equipo</label>
                            <select class="form-select" name="team_id" id="editPlayerTeam" required>
                                <option value="">Seleccionar equipo</option>
                                <?php
                                // Obtener todos los equipos registrados
                                $teamsQuery = "SELECT id, name FROM teams ORDER BY name ASC";
                                $teamsResult = $_conexion->query($teamsQuery);
                                while ($team = $teamsResult->fetch_assoc()):
                                ?>
                                    <option value="<?= $team['id'] ?>"><?= htmlspecialchars($team['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
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

    <!-- Modal Borrar Jugador -->
    <div class="modal fade" id="deletePlayerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Eliminar Jugador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="./deletePlayer.php" id="deletePlayerForm">
                    <div class="modal-body">
                        ¿Seguro que quiere eliminar a <strong id="deletePlayerName"></strong>?
                        <input type="hidden" name="id" id="deletePlayerId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>