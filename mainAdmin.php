<?php
session_start();
require "conexion.php";

if (!isset($_COOKIE['rol']) || $_COOKIE['rol'] != 1) {
    header("Location: UD3LogIn.php");
    exit;
}

function limpiar($v) { return htmlspecialchars(trim($v)); }
$mensaje = "";

//CRUD USUARIOS
if (isset($_POST["crear_usuario"])) {
    $user = limpiar($_POST["user"]);
    $pass = password_hash($_POST["pass"], PASSWORD_DEFAULT);
    $rol = (int)$_POST["rol"];

    $sql = $bd->prepare("INSERT INTO usuarios (user, password, rol) VALUES (?,?,?)");
    $sql->execute([$user, $pass, $rol]);
    $mensaje = "Usuario creado correctamente.";
}

if (isset($_POST["leer_usuario"])) {
    $user = limpiar($_POST["user"]);
    $sql = $bd->prepare("SELECT user, rol FROM usuarios WHERE user=?");
    $sql->execute([$user]);
    $resultado = $sql->fetch(PDO::FETCH_ASSOC);
    if ($resultado) {
        $mensaje .= "<h3>Resultado:</h3>";
        $mensaje .= "Usuario: " . $resultado["user"] . "<br>";
        $mensaje .= "Rol: " . $resultado["rol"] . "<br>";
    } else {
        $mensaje = "Usuario no encontrado.";
    }
}

if (isset($_POST["actualizar_usuario"])) {
    $user = limpiar($_POST["user"]);
    $pass = password_hash($_POST["pass"], PASSWORD_DEFAULT);
    $rol = (int)$_POST["rol"];

    $sql = $bd->prepare("UPDATE usuarios SET password=?, rol=? WHERE user=?");
    $sql->execute([$pass, $rol, $user]);
    $mensaje = "Usuario actualizado.";
}

if (isset($_POST["eliminar_usuario"])) {
    $user = limpiar($_POST["user"]);
    $sql = $bd->prepare("DELETE FROM usuarios WHERE user=?");
    $sql->execute([$user]);
    $mensaje = "Usuario eliminado.";
}

//CRUD REGIONES
if (isset($_POST["crear_region"])) {
    $id_region = (int)$_POST["id_region"];
    $nombre = limpiar($_POST["nombre_region"]);
    $bd->prepare("INSERT INTO regiones (id_region, nombre_region) VALUES (?,?)")->execute([$id_region,$nombre]);
    $mensaje = "Región creada.";
}

if (isset($_POST["leer_region"])) {
    $id = (int)$_POST["id_region"];
    $sql = $bd->prepare("SELECT * FROM regiones WHERE id_region=?");
    $sql->execute([$id]);
    $resultado = $sql->fetch(PDO::FETCH_ASSOC);
    if ($resultado) {
        $mensaje .= "<h3>Resultado:</h3>";
        $mensaje .= "ID Región: " . $resultado["id_region"] . "<br>";
        $mensaje .= "Nombre: " . $resultado["nombre_region"] . "<br>";
    } else {
        $mensaje = "Región no encontrada.";
    }
}

if (isset($_POST["actualizar_region"])) {
    $id = (int)$_POST["id_region"];
    $nombre = limpiar($_POST["nombre_region"]);
    $bd->prepare("UPDATE regiones SET nombre_region=? WHERE id_region=?")->execute([$nombre, $id]);
    $mensaje = "Región actualizada.";
}

if (isset($_POST["eliminar_region"])) {
    $id = (int)$_POST["id_region"];
    $bd->prepare("DELETE FROM regiones WHERE id_region=?")->execute([$id]);
    $mensaje = "Región eliminada.";
}
//CRUD TIPOS
if (isset($_POST["crear_tipo"])) {
    $id = (int)$_POST["id_tipo"];
    $nombre = limpiar($_POST["nombre"]);
    $bd->prepare("INSERT INTO tipos (id_tipo,nombre) VALUES (?,?)")->execute([$id, $nombre]);
    $mensaje = "Tipo creado.";
}

