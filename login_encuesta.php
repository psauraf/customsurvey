<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userid = $_POST['userid'];
    $password = $_POST['password'];

    $db = new mysqli('localhost', 'root', '', 'encuestas');
    if ($db->connect_error) {
        die("Conexión fallida: " . $db->connect_error);
    }

    $query = "SELECT login, tipoUsuario FROM usuario WHERE login = ? AND password = SHA1(?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $userid, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['login'] = $user['login'];
        $_SESSION['tipoUsuario'] = $user['tipoUsuario'];

        header("Location: principal.php");
        exit();
    } else {
        echo "<p style='color: red; text-align: center;'>Login o contraseña incorrectos. <a href='login_encuesta.php'>Intentar de nuevo</a></p>";
    }

    $db->close();
} else {
    echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Estilos para el formulario */
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ffffff;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .login-container h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            margin-bottom: 15px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #3399ff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #1a8cff;
        }

        .message {
            color: red;
            margin-top: 15px;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Acceso aplicación Encuestas</h1>
        <form action="login_encuesta.php" method="POST">
            <label for="userid">Usuario:</label>
            <input type="text" id="userid" name="userid" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Entrar">
        </form>
    </div>
</body>
</html>';
}
?>
