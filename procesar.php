<?php
session_start();
include 'conexion.php';

$conn = conectarDB($_SESSION['usuario'], $_SESSION['clave']);

if (isset($_POST['eliminar']) && $_SESSION['permiso'] === 'eliminar') {
    $cedula = $_POST['cedula'];

    // Validación básica
    if (!empty($cedula)) {
        $query = 'DELETE FROM datos_personales WHERE "CEDULA" = $1';
        $result = pg_query_params($conn, $query, [$cedula]);
        if ($result) {
            header("Location: index.php?msg=ok");
            exit;
        } else {
            header("Location: index.php?msg=db");
            exit;
        }
    } else {
        header("Location: index.php?msg=campos");
        exit;
    }
}

if (isset($_POST['actualizar']) && $_SESSION['permiso'] === 'actualizar') {
    $cedula = trim($_POST['cedula'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $edad = trim($_POST['edad'] ?? '');
    $peso = isset($_POST['peso']) ? (float) $_POST['peso'] : 0;
    $altura = isset($_POST['altura']) ? (float) $_POST['altura'] : 0;

    // Validar campos
    if (
        !empty($cedula) &&
        !empty($nombre) &&
        !empty($apellido) &&
        ($genero === "masculino" || $genero === "femenino") &&
        is_numeric($edad) &&
        is_numeric($altura) &&
        is_numeric($peso)
    ) {
        // Verifica si existe la cédula
        $check = pg_query_params($conn, 'SELECT 1 FROM datos_personales WHERE "CEDULA" = $1', [$cedula]);
        if (pg_num_rows($check) > 0) {
            // Actualiza los datos
            $query = 'UPDATE datos_personales SET "NOMBRES"=$1, "APELLIDOS"=$2, "GENERO"=$3, "EDAD"=$4, "PESO"=$5, "ALTURA"=$6 WHERE "CEDULA"=$7';
            $result = pg_query_params($conn, $query, [$nombre, $apellido, $genero, $edad, $peso, $altura, $cedula]);
            if ($result) {
                header("Location: index.php?msg=actualizado");
                exit;
            } else {
                header("Location: index.php?msg=db");
                exit;
            }
        } else {
            // No existe la cédula
            header("Location: index.php?msg=nodata");
            exit;
        }
    } else {
        header("Location: index.php?msg=campos");
        exit;
    }
}

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
    $query = 'INSERT INTO datos_personales ("CEDULA", "NOMBRES", "APELLIDOS ", "GENERO", "EDAD", "ALTURA", "PESO")
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