if (isset($_POST["leer_tipo"])) {
    $id = (int)$_POST["id_tipo"];
    $sql = $bd->prepare("SELECT * FROM tipos WHERE id_tipo=?");
    $sql->execute([$id]);
    $resultado = $sql->fetch(PDO::FETCH_ASSOC);
    if ($resultado) {
        $mensaje .= "<h3>Resultado:</h3>";
        $mensaje .= "ID Tipo: " . $resultado["id_tipo"] . "<br>";
        $mensaje .= "Nombre: " . $resultado["nombre"] . "<br>";
    } else {
        $mensaje = "Región no encontrada.";
    }
}

if (isset($_POST["actualizar_tipo"])) {
    $id = (int)$_POST["id_tipo"];
    $nombre = limpiar($_POST["nombre"]);
    $bd->prepare("UPDATE tipos SET nombre=? WHERE id_tipo=?")->execute([$nombre, $id]);
    $mensaje = "Tipo actualizado.";
}

if (isset($_POST["eliminar_tipo"])) {
    $id = (int)$_POST["id_tipo"];
    $bd->prepare("DELETE FROM tipos WHERE id_tipo=?")->execute([$id]);
    $mensaje = "Tipo eliminado.";
}
//CRUD POKEMONES
if (isset($_POST["crear_pokemon"])) {
    $id= (int)$_POST["id_pokemon"];
    $nombre = limpiar($_POST["nombre_pokemon"]);
    $tipo = (int)$_POST["id_tipo"];
    $region = (int)$_POST["id_region"];

    $bd->prepare("INSERT INTO pokemones(id_pokemon,nombre_pokemon,id_tipo,id_region) VALUES (?,?,?,?)")
        ->execute([$id,$nombre, $tipo, $region]);
    $mensaje = "Pokémon creado.";
}

