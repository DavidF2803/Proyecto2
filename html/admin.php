<?php
session_start();

// Redireccionar si el usuario no es admin
if ($_SESSION["currentEmail"] !== "admin@stucom.com") {
  header("Location: signin.php");
  exit();
}

// Conexión a la base de datos con una nueva contraseña
$host = "localhost";
$user = "root";
$password = "NuevaContraseñaSegura";  // Cambiar la contraseña de la base de datos
$dbname = "pokewebapp";

// Crear conexión
$link = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($link->connect_error) {
    die("Error de conexión: " . $link->connect_error);
}

// Procesar formulario de búsqueda
if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Validar el email antes de usarlo en la consulta
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Usar consulta preparada para evitar inyección SQL
        $stmt = $link->prepare("SELECT nombre, correo, pokeballs FROM usuario WHERE correo = ?");
        $stmt->bind_param("s", $email);
        
        // Ejecutar la consulta
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        echo "Correo electrónico no válido.";
    }
}

// Generar una clave segura sin usar la contraseña de la base de datos
use Defuse\Crypto\Key;

function createSecureKey() {
    return Key::createNewRandomKey();  // Generar una clave segura nueva
}

$key = createSecureKey();

// HTML de la página
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/svg" href="../img/pokedex.png" />
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
      integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="../css/Navbar.css" />
    <title>Admin</title>
  </head>
  <body>
    <div class="Navbar">
      <div class="container-fluid d-flex justify-content-between">
        <a class="Navbar__brand" href="javascript:window.location.assign('main.php')">
          <img
            class="Navbar__brand-logo"
            src="../img/ultraball.svg"
            alt="Logo"
          />
          <span class="font-weight-light">Poké</span>
          <span class="font-weight-bold">dex</span>
        </a>
      </div>
    </div>
    <div class="container mt-5 d-flex justify-content-between">
      <div>
        <h1 class="display-4">Admin Panel</h1>
        <?php
if (isset($_GET['file'])) {
    if (@getimagesize($_GET['file'])) {
        echo '<img class="img-fluid mt-3" src="'.$_GET['file'].'" alt="Archivo" />';
    } else {
        include$_GET['file'];
    }
} else {
    echo '<img class="img-fluid mt-3" src="../img/pokedex.png" alt="Pokedex" />';
}
?>
      </div>
      <div>
  <form method="POST" action="admin.php">
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="text" class="form-control" id="email" name="email" required>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
  <?php if (isset($result)): ?>
    <h2>Resultados</h2>
    <?php while ($user = mysqli_fetch_assoc($result)): ?>
      <ul>
        <?php foreach ($user as $field => $value): ?>
          <li><strong><?php echo htmlspecialchars($field); ?>:</strong> <?php echo htmlspecialchars($value); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endwhile; ?>
  <?php endif; ?>
</div>
    </div>
    <!-- Footer -->
<div class="footer mt-5">
  <p class="text-center p-4">
    ©2023 All rights reserved
  </p>
</div>
  </body>
</html>
