<?php

// Establecemos la conexión con la base de datos (ajustando esto a una variable para reutilizar la contraseña)
$host = "localhost";
$user = "root";
$password = "YRE&zbkYJ!V+Mt8y";  // contraseña de la base de datos
$dbname = "pokewebapp";

//conexion a la base de datos
$link = mysqli_connect($host, $user, $password, $dbname);

if($link == false){
    die("ERROR: Could not connect ".mysqli_connect_error());
}

// Usar la contraseña de SQL para la creación de una clave segura
use Defuse\Crypto\KeyOrPassword;

function createKey() {
    global $password;  // Accedemos a la contraseña global de SQL
    return KeyOrPassword::createFromPassword($password);
}

// Ahora puedes usar `createKey()` donde necesites la clave generada
$key = createKey();

$pokemonId = $_POST['pokemonId'];

// Query para obtener los datos del Pokemon
$sql = "SELECT p.id, p.img_id,p.especie,p.nombre,p.peso,p.altura,p.baxp FROM pokemon p WHERE p.id ='$pokemonId'";
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0){
    while ($row = $result->fetch_assoc())
    {
        echo json_encode($row);
    }
}else{
	echo json_encode(array('error' => 'No se encontró el Pokemon'));
}
mysqli_close($link);
?>
