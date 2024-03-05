<?php
session_start(); // Iniciar sesión

require_once 'conexion.php'; // Ruta al archivo de conexión

$mensaje = ""; // Variable para almacenar el mensaje

// Procesar el formulario de inicio de sesión cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Verificar si el correo existe en la base de datos
    $sql_verificar_email = "SELECT * FROM registro_usuario WHERE email='$email'";
    $result_verificar_email = $conn->query($sql_verificar_email);

    if ($result_verificar_email->num_rows > 0) {
        // Verificar si el correo y la contraseña coinciden en la base de datos
        $sql_verificar_password = "SELECT * FROM registro_usuario WHERE email='$email' AND password='$password'";
        $result_verificar_password = $conn->query($sql_verificar_password);

        if ($result_verificar_password->num_rows > 0) {
            // Inicio de sesión exitoso
            $_SESSION["email"] = $email;
            header("Location: dashboard.php"); // Redirigir al usuario a la página de dashboard
            exit();
        } else {
            $mensaje = "Contraseña incorrecta";
        }
    } else {
        $mensaje = "El correo no existe";
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Iniciar sesión</title>
  <link rel="stylesheet" type="text/css" href="estilo.css">
</head>
<body>
  <div class="container">
    <h2>Iniciar sesión</h2>
    <?php if (!empty($mensaje)): ?>
      <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
      <input type="email" name="email" placeholder="Correo electrónico" required>
      <br>
      <input type="password" name="password" placeholder="Contraseña" required>
      <br>
      <button type="submit">Iniciar sesión</button>
    </form>
    <p class="login">¿No tienes una cuenta? <a href="registro.php">Registrarse</a></p>
  </div>
</body>
</html>
