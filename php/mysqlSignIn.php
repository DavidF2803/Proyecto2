<?php
$message = "";

// Establecemos la conexión con la base de datos (ajustando esto a una variable para reutilizar la contraseña)
$host = "localhost";
$user = "root";
$password = "YRE&zbkYJ!V+Mt8y";  // contraseña de la base de datos
$dbname = "pokewebapp";

if (!empty($_POST)) {
    $link = mysqli_connect($host, $user, $password, $dbname);

    if ($link == false) {
        $message = "ERROR: Could not connect " . mysqli_connect_error();
    } else {
        $email = mysqli_real_escape_string($link, $_POST["email"]);
        $pwd = $_POST["pwd"];

        $stmt = $link->prepare("SELECT id, correo, contrasena FROM usuario WHERE correo=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($pwd, $row["contrasena"])) {
                $id_usuario = $row["id"];
                session_start();

                // Guardar el correo y el ID del usuario en las variables de sesión
                $_SESSION["currentEmail"] = $email;
                $_SESSION["currentId"] = $id_usuario;

                header('Location: ../main.php');
                exit();
            } else {
                $message = "Credenciales incorrectas";
            }
        } else {
            $message = "Credenciales incorrectas";
        }
        $stmt->close();
        mysqli_close($link);
    }
}
?>
