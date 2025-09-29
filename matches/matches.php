<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FootballDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/matchesStyles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/matches.js"></script>

    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require('../config/database.php');
    session_start();

    // Array para guardar los partidos (Por jugar y finalizados)
    $upcoming = [];
    $finished = [];

    // Si se envia el Id del equipo solo se muestran los partidos de ese equipo
    if (isset($_GET['team_id'])) {
        $team_id = intval($_GET['team_id']);
        $stmt = $_conexion->prepare("SELECT name FROM teams WHERE id = ?");
        $stmt->bind_param("i", $team_id);
        $stmt->execute();
        $team_result = $stmt->get_result();
        $team = $team_result->fetch_assoc();

        if (!$team) {
            echo "<p>Equipo no encontrado.</p>";
            exit;
        }

        $team_name = $team['name'];

        $sql = "SELECT * FROM matches
                WHERE home_team_name = ? OR away_team_name = ?
                ORDER BY match_date ASC";
        $stmt_matches = $_conexion->prepare($sql);
        $stmt_matches->bind_param("ss", $team_name, $team_name);
        $stmt_matches->execute();
        $result = $stmt_matches->get_result();
    }
    // Si no hay Id se consultan todos los partidos
    else {
        $sql = "SELECT * FROM matches ORDER BY match_date ASC";
        $result = $_conexion->query($sql);
    }

    // Se clasifican los partidos (No resultado = No jugado)
    while ($match = $result->fetch_assoc()) {
        if ($match['home_score'] !== null && $match['away_score'] !== null) {
            $finished[] = $match;
        } else {
            $upcoming[] = $match;
        }
    }
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
                    <li class="nav-item"><a class="nav-link active" href="../index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="../teams/teams.php">Equipos</a></li>
                    <li class="nav-item"><a class="nav-link" href="../players/players.php">Jugadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="./matches.php">Partidos</a></li>
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
    <div class="matches-hero-section text-center">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center mb-4">
                <h1 class="display-4 fw-bold">Partidos</h1>
            </div>
            <?php if (isset($_SESSION["usuario_id"])): ?>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMatchModal">
                    <i class="bi bi-plus-circle me-1"></i>Añadir Partido
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">

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

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4" id="matchesTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab">Próximos Partidos</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="finished-tab" data-bs-toggle="tab" data-bs-target="#finished" type="button" role="tab">Partidos Finalizados</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Próximos Partidos -->
                    <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
                        <div class="row">
                            <?php foreach ($upcoming as $match): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card match-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="badge bg-info"><?= htmlspecialchars($match['competition']) ?></span>
                                                <small class="text-muted"><?= date("d M Y - H:i", strtotime($match['match_date'])) ?></small>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-center">
                                                    <p class="mt-2 mb-0"><?= htmlspecialchars($match['home_team_name']) ?></p>
                                                </div>
                                                <div class="text-center">
                                                    <h3>VS</h3>
                                                    <p class="mb-0"><?= htmlspecialchars($match['stadium']) ?></p>
                                                </div>
                                                <div class="text-center">
                                                    <p class="mt-2 mb-0"><?= htmlspecialchars($match['away_team_name']) ?></p>
                                                </div>
                                            </div>
                                            <!-- Botones -->
                                            <?php if (isset($_SESSION["usuario_id"])): ?>
                                                <div class="mt-3 text-end">
                                                    <button class="btn btn-warning btn-sm edit-match-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editMatchModal"
                                                        data-id="<?= $match['id'] ?>"
                                                        data-home="<?= htmlspecialchars($match['home_team_name']) ?>"
                                                        data-away="<?= htmlspecialchars($match['away_team_name']) ?>"
                                                        data-date="<?= date('Y-m-d\TH:i', strtotime($match['match_date'])) ?>"
                                                        data-competition="<?= htmlspecialchars($match['competition']) ?>"
                                                        data-stadium="<?= htmlspecialchars($match['stadium']) ?>"
                                                        data-home-score="<?= $match['home_score'] ?>"
                                                        data-away-score="<?= $match['away_score'] ?>">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm delete-match-btn"
                                                        data-bs-toggle="modal" data-bs-target="#deleteMatchModal"
                                                        data-id="<?= $match['id'] ?>"
                                                        data-home="<?= htmlspecialchars($match['home_team_name']) ?>"
                                                        data-away="<?= htmlspecialchars($match['away_team_name']) ?>"
                                                        data-date="<?= date('d M Y', strtotime($match['match_date'])) ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Partidos Finalizados -->
                    <div class="tab-pane fade" id="finished" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Partido</th>
                                        <th>Resultado</th>
                                        <th>Competición</th>
                                        <?php if (isset($_SESSION["usuario_id"])): ?>
                                            <th>Acciones</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($finished as $match): ?>
                                        <tr>
                                            <td><?= date("d M Y", strtotime($match['match_date'])) ?></td>
                                            <td><?= htmlspecialchars($match['home_team_name']) ?> vs <?= htmlspecialchars($match['away_team_name']) ?></td>
                                            <td><?= $match['home_score'] ?> - <?= $match['away_score'] ?></td>
                                            <td><?= htmlspecialchars($match['competition']) ?></td>
                                            <?php if (isset($_SESSION["usuario_id"])): ?>
                                                <td>
                                                    <button class="btn btn-warning btn-sm edit-match-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editMatchModal"
                                                        data-id="<?= $match['id'] ?>"
                                                        data-home="<?= htmlspecialchars($match['home_team_name']) ?>"
                                                        data-away="<?= htmlspecialchars($match['away_team_name']) ?>"
                                                        data-date="<?= date('Y-m-d\TH:i', strtotime($match['match_date'])) ?>"
                                                        data-competition="<?= htmlspecialchars($match['competition']) ?>"
                                                        data-stadium="<?= htmlspecialchars($match['stadium']) ?>"
                                                        data-home-score="<?= $match['home_score'] ?>"
                                                        data-away-score="<?= $match['away_score'] ?>">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm delete-match-btn"
                                                        data-bs-toggle="modal" data-bs-target="#deleteMatchModal"
                                                        data-id="<?= $match['id'] ?>"
                                                        data-home="<?= htmlspecialchars($match['home_team_name']) ?>"
                                                        data-away="<?= htmlspecialchars($match['away_team_name']) ?>"
                                                        data-date="<?= date('d M Y', strtotime($match['match_date'])) ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Añadir Partido -->
    <div class="modal fade" id="addMatchModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="./addMatch.php" method="POST">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Añadir Partido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Equipo Local</label>
                                <input type="text" class="form-control" name="home_team_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Equipo Visitante</label>
                                <input type="text" class="form-control" name="away_team_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha y Hora</label>
                                <input type="datetime-local" class="form-control" name="match_date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Competición</label>
                                <input type="text" class="form-control" name="competition" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estadio</label>
                                <input type="text" class="form-control" name="stadium">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Partido -->
    <div class="modal fade" id="editMatchModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="./editMatch.php" method="POST">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">Editar Partido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_match_id">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Equipo Local</label>
                                <input type="text" class="form-control" name="home_team_name" id="edit_home_team" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Equipo Visitante</label>
                                <input type="text" class="form-control" name="away_team_name" id="edit_away_team" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha y Hora</label>
                                <input type="datetime-local" class="form-control" name="match_date" id="edit_match_date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Competición</label>
                                <input type="text" class="form-control" name="competition" id="edit_competition" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estadio</label>
                                <input type="text" class="form-control" name="stadium" id="edit_stadium">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Marcador (Local - Visitante)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="home_score" id="edit_home_score" min="0">
                                    <span class="input-group-text">-</span>
                                    <input type="number" class="form-control" name="away_score" id="edit_away_score" min="0">
                                </div>
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

    <!-- Modal Borrar Partido -->
    <div class="modal fade" id="deleteMatchModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="./deleteMatch.php" method="POST">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Eliminar Partido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="delete_match_id">
                        <p>¿Seguro que deseas eliminar este partido?</p>
                        <p class="fw-bold" id="delete_match_info"></p>
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