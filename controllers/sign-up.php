<?php
session_start();
include('../connetion/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $documento = $_POST["documento"];
    $telefono = $_POST["telefono"];

    // Insertar siempre como cliente
    $sql = "INSERT INTO usuarios (nombre,apellido,documento,correo,telefono,pws,rol) 
            VALUES (?,?,?,?,?,?,'cliente')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nombre, $apellido, $documento, $email, $telefono, $password);

    if ($stmt->execute()) {
        $_SESSION["correo"] = $email;
        $_SESSION["rol"] = "cliente";
        $_SESSION["id_usuario"] = $stmt->insert_id;
        header("Location: ../cliente-dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
