<?php
session_start();
 
$conexion = new mysqli("localhost", "root", "", "POP ART");
 
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $usuario  = $_POST["usuario"];
    $password = $_POST["password"];
 
    $sql = "SELECT * FROM usuarios
            WHERE usuario='$usuario'
            AND password='$password'";
 
    $resultado = $conexion->query($sql);
 
    if ($resultado && $resultado->num_rows > 0) {
 
        $_SESSION["usuario"] = $usuario;
 
        // Vuelve a contacto.php con sesión iniciada
        header("Location: contacto.php");
        exit();
 
    } else {
 
        // Vuelve a contacto.php con error y abre el modal automáticamente
        header("Location: contacto.php?modal=login&error=credenciales");
        exit();
    }
}
 
// Si alguien entra directo a login.php sin POST, lo mandamos a contacto
header("Location: contacto.php?modal=login");
exit();
 