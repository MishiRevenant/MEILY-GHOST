<?php
// --- ¡IMPORTANTE! ACTIVAR REPORTE DE ERRORES ---
// Estas dos líneas harán que cualquier error en el código o en la base de datos se muestre en la pantalla.
// Esto es fundamental para saber qué está pasando.
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluimos la conexión para que las tablas y la BD se aseguren de existir
include 'conexion.php';

$mensaje = ""; // Variable para mostrar mensajes de éxito o error

// Lógica para AÑADIR o EDITAR un producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar_producto'])) {
    
    // Validamos que los campos no estén vacíos
    if (empty($_POST['nombre']) || empty($_POST['imagen_url']) || empty($_POST['precio']) || empty($_POST['categoria_id']) || empty($_POST['estilo_id'])) {
        $mensaje = "Error: Todos los campos marcados son obligatorios.";
    } else {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $imagen_url = $_POST['imagen_url'];
        $categoria_id = $_POST['categoria_id'];
        $estilo_id = $_POST['estilo_id'];
        $precio = $_POST['precio'];
        $producto_id = $_POST['producto_id']; // Para saber si estamos editando

        if (empty($producto_id)) {
            // Es un producto nuevo -> INSERT
            $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, imagen_url, categoria_id, estilo_id, precio) VALUES (?, ?, ?, ?, ?, ?)");
            // CORRECCIÓN: El tipo de dato para IDs debe ser 'i' (integer) y para precio 'd' (double)
            $stmt->bind_param("sssiid", $nombre, $descripcion, $imagen_url, $categoria_id, $estilo_id, $precio);
        } else {
            // Es un producto existente -> UPDATE
            $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, imagen_url=?, categoria_id=?, estilo_id=?, precio=? WHERE id=?");
            // CORRECCIÓN: Añadimos el id al final con tipo 'i'
            $stmt->bind_param("sssiidi", $nombre, $descripcion, $imagen_url, $categoria_id, $estilo_id, $precio, $producto_id);
        }
        
        // Ejecutamos y verificamos si fue exitoso
        if ($stmt->execute()) {
            // En lugar de redirigir inmediatamente, mostramos un mensaje de éxito.
            // La redirección a veces oculta mensajes de error.
            $mensaje = "¡Producto guardado correctamente!";
        } else {
            // Si hay un error, lo mostramos
            $mensaje = "Error al guardar el producto: " . $stmt->error;
        }
        $stmt->close();
    }
}

if (isset($_GET['borrar'])) {
    $id_a_borrar = $_GET['borrar'];
    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id_a_borrar);
    if ($stmt->execute()) {
        header("Location: admin.php"); 
        exit();
    } else {
        $mensaje = "Error al borrar el producto.";
    }
}

$producto_a_editar = null;
if (isset($_GET['editar'])) {
    $id_a_editar = $_GET['editar'];
    $resultado = $conn->query("SELECT * FROM productos WHERE id = $id_a_editar");
    $producto_a_editar = $resultado->fetch_assoc();
}

