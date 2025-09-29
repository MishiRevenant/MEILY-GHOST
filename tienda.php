<?php
// 1. TODA LA LÓGICA PHP VA AL INICIO, ANTES DE CUALQUIER HTML.
// Habilitamos la vista de errores para saber si algo más falla.
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ---- LA CORRECCIÓN ESTÁ AQUÍ ----
// Le decimos a PHP que busque conexion.php en la MISMA CARPETA que tienda.php
include 'conexion.php';

// Obtenemos los estilos para crear los botones de filtro.
$estilos = $conn->query("SELECT * FROM estilos ORDER BY nombre ASC");

// Obtenemos los productos para mostrarlos en la cuadrícula.
$productos = $conn->query("SELECT p.*, e.nombre as estilo_nombre 
                           FROM productos p 
                           LEFT JOIN estilos e ON p.estilo_id = e.id 
                           ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda | Meily Ghost</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<nav id="sidebar" class="sidebar">
    <a href="index.html"><i class="fas fa-home"></i><span>Inicio</span></a>
    <a href="tienda.php" class="active"><i class="fas fa-store"></i><span>Tienda</span></a>
    <a href="inspiracion.html"><i class="fas fa-lightbulb"></i><span>Inspiración</span></a>
    <a href="acerca.html"><i class="fas fa-info-circle"></i><span>Acerca de</span></a>
    <a href="contacto.php"><i class="fas fa-envelope"></i><span>Contacto</span></a>
    <a href="admin.php"><i class="fas fa-user-shield"></i><span>Admin</span></a>

</nav>

<header>
    <img src="https://scontent.fcuz2-1.fna.fbcdn.net/v/t39.30808-6/468282342_586989107106293_9188685479668068517_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeGqaBqQuSIFdrSGvSeCK_SngyhqWNOuwTqDKGpY067BOvLpiHjJtqPjYQUTvRZUJhLMmiFs40xKKr6rZNwmCsUw&_nc_ohc=3bEk8xeZqmYQ7kNvwGefWmA&_nc_oc=AdmmvKQnCo2mKYnal3r9CuTk6eUCw43GxIoC50uF6AZydtUGiJxEAeZAhZbh35Oj1dQ&_nc_zt=23&_nc_ht=scontent.fcuz2-1.fna&_nc_gid=2d1C21sehre24ha-Q3TTLw&oh=00_AfYTjjRgGKhTZebiABbwRUNgh62Jq5s0xd4jeQ6X4XglZA&oe=68DE986D">
    <h1>Tienda Meily Ghost</h1>
    <p class="intro-coraline">Explora la colección completa y filtra por estilo:</p>
</header>


<section class="main-section">
    <h2>Estilos</h2>
    <div class="categories">
        <button class="cat-btn active" onclick="filterCat('todos')">Todos</button>
        <?php if ($estilos && $estilos->num_rows > 0): ?>
            <?php while($estilo = $estilos->fetch_assoc()): ?>
                <button class="cat-btn" onclick="filterCat('<?php echo strtolower(htmlspecialchars($estilo['nombre'])); ?>')">
                    <?php echo htmlspecialchars($estilo['nombre']); ?>
                </button>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
    
    <div id="productos" class="products-grid">
        
        <?php if ($productos && $productos->num_rows > 0): ?>
            <?php while($producto = $productos->fetch_assoc()): ?>
            
            <div class="product-card" data-cat="<?php echo strtolower(htmlspecialchars($producto['estilo_nombre'] ?? 'sin-estilo')); ?>">
                <img src="<?php echo htmlspecialchars($producto['imagen_url']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                <p class="cat-label"><?php echo htmlspecialchars($producto['estilo_nombre']); ?></p>
                <p style="color: #ffe766; font-size: 1.2em;">S/ <?php echo number_format($producto['precio'], 2); ?></p>
            </div>

            <?php endwhile; ?>
        <?php else: ?>
            <p style="grid-column: 1 / -1; text-align: center;">No hay productos disponibles en este momento. ¡Añade algunos desde el panel de admin!</p>
        <?php endif; ?>

    </div>
</section>

<footer class="footer">
    &copy; 2025 Meily Ghost - Creaciones únicas hechas a mano
</footer>

<script src="sidebar.js"></script>
<script src="tienda.js"></script> 
</body>
</html>
<?php
// Cerramos la conexión al final del script.
if (isset($conn)) {
    $conn->close();
}
?>