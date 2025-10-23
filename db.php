<?php
$host = "localhost";
$user = "root";       // usuario por defecto en XAMPP
$pass = "";           // contraseña vacía en XAMPP (a menos que le hayas puesto una)
$db   = "huellitasdb"; // tu base importada

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
