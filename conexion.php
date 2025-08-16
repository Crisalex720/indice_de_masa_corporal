<?php
function conectarDB() {
    $host = "localhost";
    $port = "5432";
    $dbname = "masa_corporal";
    $user = "postgres";
    $password = "3142"; // Si tienes contraseña, colócala aquí

    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

    if (!$conn) {
        die("Error de conexión: " . pg_last_error());
    }
    return $conn;
}
?>
