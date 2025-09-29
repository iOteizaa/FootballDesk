<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FootballDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/teamStyles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/teams.js"></script>

    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require('../config/database.php');
    session_start();
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
                    <li class="nav-item">
                        <a class="nav-link active" href="../index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./teams.php">Equipos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../players/players.php">Jugadores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../matches/matches.php">Partidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/adminLogin.php">Admin</a>
                    </li>
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

    <?php
    $result = $_conexion->query("SELECT * FROM teams");

    // Para poder usar ForEach
    $teams = $result->fetch_all(MYSQLI_ASSOC);
    ?>

    <!-- Cabecera -->
    <div class="hero-section text-center">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center mb-4">
                <h1 class="display-4 fw-bold">Equipos Registrados</h1>
            </div>
            <?php if (isset($_SESSION["usuario_id"])): ?>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                    <i class="bi bi-plus-circle me-1"></i>Añadir Equipo
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


    <!-- Lista de los Equipos -->
    <div class="container my-5">
        <div class="row g-4">
            <?php foreach ($teams as $team): ?>
                <div class="col-md-4">
                    <div class="card team-card h-100 shadow-sm rounded-3">
                        <!-- Eliminar -->
                        <?php if (isset($_SESSION["usuario_id"])): ?>
                            <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 delete-team-btn"
                                data-id="<?= $team['id'] ?>"
                                data-name="<?= htmlspecialchars($team['name']) ?>"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteTeamModal">
                                <i class="bi bi-trash"></i>
                            </button>
                        <?php endif; ?>
                        <img src="<?= $team['img'] ?>" alt="<?= htmlspecialchars($team['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($team['name']) ?></h5>
                            <p class="text-muted mb-1"><i class="bi bi-geo-alt me-2"></i><?= htmlspecialchars($team['city']) ?></p>
                            <p class="text-muted"><i class="bi bi-person me-2"></i><?= htmlspecialchars($team['coach']) ?></p>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="teamDetails.php?id=<?= $team['id'] ?>" class="btn btn-primary w-100">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal Agregar Equipo -->
    <div class="modal fade" id="addTeamModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Agregar Nuevo Equipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="./addTeam.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre del equipo</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ciudad</label>
                            <input type="text" class="form-control" name="city" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Entrenador</label>
                            <input type="text" class="form-control" name="coach" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">URL de la imagen del equipo</label>
                            <input type="url" class="form-control" name="img" placeholder="https://example.com/logo.png" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar Equipo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar Equipo -->
    <div class="modal fade" id="deleteTeamModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Eliminar Equipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="./deleteTeam.php" id="deleteTeamForm">
                    <div class="modal-body">
                        ¿Seguro que deseas eliminar al <strong id="deleteTeamName"></strong>?
                        <input type="hidden" name="id" id="deleteTeamId">
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