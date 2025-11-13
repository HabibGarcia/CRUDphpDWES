<?php
session_start(); //SE ABRE UNA SESIÓN
//CONEXIÓN CON LA BBDD
//DATOS DE CONEXIÓN
$cadena_conexion = 'mysql:host=localhost;dbname=BD1;charset=utf8';
$usuarioBD = 'habib';
$password = 'habib071203';
try {
    $bd = new PDO($cadena_conexion, $usuarioBD, $password);	
	echo "Conexión realizada con éxito<br>";	

} catch (PDOException $e) {
	echo 'Error con la base de datos: ' . $e->getMessage();
} 
//HACER CONSULTA CON USUARIO Y CLAVE
//SELECT * FROM USUARIOS WHERE USER=$usuario AND PASSWORD=$clave;

//LOGIN
if($_SERVER["REQUEST_METHOD"] =="POST"){
    $usuario = $_POST['usuario']; //SE DEFINE LA VARIABLE USUARIO
    $clave   = $_POST['clave']; //SE DEFINE LA VARIABLE CLAVE 
//CAMBIAR EL IF 
    if (isset($usuariosRegistrados[$usuario]) && $usuariosRegistrados[$usuario][0] == $clave) {
        $rol = $usuariosRegistrados[$usuario][1];
        if ($rol == 1) {
            header("Location: administracion.php");
        } else {
            header("Location: principal.php");
        }
        exit;
    } else {
        $err = true;
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

