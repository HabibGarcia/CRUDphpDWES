<?php
session_start();
session_unset();
session_destroy();
setcookie("usuario", "", time() - 3600);
setcookie("rol", "", time() - 3600);
setcookie("visitas", "", time() - 3600);
header("Location: UD3LogIn.php");
?>