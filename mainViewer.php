<?php
//
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_COOKIE["usuario"])) {
    header("Location: UD3LogIn.php");
    exit;
}
//VISITIAS CON COOKIES
if (!isset($_COOKIE['visitas'])) {
    setcookie('visitas', '1', time() + 3600 * 24);
    $mensajeVisitas = "Bienvenido por primera vez";
} else {
    $visitas = (int) $_COOKIE['visitas'];
    $visitas++;
    setcookie('visitas', $visitas, time() + 3600 * 24);
    $mensajeVisitas = "Bienvenido por $visitas vez";
}

require "conexion.php";
$resultado = null;

if ($_POST) {
    $busqueda = "%" . $_POST["busqueda"] . "%";
    // JOIN PARA OBTENER LOS DATOS DE TODAS LAS TABLAS
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

<h2>Bienvenido <?php echo htmlspecialchars($_COOKIE["usuario"]); ?></strong></p></h2>   
<p><?php echo $mensajeVisitas; ?></p>

<hr>
<h3>BUSCA UN POKEMON</h3>
<span>Introduce el ID, nombre, tipo o región</span>
<!-- FORMULARIO DE BÚSQUEDA -->
<form method="POST"> 
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
} else {
    echo "Pokémon no encontrado.";
}
?>
<br>
<a href="logout.php">Cerrar sesión</a>