if (isset($_POST["leer_pokemon"])) {
    $busqueda = "%" . $_POST["busqueda"] . "%";
    $sql = $bd->prepare("
        SELECT 
            p.id_pokemon,
            p.nombre_pokemon,
            t.nombre AS tipo,
            r.nombre_region AS region
        FROM pokemones p
        JOIN tipos t ON p.id_tipo = t.id_tipo
        JOIN regiones r ON p.id_region = r.id_region
        WHERE 
            p.id_pokemon LIKE ?
            OR p.nombre_pokemon LIKE ?
            OR t.nombre LIKE ?
            OR r.nombre_region LIKE ?
    ");
    $sql->execute([$busqueda, $busqueda, $busqueda, $busqueda]);
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    if ($resultado) {
    $mensaje .= "<h3>Resultados:</h3>";
    foreach ($resultado as $fila) {
        $mensaje .= "ID: " . $fila["id_pokemon"] . "<br>";
        $mensaje .= "Nombre: " . $fila["nombre_pokemon"] . "<br>";
        $mensaje .= "Tipo: " . $fila["tipo"] . "<br>";
        $mensaje .= "Región: " . $fila["region"] . "<br>";
    }
    } else {
    $mensaje = "Pokémon no encontrado.";
    }
}

if (isset($_POST["actualizar_pokemon"])) {
    $id = (int)$_POST["id_pokemon"];
    $nombre = limpiar($_POST["nombre_pokemon"]);
    $tipo = (int)$_POST["id_tipo"];
    $region = (int)$_POST["id_region"];

    $bd->prepare("UPDATE pokemones SET nombre_pokemon=?, id_tipo=?, id_region=? WHERE id_pokemon=?")
        ->execute([$nombre, $tipo, $region, $id]);
    $mensaje = "Pokémon actualizado.";
}
if (isset($_POST["eliminar_pokemon"])) {
    $id = (int)$_POST["id_pokemon"];
    $bd->prepare("DELETE FROM pokemones WHERE id_pokemon=?")->execute([$id]);
    $mensaje = "Pokémon eliminado.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Administrador</title>
</head>
<body>

<h1>Panel de Administración</h1>
<a href="logout.php">Cerrar sesión</a>

<h2 style="color:blue;"><?php echo $mensaje; ?></h2>

<hr>
<!--USUARIOS-->
<h2>Usuarios</h2>
<h3>Crear</h3>
<form method="post">
    Usuario: <input name="user">
    Contraseña: <input type="password" name="pass">
    Rol (1 admin / 0 viewer): <input name="rol">
    <input type="submit" name="crear_usuario" value="Crear">
</form>
<h3>Leer</h3>
<form method="post">
    Usuario: <input name="user">
    <input type="submit" name="leer_usuario" value="Leer">
</form>
<h3>Actualizar</h3>
<form method="post">
    Usuario: <input name="user">
    Nueva Contraseña: <input type="password" name="pass">
    Nuevo Rol: <input name="rol">
    <input type="submit" name="actualizar_usuario" value="Actualizar">
</form>
<h3>Eliminar</h3>
<form method="post">
    Usuario: <input name="user">
    <input type="submit" name="eliminar_usuario" value="Eliminar">
</form>
<hr>
<!--REGIONES-->
<h2>Regiones</h2>

<h3>Crear</h3>
<form method="post">
    ID región: <input name="id_region">
    Nombre región: <input name="nombre_region">
    <input type="submit" name="crear_region" value="Crear">
</form>

<h3>Leer</h3>
<form method="post">
    ID región: <input name="id_region">
    <input type="submit" name="leer_region" value="Leer">
</form>

<h3>Actualizar</h3>
<form method="post">
    ID región: <input name="id_region">
    Nuevo nombre: <input name="nombre_region">
    <input type="submit" name="actualizar_region" value="Actualizar">
</form>

<h3>Eliminar</h3>
<form method="post">
    ID región: <input name="id_region">
    <input type="submit" name="eliminar_region" value="Eliminar">
</form>

<hr>
<!--TIPOS-->
<h2>Tipos</h2>
<h3>Crear</h3>
<form method="post">
    ID tipo: <input name="id_tipo">
    Nombre tipo: <input name="nombre">
    <input type="submit" name="crear_tipo" value="Crear">
</form>
<h3>Leer</h3>
<form method="post">
    ID tipo: <input name="id_tipo">
    <input type="submit" name="leer_tipo" value="Leer">
</form>
<h3>Actualizar</h3>
<form method="post">
    ID tipo: <input name="id_tipo">
    Nuevo nombre: <input name="nombre">
    <input type="submit" name="actualizar_tipo" value="Actualizar">
</form>
<h3>Eliminar</h3>
<form method="post">
    ID tipo: <input name="id_tipo">
    <input type="submit" name="eliminar_tipo" value="Eliminar">
</form>
<hr>
<!--POKEMONES-->
<h2>Pokemones</h2>
<h3>Crear</h3>
<form method="post">
    ID Pokémon: <input name="id_pokemon">
    Nombre: <input name="nombre_pokemon">
    ID Tipo: <input name="id_tipo">
    ID Región: <input name="id_region">
    <input type="submit" name="crear_pokemon" value="Crear">
</form>
<h3>Leer</h3>
<form method="post">
    ID,nombre: <input name="busqueda">
    <input type="submit" name="leer_pokemon" value="Leer">
</form>
<h3>Actualizar</h3>
<form method="post">
    ID Pokémon: <input name="id_pokemon">
    Nuevo nombre: <input name="nombre_pokemon">
    Nuevo ID Tipo: <input name="id_tipo">
    Nuevo ID Región: <input name="id_region">
    <input type="submit" name="actualizar_pokemon" value="Actualizar">
</form>
<h3>Eliminar</h3>
<form method="post">
    ID Pokémon: <input name="id_pokemon">
    <input type="submit" name="eliminar_pokemon" value="Eliminar">
</form>
</body>
</html>