<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FootballDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="../css/adminStyles.css">
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require('../config/database.php');

    // Añadir la Clave del Captcha
    $secret_key = "";

    // Inicializacion de las variables
    $usuario_value = "";
    $contrasena_value = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["usuario"])) {
            $usuario_value = htmlspecialchars($_POST["usuario"]);
        }

        if (isset($_POST["contrasena"])) {
            $contrasena_value = htmlspecialchars($_POST["contrasena"]);
        }

        // Validacion del Captcha
        if (isset($_POST['g-recaptcha-response'])) {
            $captcha_response = $_POST['g-recaptcha-response'];

            // Verificacion Google
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$captcha_response}");
            $response_data = json_decode($response);

            if (!$response_data->success) {
                $err_captcha = "Por favor, completa el CAPTCHA.";
            } else {
                $usuario = $_POST["usuario"];
                $contrasena = $_POST["contrasena"];

                // Consulta
                $stmt = $_conexion->prepare("SELECT id, user, password FROM admins WHERE user = ?");
                $stmt->bind_param("s", $usuario);
                $stmt->execute();
                $resultado = $stmt->get_result();

                if ($resultado->num_rows == 0) {
                    $err_usuario = "El usuario no existe";
                } else {
                    $info_usuario = $resultado->fetch_assoc();
                    $storedHash = $info_usuario['password'];
                    $inputHash = hash('sha256', $contrasena);
                    
                    // Contraseña cifrada
                    if (hash_equals($storedHash, $inputHash)) {
                        session_start();
                        $_SESSION["usuario_id"] = $info_usuario["id"];
                        $_SESSION["usuario"] = $usuario;
                        $_SESSION['show_toast'] = true;
                        $_SESSION['toast_message'] = "¡Bienvenido, $usuario!";

                        header("Location: ../index.php");
                        exit;
                    } else {
                        $err_contrasena = "La contraseña es incorrecta";
                    }
                }
            }
        } else {
            $err_captcha = "Por favor, completa el CAPTCHA.";
        }
    }
    ?>
</head>

<body>
    <div class="login-container">
        <div class="admin-header">
            <h2>Acceso Administrador</h2>
        </div>

        <div class="alert alert-danger" id="error-message" role="alert">
            <span id="error-text"></span>
        </div>

        <form id="admin-login-form" action="adminLogin.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Usuario Administrador</label>
                <input type="text" class="form-control" id="username" name="usuario" placeholder="Ingresa tu usuario" value="<?php echo $usuario_value; ?>">
            </div>
            <?php if (isset($err_usuario)) echo "<span class='error-text php-error'>$err_usuario</span>" ?>

            <div class="mb-4">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="contrasena" placeholder="Ingresa tu contraseña">
            </div>
            <?php if (isset($err_contrasena)) echo "<span class='error-text php-error'>$err_contrasena</span>" ?>

            <!-- Captcha (Poner tu SecretKey en sitekey) -->
            <div class="mb-3">
                <div class="g-recaptcha" data-sitekey=""></div>
                <?php if (isset($err_captcha)) echo "<span class='error-text php-error'>$err_captcha</span>" ?>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">Ingresar</button>
            </div>
        </form>

        <hr class="my-4">

        <div>
            <p id="home">Volver a <a href="../index.php" class="text-decoration-none">página principal</a></p>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2025 FootballDesk. Todos los derechos reservados.</p>
    </div>
</body>

</html>