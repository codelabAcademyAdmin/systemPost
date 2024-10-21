<?php

session_start(); // Reanudar la sesión existente
session_unset(); // Eliminar todas las variables de sesión
session_destroy(); // Destruir la sesión

header('Location: ../../index.php');
exit;
?>