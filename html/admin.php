<?php 
session_start();

if ($_SESSION["currentEmail"] !== "admin@stucom.com") {
  header("Location: signin.php");
  exit();
}

// Establecemos la conexión con la base de datos (ajustando esto a una variable para reutilizar la contraseña)
$host = "localhost";
$user = "root";
$password = "YRE&zbkYJ!V+Mt8y";  // contraseña de la base de datos
$dbname = "pokewebapp";

$link = mysqli_connect($host, $user, $password, $dbname);

if (isset($_POST['email'])) {
  $email = $_POST['email'];

  $query = "SELECT nombre,correo,pokeballs FROM usuario WHERE correo = '$email'";
  $result = mysqli_query($link, $query);

  if (!$result) {
    echo mysqli_error($link);
    exit();
  }
}

// Usar la contraseña de SQL para la creación de una clave segura
use Defuse\Crypto\KeyOrPassword;

function createKey() {
    global $password;  // Accedemos a la contraseña global de SQL
    return KeyOrPassword::createFromPassword($password);
}

// Ahora puedes usar `createKey()` donde necesites la clave generada
$key = createKey();

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
