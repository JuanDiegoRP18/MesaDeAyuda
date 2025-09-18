<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'tecnico') {
    header("Location: sign-in.html");
    exit();
}

include('connetion/conexion.php');
$id_tecnico = $_SESSION['id_usuario'];

// Cambiar estado de ticket
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_ticket'])) {
    $estado = $_POST['estado'];
    $id_ticket = $_POST['id_ticket'];
    $sqlUpdate = "UPDATE tickets SET estado=? WHERE id=? AND id_tecnico=?";
    $stmt = $conn->prepare($sqlUpdate);
    $stmt->bind_param("sii", $estado, $id_ticket, $id_tecnico);
    $stmt->execute();
}

// Consultar tickets asignados al técnico
$sql = "SELECT t.*, u.nombre AS cliente 
        FROM tickets t 
        JOIN usuarios u ON t.id_cliente = u.id 
        WHERE t.id_tecnico = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_tecnico);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Técnico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f1f8e9, #c5e1a5);
            min-height: 100vh;
        }
        .navbar {
            background: #33691e;
        }
        .navbar-brand, .nav-link, .navbar-text {
            color: #fff !important;
        }
        .container-dashboard {
            margin-top: 40px;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            color: #33691e;
        }
        .btn-success {
            background: #558b2f;
            border: none;
        }
        .btn-success:hover {
            background: #33691e;
        }
        table th {
            background: #33691e;
            color: #fff;
        }
        table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">ServiceDesk</a>
        <div class="d-flex">
          <span class="navbar-text me-3">Técnico: <?php echo $_SESSION['correo']; ?></span>
          <a href="controllers/logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
        </div>
      </div>
    </nav>

    <!-- Contenido -->
    <div class="container container-dashboard">
        <div class="card p-4">
            <h2 class="mb-3">Tickets Asignados</h2>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Título</th><th>Cliente</th><th>Fecha</th>
                            <th>Prioridad</th><th>Departamento</th><th>Estado</th><th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['titulo']; ?></td>
                            <td><?= $row['cliente']; ?></td>
                            <td><?= $row['fecha']; ?></td>
                            <td><span class="badge bg-info"><?= $row['prioridad']; ?></span></td>
                            <td><?= $row['departamento']; ?></td>
                            <td>
                                <?php if ($row['estado'] == 'Abierto'): ?>
                                    <span class="badge bg-success">Abierto</span>
                                <?php elseif ($row['estado'] == 'En Proceso'): ?>
                                    <span class="badge bg-warning text-dark">En Proceso</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Cerrado</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="id_ticket" value="<?= $row['id']; ?>">
                                    <select name="estado" class="form-select form-select-sm">
                                        <option <?= $row['estado']=='Abierto'?'selected':''; ?>>Abierto</option>
                                        <option <?= $row['estado']=='En Proceso'?'selected':''; ?>>En Proceso</option>
                                        <option <?= $row['estado']=='Cerrado'?'selected':''; ?>>Cerrado</option>
                                    </select>
                                    <button type="submit" class="btn btn-success btn-sm">Actualizar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
