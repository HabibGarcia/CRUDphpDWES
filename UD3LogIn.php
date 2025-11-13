<?php
session_start(); //SE ABRE UNA SESIÓN
//CONEXIÓN CON LA BBDD
//DATOS DE CONEXIÓN
$cadena_conexion = 'mysql:host=localhost;dbname=BD1;charset=utf8';
$usuarioBD = 'habib';
$password = 'habib071203';
//VARIABLES DE PASSWORD ENCRIPTADAS
$contrsena1= password_hash("usuario1", PASSWORD_DEFAULT);
$contrsena2= password_hash("usuario2", PASSWORD_DEFAULT);
$contrsena3= password_hash("usuario3", PASSWORD_DEFAULT);

try {
    $bd = new PDO($cadena_conexion, $usuarioBD, $password);	
	echo "Conexión realizada con éxito<br>";
    //INSERTAMOS USUARIOS ADMIN Y NO ADMIN
    //$ins1 = "INSERT INTO BD1.usuarios(user, password,rol) values ('user1', '$contrsena1', 1);";
    //$ins2 = "INSERT INTO usuarios (user, password,rol) values ('user2', '$contrsena2', 0);";	
    //$ins3 = "INSERT INTO usuarios (user, password,rol) values ('user3', '$contrsena3', 0);";		
    //$insersiones = $bd->query($ins3);
} catch (PDOException $e) {
	echo 'Error con la base de datos: ' . $e->getMessage();
}

//LOGIN
if($_SERVER["REQUEST_METHOD"] =="POST"){
    $usuario = $_POST['usuario']; //SE DEFINE LA VARIABLE USUARIO
    $clave   = $_POST['clave']; //SE DEFINE LA VARIABLE CLAVE 
    if ($usuario === '' || $clave === '') {
        $err = 'Rellena usuario y contraseña.';
    } else {
        // Consulta preparada
        $stmt = $bd->prepare('SELECT user, password, rol FROM usuarios WHERE user = :id');
        //$stmt->execute([':id' => $usuario]); 
        $stmt->execute(array(':id' => $usuario)); 
        $fila = $stmt->fetch();

        if ($fila && password_verify($clave, $fila['password'])) {
            // Autenticado
            session_regenerate_id(true);
            $_SESSION['user'] = $fila['user'];
            $_SESSION['rol']  = (int)$fila['rol'];

            if ($_SESSION['rol'] === 1) {
                header('Location: mainAdmin.php');
            } else {
                header('Location: mainViewer.php');
            }
            exit;
        } else {
            $err = 'Usuario o contraseña incorrectos.';
        }
    }
}?>
<!--FORMULARIO LOG IN, SOLO PIDE USER Y PASSWORD-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
</head>
<body>
    <?php if (isset($err)) echo "<p style='color:red;'>Revise usuario y contraseña</p>"; ?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input value="<?php if (isset($usuario)) echo htmlspecialchars($usuario); ?>" type="text" name="usuario" id="usuario" placeholder="Usuario">
        <input type="password" name="clave" id="clave" placeholder="Contraseña">
        <input type="submit" value="Entrar">
    </form>
</body>
</html>
