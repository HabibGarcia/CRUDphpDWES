<?php
setcookie("usuario", "", time() - 3600);
setcookie("rol", "", time() - 3600);
header("Location: UD3LogIn.php");
?>