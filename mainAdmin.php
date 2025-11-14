<?php
session_start();
require "conexion.php";

// COMPROBAR LOGIN
if (!isset($_COOKIE["usuario"]) || $_COOKIE["rol"] != 1) {
    header("Location: UD3LogIn.php");
    exit;
}

// --- FUNCIONES CRUD ---
// Crear Pokémon
if (isset($_POST["accion"]) && $_POST["accion"] === "crear") {
    $sql = $bd->prepare("INSERT INTO pokemones (nombre, id_tipo, id_region) VALUES (?, ?, ?)");
    $sql->execute([
        $_POST["nombre"],
        $_POST["tipo"],
        $_POST["region"]
    ]);
    $msg = "Pokémon creado correctamente.";
}

// Buscar Pokémon por ID
$pokemonBuscado = null;
if (isset($_POST["accion"]) && $_POST["accion"] === "buscar") {
    $sql = $bd->prepare("SELECT * FROM pokemones WHERE id_pokemon = ?");
    $sql->execute([$_POST["id_buscar"]]);
    $pokemonBuscado = $sql->fetch(PDO::FETCH_ASSOC);
}

// Actualizar Pokémon
if (isset($_POST["accion"]) && $_POST["accion"] === "actualizar") {
    $sql = $bd->prepare("UPDATE pokemones 
                          SET nombre = ?, id_tipo = ?, id_region = ?
                          WHERE id_pokemon = ?");
    $sql->execute([
        $_POST["nombre_edit"],
        $_POST["tipo_edit"],
        $_POST["region_edit"],
        $_POST["id_edit"]
    ]);
    $msg = "Pokémon actualizado correctamente.";
}

// Eliminar Pokémon
if (isset($_POST["accion"]) && $_POST["accion"] === "eliminar") {
    $sql = $bd->prepare("DELETE FROM pokemones WHERE id_pokemon = ?");
    $sql->execute([$_POST["id_eliminar"]]);
    $msg = "Pokémon eliminado.";
}

// Obtener listas de tipos y regiones
$tipos = $bd->query("SELECT * FROM tipos")->fetchAll(PDO::FETCH_ASSOC);
$regiones = $bd->query("SELECT * FROM regiones")->fetchAll(PDO::FETCH_ASSOC);

// Obtener tabla completa de pokemones
$lista = $bd->query("SELECT * FROM pokemones")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
</head>
<body>

<h1>Panel de Administración</h1>
<p>Bienvenido, <?php echo htmlspecialchars($_COOKIE["usuario"]); ?></p>

<?php if (isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>

<hr>
<h2>Crear Pokémon</h2>
<form method="POST">
    <input type="hidden" name="accion" value="crear">

    Nombre: <input type="text" name="nombre" required><br>

    Tipo:
    <select name="tipo">
        <?php foreach ($tipos as $t): ?>
            <option value="<?= $t["id_tipo"] ?>"><?= $t["nombre"] ?></option>
        <?php endforeach; ?>
    </select><br>

    Región:
    <select name="region">
        <?php foreach ($regiones as $r): ?>
            <option value="<?= $r["id_region"] ?>"><?= $r["nombre_region"] ?></option>
        <?php endforeach; ?>
    </select><br>

    <button type="submit">Crear</button>
</form>

<hr>
<h2>Buscar Pokémon por ID</h2>
<form method="POST">
    <input type="hidden" name="accion" value="buscar">
    ID Pokémon: <input type="number" name="id_buscar">
    <button type="submit">Buscar</button>
</form>

<?php if ($pokemonBuscado): ?>
    <p><strong>Resultado:</strong></p>
    <p>ID: <?= $pokemonBuscado["id_pokemon"] ?></p>
    <p>Nombre: <?= $pokemonBuscado["nombre"] ?></p>
    <p>ID Tipo: <?= $pokemonBuscado["id_tipo"] ?></p>
    <p>ID Región: <?= $pokemonBuscado["id_region"] ?></p>
<?php endif; ?>

<hr>
<h2>Actualizar Pokémon</h2>
<form method="POST">
    <input type="hidden" name="accion" value="actualizar">

    ID a editar: <input type="number" name="id_edit" required><br>
    Nuevo nombre: <input type="text" name="nombre_edit"><br>

    Nuevo tipo:
    <select name="tipo_edit">
        <?php foreach ($tipos as $t): ?>
            <option value="<?= $t["id_tipo"] ?>"><?= $t["nombre"] ?></option>
        <?php endforeach; ?>
    </select><br>

    Nueva región:
    <select name="region_edit">
        <?php foreach ($regiones as $r): ?>
            <option value="<?= $r["id_region"] ?>"><?= $r["nombre_region"] ?></option>
        <?php endforeach; ?>
    </select><br>

    <button type="submit">Actualizar</button>
</form>

<hr>
<h2>Eliminar Pokémon</h2>
<form method="POST">
    <input type="hidden" name="accion" value="eliminar">
    ID Pokémon: <input type="number" name="id_eliminar">
    <button type="submit">Eliminar</button>
</form>

<hr>
<h2>Listado Completo de Pokemones</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>ID Tipo</th>
        <th>ID Región</th>
    </tr>

    <?php foreach ($lista as $p): ?>
        <tr>
            <td><?= $p["id_pokemon"] ?></td>
            <td><?= $p["nombre"] ?></td>
            <td><?= $p["id_tipo"] ?></td>
            <td><?= $p["id_region"] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="logout.php">Cerrar sesión</a>

</body>
</html>
