<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "lista_tareas");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $rol = $_POST["rol"];

    // Validación básica
    if ($username && $password && ($rol == "admin" || $rol == "usuario")) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuarios (username, password, rol) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hash, $rol);

        if ($stmt->execute()) {
            $mensaje = "✅ Usuario creado correctamente.";
        } else {
            $mensaje = "❌ Error: " . $conn->error;
        }
    } else {
        $mensaje = "⚠️ Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Usuario</title>
  <style>
    body {
      background: #e0f2fe;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }

    .register-box {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 320px;
    }

    h2 {
      text-align: center;
      color: #0ea5e9;
      margin-bottom: 20px;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
    }

    input[type="submit"] {
      background-color: #0ea5e9;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #0284c7;
    }

    .msg {
      text-align: center;
      margin-top: 10px;
      font-size: 14px;
      color: #333;
    }
  </style>
</head>
<body>
  <div class="register-box">
    <h2>Crear Usuario</h2>
    <form method="POST" action="">
      <input type="text" name="username" placeholder="Nombre de usuario" required>
      <input type="password" name="password" placeholder="Contraseña" required>
      <select name="rol" required>
        <option value="">-- Seleccionar Rol --</option>
        <option value="admin">Admin</option>
        <option value="usuario">Usuario</option>
      </select>
      <input type="submit" value="Registrar">
    </form>
    <?php if ($mensaje): ?>
      <div class="msg"><?php echo $mensaje; ?></div>
    <?php endif; ?>
  </div>
</body>
</html>
