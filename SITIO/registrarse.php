Registrarse · PHP
<?php
session_start();
 
$conexion = new mysqli("localhost", "root", "", "POP ART");
 
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $nombre   = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $mail     = $_POST["mail"];
    $password = $_POST["password"];
    $usuario  = $_POST["usuario"];   // campo usuario del formulario del modal
 
    $sql = "INSERT INTO usuarios (usuario, password)
            VALUES ('$usuario', '$password')";
 
    if ($conexion->query($sql) === TRUE) {
 
        // Registro exitoso: iniciamos sesión automáticamente y volvemos a contacto
        $_SESSION["usuario"] = $usuario;
        header("Location: contacto.php");
        exit();
 
    } else {
 
        // Error: volvemos a contacto con el modal de registro abierto y el error
        header("Location: contacto.php?modal=registro&error=registro");
        exit();
    }
}
 
// Si alguien entra directo sin POST
header("Location: contacto.php?modal=registro");
exit();