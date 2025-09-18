<?php
session_start();
include('../connetion/conexion.php');

if ($_SESSION['rol'] != 'cliente') {
    exit("No autorizado");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
    $id_cliente = $_SESSION['id_usuario'];

    $sql = "DELETE FROM tickets WHERE id=? AND id_cliente=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_ticket, $id_cliente);
    $stmt->execute();
}

header("Location: ../cliente-dashboard.php");
?>
