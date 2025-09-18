<?php
session_start();
include('../connetion/conexion.php');

if ($_SESSION['rol'] != 'administrador') {
    exit("No autorizado");
}

// Crear técnico
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crear_tecnico'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $documento = $_POST['documento'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $pws = $_POST['password'];

    $sql = "INSERT INTO usuarios (nombre,apellido,documento,correo,telefono,pws,rol) 
            VALUES (?,?,?,?,?,?,'tecnico')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nombre, $apellido, $documento, $correo, $telefono, $pws);
    $stmt->execute();

    header("Location: ../admin-dashboard.php");
    exit();
}

// Eliminar usuario (cliente o técnico)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    $sql = "DELETE FROM usuarios WHERE id=? AND rol IN ('cliente','tecnico')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    header("Location: ../admin-dashboard.php");
    exit();
}
?>
