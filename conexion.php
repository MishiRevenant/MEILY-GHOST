<?php
$servidor = "localhost"; // Generalmente es localhost
$usuario = "root"; // Usuario de tu base de datos
$contrasena = ""; // Contraseña de tu base de datos
$basedatos = "meily_ghost_db";

// Crear conexión
$conn = new mysqli($servidor, $usuario, $contrasena);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Intentar crear la base de datos si no existe
$sql_crear_db = "CREATE DATABASE IF NOT EXISTS $basedatos";
if (!$conn->query($sql_crear_db)) {
    die("Error al crear la base de datos: " . $conn->error);
}

// Seleccionar la base de datos
$conn->select_db($basedatos);

// --- SQL para crear las tablas si no existen ---

// Tabla de Categorías
$sql_tabla_categorias = "
CREATE TABLE IF NOT EXISTS categorias (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);";

// Tabla de Estilos
$sql_tabla_estilos = "
CREATE TABLE IF NOT EXISTS estilos (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);";

// Tabla de Productos
$sql_tabla_productos = "
CREATE TABLE IF NOT EXISTS productos (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    imagen_url VARCHAR(255) NOT NULL,
    categoria_id INT(11),
    estilo_id INT(11),
    precio DECIMAL(10, 2),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    FOREIGN KEY (estilo_id) REFERENCES estilos(id)
);";

// Ejecutar la creación de tablas
if (!$conn->query($sql_tabla_categorias) || !$conn->query($sql_tabla_estilos) || !$conn->query($sql_tabla_productos)) {
    die("Error al crear las tablas: " . $conn->error);
}

// Opcional: Insertar algunas categorías y estilos iniciales si las tablas están vacías
$verificar_cat = $conn->query("SELECT id FROM categorias LIMIT 1");
if ($verificar_cat->num_rows == 0) {
    $conn->query("INSERT INTO categorias (nombre) VALUES ('Pulseras'), ('Collares'), ('Aretes');");
}

$verificar_est = $conn->query("SELECT id FROM estilos LIMIT 1");
if ($verificar_est->num_rows == 0) {
    $conn->query("INSERT INTO estilos (nombre) VALUES ('Alternativo'), ('Cute'), ('Dark'), ('Gótico');");
}

// La variable $conn ya está lista para ser usada en otros archivos
?>