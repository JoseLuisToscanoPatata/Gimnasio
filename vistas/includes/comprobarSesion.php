 <?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ((!isset($_SESSION['logueado'])) && (!isset($_COOKIE['mantener']))) {
    header("Location: index.php?controller=index&accion=index&fuera=true");
}
?>