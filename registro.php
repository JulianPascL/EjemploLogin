<?php
require_once 'conexion.php'; // Ruta al archivo de conexión

$mensaje = ""; // Variable para almacenar el mensaje

// Procesar el formulario de registro cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = ucwords($_POST["nombre"]);
    $apellido = ucwords($_POST["apellido"]);
    $edad = $_POST["edad"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validar que la contraseña sea alfanumérica
    if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
        $mensaje = "La contraseña debe ser alfanumérica";
    } else {
        // Validar el formato del correo electrónico personalizado
        if (!isValidEmail($email)) {
            $mensaje = "No es un correo válido";
        } else {
            // Verificar si el correo ya existe en la base de datos
            $sql_verificar = "SELECT COUNT(*) AS total FROM registro_usuario WHERE email='$email'";
            $result_verificar = $conn->query($sql_verificar);
            $row_verificar = $result_verificar->fetch_assoc();
            $total_verificar = $row_verificar['total'];

            if ($total_verificar > 0) {
                $mensaje = "El usuario ya existe";
            } else {
                // Generar un ID único utilizando sha1 con la marca de tiempo y la dirección IP
                $id = sha1(uniqid() . time() . $_SERVER['REMOTE_ADDR']);

                // Insertar el registro en la base de datos
                $sql = "INSERT INTO registro_usuario (id, nombre, apellido, edad, email, password) VALUES ('$id', '$nombre', '$apellido', '$edad', '$email', '$password')";

                if ($conn->query($sql) === TRUE) {
                    $mensaje = "Registro exitoso";
                    // Redirigir al usuario a index.php después de 2 segundos
                    header("refresh:2;url=index.php");
                } else {
                    echo "Error al registrar: " . $conn->error;
                }
            }
        }
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Función de validación de correo electrónico personalizada
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Registro de usuario</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url('ropa.jpg');
      background-size: cover;
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    .container {
      background-color: rgba(255, 255, 255, 0.8);
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      padding: 20px;
      width: 300px;
      text-align: center;
    }

    .container h2 {
      margin-top: 0;
    }

    .container input[type="text"],
    .container input[type="number"],
    .container input[type="email"],
    .container input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
      box-sizing: border-box;
    }

    .container button[type="submit"] {
      width: 100%;
      padding: 10px;
      background-color: #4CAF50;
      color: #fff;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }

    .container button[type="submit"]:hover {
      background-color: #45a049;
    }

    .container .login {
      font-size: 14px;
      color: #666;
      margin-top: 10px;
    }

    .container .login a {
      color: #4CAF50;
      text-decoration: none;
    }

    .mensaje {
      margin-bottom: 10px;
      padding: 10px;
      background-color: #FF2300;
      color: #fff;
      font-weight: bold;
    }
  </style>

</head>
<body>
  <div class="container">
    <h2>Registro de usuario</h2>
    <?php if (!empty($mensaje)): ?>
      <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
      <input type="text" name="nombre" placeholder="Nombre" required>
      <br>
      <input type="text" name="apellido" placeholder="Apellido" required>
      <br>
      <input type="number" name="edad" placeholder="Edad" required>
      <br>
      <input type="email" name="email" placeholder="Correo electrónico" required>
      <br>
      <input type="password" name="password" placeholder="Contraseña" required>
      <br>
      <button type="submit">Registrarse</button>
    </form>
    <p class="login">¿Ya tienes una cuenta? <a href="index.php">Iniciar sesión</a></p>
  </div>
</body>
</html>
