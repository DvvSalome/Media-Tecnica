
    header("Location: panel.php");
    exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM perros WHERE id = $id");
$perro = $result->fetch_assoc();

if (!$perro) {
    header("Location: panel.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $raza = $_POST['raza'];
    $tamano = $_POST['tamano'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];

    $foto = $perro['foto'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = 'img/' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    }

    $stmt = $conn->prepare("UPDATE perros SET nombre=?, edad=?, raza=?, tamano=?, descripcion=?, estado=?, foto=? WHERE id=?");
    $stmt->bind_param("sisssssi", $nombre, $edad, $raza, $tamano, $descripcion, $estado, $foto, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perro - Panel Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="mb-4">✏️ Editar Perro</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($perro['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Edad (años)</label>
            <input type="number" name="edad" class="form-control" value="<?= $perro['edad'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Raza</label>
            <input type="text" name="raza" class="form-control" value="<?= htmlspecialchars($perro['raza']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Tamaño</label>
            <select name="tamano" class="form-select" required>
                <option value="Pequeño" <?= $perro['tamano']=='Pequeño'?'selected':'' ?>>Pequeño</option>
                <option value="Mediano" <?= $perro['tamano']=='Mediano'?'selected':'' ?>>Mediano</option>
                <option value="Grande" <?= $perro['tamano']=='Grande'?'selected':'' ?>>Grande</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4"><?= htmlspecialchars($perro['descripcion']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-select" required>
                <option value="disponible" <?= $perro['estado']=='disponible'?'selected':'' ?>>Disponible</option>
                <option value="adoptado" <?= $perro['estado']=='adoptado'?'selected':'' ?>>Adoptado</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Foto Actual</label><br>
            <?php if($perro['foto']){ ?>
                <img src="<?= $perro['foto'] ?>" style="width:150px; height:150px; object-fit:cover;"><br><br>
            <?php } ?>
            <label>Subir nueva foto (opcional)</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="panel.php" class="btn btn-secondary">Volver</a>
    </form>
</div>

</body>
</html><?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "huellitasdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {