<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cedula = trim($_POST['cedula'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $edad = trim($_POST['edad'] ?? '');
    $peso = isset($_POST['peso']) ? (float) $_POST['peso'] : 0;
    $altura = isset($_POST['altura']) ? (float) $_POST['altura'] : 0;

    if (
    !empty($cedula) &&
    !empty($nombre) &&
    !empty($apellido) &&
    ($genero === "masculino" || $genero === "femenino") &&
    is_numeric($edad) &&
    is_numeric($altura) &&
    is_numeric($peso)
) {
    $conn = conectarDB();
    $query = 'INSERT INTO datos_personales ("CEDULA", "NOMBRES", "APELLIDOS", "GENERO", "EDAD", "ALTURA", "PESO")
              VALUES ($1, $2, $3, $4, $5, $6, $7)';
    $result = pg_query_params($conn, $query, [
        $cedula,
        $nombre,
        $apellido,
        $genero,
        $edad,
        (float) $altura,
        (float) $peso
    ]);

        if ($result) {
            pg_close($conn); // 🔹 Cerramos la conexión aquí
            header("Location: index.php?msg=ok");
            exit;
        } else {
            echo "<pre>Error en la consulta: " . pg_last_error($conn) . "</pre>";
            pg_close($conn); // 🔹 Cerramos la conexión también en caso de error
            exit;
        }

    } else {
        pg_close($conn); // Si abriste la conexión antes, ciérrala aquí también
        header("Location: index.php?msg=campos");
        exit;

    }
}
?>
