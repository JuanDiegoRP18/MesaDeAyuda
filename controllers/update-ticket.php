<?php
session_start();
include('../connetion/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_ticket'], $_POST['estado'])) {
    $id_ticket = $_POST['id_ticket'];
    $estado = $_POST['estado'];

    if ($_SESSION['rol'] == 'tecnico') {
        $id_tecnico = $_SESSION['id_usuario'];
        $sql = "UPDATE tickets SET estado=? WHERE id=? AND id_tecnico=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $estado, $id_ticket, $id_tecnico);
    } elseif ($_SESSION['rol'] == 'administrador') {
        $sql = "UPDATE tickets SET estado=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $estado, $id_ticket);
    } else {
        exit("No autorizado");
    }

    $stmt->execute();
}

if ($_SESSION['rol'] == 'tecnico') {
    header("Location: ../tecnico-dashboard.php");
} else {
    header("Location: ../admin-dashboard.php");
}
?>
