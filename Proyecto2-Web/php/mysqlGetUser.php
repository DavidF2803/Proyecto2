<?php 
$email = $_SESSION["currentEmail"];
if (isset($_POST['email'])) {
	$user_mail = $_POST['email'];
}

// Establecemos la conexión con la base de datos (ajustando esto a una variable para reutilizar la contraseña)
$host = "localhost";
$user = "root";
$password = "YRE&zbkYJ!V+Mt8y";  // contraseña de la base de datos
$dbname = "pokewebapp";


//establecemos la conexión con la base de datos
$link = mysqli_connect($host, $user, $password, $dbname);
//revisamos que se haya realizado la conexión
if($link == false){
	echo "cannot connect";

// Usar la contraseña de SQL para la creación de una clave segura
use Defuse\Crypto\KeyOrPassword;

function createKey() {
    global $password;  // Accedemos a la contraseña global de SQL
    return KeyOrPassword::createFromPassword($password);
}

// Ahora puedes usar `createKey()` donde necesites la clave generada
$key = createKey();

// Close connection

}else{
	$sql = "SELECT nombre FROM usuario WHERE correo='$email'";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0){
		 while ($row = $result->fetch_assoc())
    {
        foreach($row as $value) {
             $name = $value;
        }
    }
		//Lo primero que necesitamos, será obtener el nombre del usuario
	//que se ha encontrado
	$sql = "SELECT nombre FROM usuario WHERE correo ='$user_mail'";
	$result = mysqli_query($link, $sql);
	$name="";
	if (mysqli_num_rows($result) > 0){
            while ($row = $result->fetch_assoc())
    {
       foreach($row as $value) {
            $nombre = $value;
       }
    }
    //Ahora, procederemos a obtener los specs de los pokemons encontrados.
    $sql = "SELECT p.id, p.img_id,p.especie,p.nombre,p.peso,p.altura,p.baxp FROM pokemon p INNER JOIN pokedek_pokemon pp ON p.id = pp.id_pokemon INNER JOIN Pokedek pk  ON pp.id_pokedek = pk.id INNER JOIN Usuario u ON pk.id_usuario = u.id WHERE u.correo ='$user_mail' ORDER BY p.id DESC";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0){
    	 while ($row = $result->fetch_assoc())
    		{
      			$poks[] = $row;
    		}
    }else{
    	$poks = Array();
    	$message = "Could not find pokemons";
    	// Close connection
	 	
    }
    	}	else{
    	$message = "Couldnot find username";
    	// Close connection
	 	
    		}
    	}else{
    		$message = "Couldnot find current user";
    	// Close connection
	 	
    	}
	
}
mysqli_close($link);
?>