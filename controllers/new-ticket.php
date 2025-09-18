<?php
session_start();
include('../connetion/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['rol'] == 'cliente') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $prioridad = $_POST['prioridad'];
    $departamento = $_POST['departamento'];
    $estado = $_POST['estado'];
    $id_cliente = $_SESSION['id_usuario'];
    $id_tecnico = !empty($_POST['id_tecnico']) ? $_POST['id_tecnico'] : NULL;

    $sql = "INSERT INTO tickets (titulo, descripcion, fecha, prioridad, departamento, estado, id_cliente, id_tecnico) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssiii", $titulo, $descripcion, $fecha, $prioridad, $departamento, $estado, $id_cliente, $id_tecnico);

    if ($stmt->execute()) {
        header("Location: ../cliente-dashboard.php");
        exit();
    } else {
        echo "Error al crear el ticket: " . $stmt->error;
    }
}
?>
