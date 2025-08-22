<?php
session_start();
include 'conexion.php';

$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

// Intenta conectar con los datos ingresados
$conn = conectarDB($usuario, $clave);

if ($conn) {
    $_SESSION['usuario'] = $usuario;
    $_SESSION['clave'] = $clave;
    header("Location: index.php");
    exit;
} else {
    header("Location: login.php?error=1");
    exit;
}
?>