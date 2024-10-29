<?php
session_start();

// Comprobar si hay una sesión activa
if (isset($_SESSION['user'])) {
    // Destruir la sesión
    session_destroy();
    header("Location: ../../login.php");
}

?>
