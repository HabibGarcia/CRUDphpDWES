<?php
session_start();
require "conexion.php";

// Para ver errores reales mientras desarrollas:
error_reporting(E_ALL);
ini_set('display_errors', 1);

$err = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST["id"] ?? "";
    $pass = $_POST["pass"] ?? "";

    // Buscar usuario
    $sql = $bd->prepare("SELECT * FROM usuarios WHERE user = ?");
    $sql->execute([$id]);

    $user = $sql->fetch(PDO::FETCH_ASSOC);

    // Verificar usuario y contraseña hasheada
    if ($user && password_verify($pass, $user["password"])) {

        setcookie("usuario", $user["user"], time() + 3600);
        setcookie("rol", $user["rol"], time() + 3600);

        // Redirigir según rol
        if ($user["rol"] == 1) {   // OJO: con ==
            header("Location: mainAdmin.php");
        } else {
            header("Location: mainViewer.php");
        }
        exit;

    } else {
        $err = true;   // Marca el error
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
</head>
<body>

    <?php if ($err): ?>
        <p style='color:red;'>Revise usuario y contraseña</p>
    <?php endif; ?>

    <form method="POST">
        <input 
            type="text" 
            name="id" 
            placeholder="Usuario"
            value="<?php echo htmlspecialchars($id ?? ""); ?>"
        >
        <br>
        <input type="password" name="pass" placeholder="Contraseña">
        <br>
        <input type="submit" value="Entrar">
    </form>

</body>
</html>
