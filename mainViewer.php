<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_COOKIE["usuario"])) {
    header("Location: UD3LogIn.php");
    exit;
}

require "conexion.php";
$resultado = null;

if ($_POST) {

    $busqueda = "%" . $_POST["busqueda"] . "%";

    // JOIN para unir las tres tablas
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
}
?>

<h2>Bienvenido <?php echo $_COOKIE["usuario"]; ?></h2>

<form method="POST">
    Buscar Pokémon (ID, nombre, tipo o región):
    <input type="text" name="busqueda">
    <button type="submit">Buscar</button>
</form>

<?php
if ($resultado) {
    echo "<h3>Resultados:</h3>";
    foreach ($resultado as $fila) {
        echo "ID: " . $fila["id_pokemon"] . "<br>";
        echo "Nombre: " . $fila["nombre_pokemon"] . "<br>";
        echo "Tipo: " . $fila["tipo"] . "<br>";
        echo "Región: " . $fila["region"] . "<br>";
        echo "<hr>";
    }
}
?>

<br>
<a href="logout.php">Cerrar sesión</a>