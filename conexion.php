<?php
$cadena_conexion = 'mysql:host=localhost;dbname=BD1;charset=utf8';
$usuarioBD = 'habib';
$password = 'habib071203';
//VARIABLES DE PASSWORD ENCRIPTADAS
//$contrsena1= password_hash("usuario1", PASSWORD_DEFAULT);
//$contrsena2= password_hash("usuario2", PASSWORD_DEFAULT);
//$contrsena3= password_hash("usuario3", PASSWORD_DEFAULT);
try {
    $bd = new PDO($cadena_conexion, $usuarioBD, $password);	
    //INSERTAMOS USUARIOS ADMIN Y NO ADMIN
    //$ins1 = "INSERT INTO BD1.usuarios(user, password,rol) values ('user1', '$contrsena1', 1);";
    //$ins2 = "INSERT INTO usuarios (user, password,rol) values ('user2', '$contrsena2', 0);";	
    //$ins3 = "INSERT INTO usuarios (user, password,rol) values ('user3', '$contrsena3', 0);";		
    //$insersiones = $bd->query($ins3);
} 
catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
    exit;
}
?>