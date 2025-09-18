<?php
session_start();
include('../connetion/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["user"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM usuarios WHERE correo = ? AND pws = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION["id_usuario"] = $user['id'];
        $_SESSION["correo"] = $user['correo'];
        $_SESSION["rol"] = $user['rol'];

        if ($user['rol'] == 'administrador') {
            header("Location: ../admin-dashboard.php");
        } elseif ($user['rol'] == 'tecnico') {
            header("Location: ../tecnico-dashboard.php");
        } else {
            header("Location: ../cliente-dashboard.php");
        }
        exit();
    } else {
        echo "Correo o contraseña incorrectos.";
    }
}
?>
