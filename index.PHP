<?php
include 'conexion.php';
$conn = conectarDB();
$resultado = pg_query($conn, 'SELECT "NOMBRES", "APELLIDOS", "ESTADO" FROM datos_personales');

if (!$resultado) {
    die("Error en la consulta: " . pg_last_error($conn));
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title> Insertar y Mostrar Datos </title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] === 'campos'): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <strong>Error:</strong> Debes completar todos los campos obligatorios del formulario.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($_GET['msg'] === 'db'): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <strong>Error:</strong> Hubo un problema al guardar los datos. Intenta nuevamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($_GET['msg'] === 'ok'): ?>
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <strong>Éxito:</strong> Los datos se guardaron correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <h1 class="mb-4">Formulario de Inserción</h1>
        <div class="form-section">
            <form class="row g-2 align-items-end" action="procesar.php" method="POST">
                <div class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="30">
                    </div>
                    <div class="col-md-6">
                        <label for="apellido" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required maxlength="30">
                    </div>
                </div>
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label for="cedula" class="form-label">Cédula</label>
                        <input type="text" class="form-control" id="cedula" name="cedula" required maxlength="20">
                    </div>
                    <div class="col-md-3">
                    <label for="genero" class="form-label">Género</label>
                    <select class="form-select" id="genero" name="genero" required>
                        <option value="0" selected disabled>Selecciona</option>
                        <option value="masculino">Masculino</option>
                        <option value="femenino">Femenino</option>
                    </select>
                </div>
                </div>
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label for="edad" class="form-label">Edad</label>
                        <input type="text" class="form-control" id="edad" name="edad" required maxlength="3" placeholder="0">
                    </div>
                    <div class="col-md-2">
                        <label for="peso" class="form-label">Peso</label>
                        <input type="number" class="form-control" id="peso" name="peso" min="0" max="200" placeholder="0Kg">
                    </div>
                    <div class="col-md-2">
                        <label for="altura" class="form-label">Altura</label>
                        <input type="number" class="form-control" id="altura" name="altura" step="0.01" min="0" max="3" placeholder="0.00m">
                    </div>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
            </form>
        </div>
        <h2 class="mb-3">Registros</h2>
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>NOMBRE</th>
                    <th>APELLIDO</th>
                    <th>ESTADO</th>
                </tr>
            </thead>
            <tbody>
    <?php while ($fila = pg_fetch_assoc($resultado)): ?>
        <?php
            // Asigna una clase según el estado
            switch (strtolower($fila["ESTADO"])) {
                case 'bajo peso':
                    $clase = 'table-danger';
                    break;
                case 'normal':
                    $clase = 'table-success';
                    break;
                case 'sobrepeso':
                case 'obesidad leve':
                case 'obesidad media':
                    $clase = 'table-warning';
                    break;
                case 'obesidad morbida':
                    $clase = 'table-danger';
                    break;
                default:
                    $clase = '';
            }
        ?>
        <tr class="<?= $clase ?>">
            <td class="fw-bold"><?= htmlspecialchars($fila["NOMBRES"]) ?></td>
            <td class="fw-bold"><?= htmlspecialchars($fila["APELLIDOS"]) ?></td>
            <td class="fw-bold"><?= htmlspecialchars($fila["ESTADO"]) ?></td>
        </tr>
    <?php endwhile; ?>
</tbody>
        </table>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