$categorias = $conn->query("SELECT * FROM categorias ORDER BY nombre");
$estilos = $conn->query("SELECT * FROM estilos ORDER BY nombre");
$productos = $conn->query("SELECT p.*, c.nombre as categoria_nombre, e.nombre as estilo_nombre FROM productos p LEFT JOIN categorias c ON p.categoria_id = c.id LEFT JOIN estilos e ON p.estilo_id = e.id ORDER BY p.id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin | Meily Ghost</title>
    <link rel = "stylesheet" type = "text/css" href = "style.css">
    <link rel="icon" href="https://scontent.fcuz2-1.fna.fbcdn.net/v/t39.30808-6/476437022_122116658288648912_4755061113525152841_n.jpg?_nc_cat=111&ccb=1-7&_nc_sid=cc71e4&_nc_eui2=AeHOVONoddacloSa-9euEaNd6M0UMd6JblLozRQx3oluUgIBmybeAJPz3kU1-LY_gvbdVqGO-wNWDqIssLlwIIzX&_nc_ohc=T514HRelql0Q7kNvwGvM2FU&_nc_oc=AdlT8wRwiJuMjNNW807PuWouP_3WZrI3LSpRs_rNzWVP0w8_GI9CWZN4B_C2fWgbqZo&_nc_zt=23&_nc_ht=scontent.fcuz2-1.fna&_nc_gid=aZ2cD_78IJEfvvTr3YeoEQ&oh=00_AfYZFoPprW7hMg5uHqnwnKPYWVIMiGHmWpHgrX4jIPRgaw&oe=68DECCF3" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../style.css"> <style>
    

        body { margin: 20px; background: #131224; color: #e3e3e3; font-family: 'Poppins', sans-serif;}
        .admin-container { max-width: 1200px; margin: auto; background-color: #23253b; padding: 25px; border-radius: 15px; box-shadow: 0 0 20px rgba(0,0,0,0.5); }
        h1, h2 { color: #ffe766; font-family: 'IM Fell English SC', cursive; }
        .mensaje { background-color: #ffe766; color: #131224; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #4d3456; }
        th { background-color: #6045d3; color: #fff; text-align: left;}
        tr:nth-child(even) { background-color: #2a284d; }
        .admin-form input, .admin-form select, .admin-form textarea, .admin-form button { width: 100%; padding: 12px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #6045d3; background-color: #191927; color: #fff; font-family: 'Poppins', sans-serif; font-size: 1em; box-sizing: border-box;}
        .admin-form button { background-color: #6045d3; color: #ffe766; cursor: pointer; font-weight: bold; }
        .admin-form button:hover { background-color: #785ae0; }
        .btn { padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 0.9em; }
        .btn-editar { background-color: #ffe766; color: #131224; margin-right: 5px; }
        .btn-borrar { background-color: #8a0303; color: #fff; }
    </style>
</head>
<body>
<nav id="sidebar" class="sidebar">
    <a href="index.html" class="active"><i class="fas fa-home"></i><span>Inicio</span></a>
    <a href="tienda.php"><i class="fas fa-store"></i><span>Tienda</span></a>
    <a href="inspiracion.html"><i class="fas fa-lightbulb"></i><span>Inspiración</span></a>
    <a href="acerca.html"><i class="fas fa-info-circle"></i><span>Acerca de</span></a>
    <a href="contacto.php"><i class="fas fa-envelope"></i><span>Contacto</span></a>
    <a href="admin.php"><i class="fas fa-user-shield"></i><span>Admin</span></a>
</nav>
<div class="admin-container">
    <h1><i class="fas fa-ghost"></i> Panel de Administración</h1>

    <?php if (!empty($mensaje)): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <h2><?php echo $producto_a_editar ? 'Editar' : 'Añadir Nuevo'; ?> Producto</h2>
    <form class="admin-form" method="POST" action="admin.php">
        <input type="hidden" name="producto_id" value="<?php echo $producto_a_editar['id'] ?? ''; ?>">
        <input type="text" name="nombre" placeholder="Nombre del Producto" value="<?php echo htmlspecialchars($producto_a_editar['nombre'] ?? ''); ?>" required>
        <textarea name="descripcion" placeholder="Descripción corta"><?php echo htmlspecialchars($producto_a_editar['descripcion'] ?? ''); ?></textarea>
        <input type="text" name="imagen_url" placeholder="URL de la Imagen" value="<?php echo htmlspecialchars($producto_a_editar['imagen_url'] ?? ''); ?>" required>
        <input type="number" step="0.01" name="precio" placeholder="Precio (ej: 25.50)" value="<?php echo htmlspecialchars($producto_a_editar['precio'] ?? ''); ?>" required>
        <select name="categoria_id" required>
            <option value="">-- Selecciona una Categoría --</option>
            <?php mysqli_data_seek($categorias, 0); // Reiniciar puntero
            while($cat = $categorias->fetch_assoc()): ?>
                <option value="<?php echo $cat['id']; ?>" <?php if(isset($producto_a_editar) && $producto_a_editar['categoria_id'] == $cat['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($cat['nombre']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <select name="estilo_id" required>
            <option value="">-- Selecciona un Estilo --</option>
            <?php mysqli_data_seek($estilos, 0); // Reiniciar puntero
            while($est = $estilos->fetch_assoc()): ?>
                <option value="<?php echo $est['id']; ?>" <?php if(isset($producto_a_editar) && $producto_a_editar['estilo_id'] == $est['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($est['nombre']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="guardar_producto">Guardar Producto</button>
    </form>

    <h2>Inventario de Productos</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Estilo</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($productos->num_rows > 0): ?>
                <?php while($prod = $productos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $prod['id']; ?></td>
                    <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($prod['categoria_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($prod['estilo_nombre']); ?></td>
                    <td>S/ <?php echo number_format($prod['precio'], 2); ?></td>
                    <td>
                        <a href="admin.php?editar=<?php echo $prod['id']; ?>" class="btn btn-editar">Editar</a>
                        <a href="admin.php?borrar=<?php echo $prod['id']; ?>" class="btn btn-borrar" onclick="return confirm('¿Estás seguro?');">Borrar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Aún no hay productos. ¡Añade el primero!</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$conn->close();
?>
<script src="sidebar.js"></script>
<script src="slider.js"></script>
</body>
</html>