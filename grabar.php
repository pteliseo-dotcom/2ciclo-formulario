<?php
$conexion = new mysqli("localhost", "root", "", "practica"); // 👈 nombre real de tu base de datos

if ($conexion->connect_error) {
    die("❌ Error de conexión: " . $conexion->connect_error);
}

$nombreproveedor = $_POST['nombreproveedor'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$ciudad = $_POST['ciudad'];
$provincia = $_POST['provincia'];
$codigopostal = $_POST['codigopostal']; // 👈 OJO: aquí no pongas $codigopostals (tenía una “s” extra)

$sql = "INSERT INTO `proveedores`
(`id`, `nomproveedor`, `nombrecontacto`, `apellidocontacto`, `email`, `telefono`, `direccion`, `ciudad`, `provincia`, `codigopostal`)
VALUES (NULL, '$nombreproveedor', '$nombre', '$apellido', '$email', '$telefono', '$direccion', '$ciudad', '$provincia', '$codigopostal')";

if ($conexion->query($sql) === TRUE) {
    echo "✅ Registro insertado correctamente.";
} else {
    echo "❌ Error al insertar: " . $conexion->error;
}

$conexion->close();
?>

