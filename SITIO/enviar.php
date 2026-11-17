<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$conexion = new mysqli("localhost", "root", "", "POP ART");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$usuario = $_SESSION["usuario"];
$opinion = $_POST["opinion"];

$sql = "INSERT INTO opiniones (usuario, opinion) VALUES ('$usuario', '$opinion')";

if ($conexion->query($sql) === TRUE) {
    header("Location: contacto.php");
    exit();
} else {
    echo "Error al guardar la opinión: " . $conexion->error;
}
?>