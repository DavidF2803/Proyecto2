<?php
require __DIR__.'/mysqlProfile.php';
// Establecemos la conexión con la base de datos (ajustando esto a una variable para reutilizar la contraseña)
$host = "localhost";
$user = "root";
$password = "YRE&zbkYJ!V+Mt8y";  // contraseña de la base de datos
$dbname = "pokewebapp";

//obtenemos el indice de la imagen clickeada
$id = $_COOKIE["clicked"];
//establecemos la conexión con la base de datos
$link = mysqli_connect($host, $user, $password, $dbname);
//revisamos que se haya realizado la conexión
if($link == false){
	echo "cannot connect";
	// Close connection
	mysqli_close($link);
}else{
	$id_pokemon = null;
	//obtendremos el ultimo id del pokemon agregado en la tabla "Pokemon"
	$sql = "SELECT id FROM pokemon ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($link, $sql);
	$last_id = "";
	if (mysqli_num_rows($result) > 0){
		while ($row = $result->fetch_assoc()){
			foreach($row as $value) {
				$last_id = $value;
			}
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

    //cambiaremos temporalmente el id del primer pokemon
    $last_id++;
    $id_pokemon = ""; // Debes definir la variable $id_pokemon correctamente
    
    $sql="UPDATE pokemon SET id  = '$last_id'  WHERE id = '$id_pokemon'";
    if(mysqli_query($link, $sql)){
    	//una vez que cambiamos el id temporal, 
    	//cambiaremos el id de la imagen seleccionada por el id del pokemon principal
    	$sql="UPDATE pokemon SET id  = '$id_pokemon'  WHERE id = '$id'";
    	if(mysqli_query($link, $sql)){
    		//ahora cambiaremos el id del pokemon principal por el id
    		//de la imagen seleccionada
    		$sql="UPDATE pokemon SET id  = '$id'  WHERE id = '$last_id'";
    		if(mysqli_query($link, $sql)){
    			header('Location: ../html/profile.php');
				exit();
    		}else{
    			echo "cannot update id old to new";
				// Close connection
				mysqli_close($link);
    		}
    	}else{
    		echo  "cannot update id new to old";
			// Close connection
			mysqli_close($link);
    	}
    }else{
    	echo "cannot update temporal id";
		// Close connection
		mysqli_close($link);
    }
}
?>
