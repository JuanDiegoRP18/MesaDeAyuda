<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'cliente') {
    header("Location: sign-in.html");
    exit();
}

include('connetion/conexion.php');
$id_cliente = $_SESSION['id_usuario'];

// Consultar tickets del cliente
$sql = "SELECT t.*, u.nombre AS tecnico 
        FROM tickets t 
        LEFT JOIN usuarios u ON t.id_tecnico = u.id 
        WHERE t.id_cliente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #80deea);
            min-height: 100vh;
        }
        .navbar {
            background: #006064;
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
            color: #006064;
        }
        .btn-primary {
            background: #00838f;
            border: none;
        }
        .btn-primary:hover {
            background: #004d40;
        }
        table th {
            background: #006064;
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
          <span class="navbar-text me-3">Cliente: <?php echo $_SESSION['correo']; ?></span>
          <a href="controllers/logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
        </div>
      </div>
    </nav>

    <!-- Contenido -->
    <div class="container container-dashboard">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Mis Tickets</h2>
                <a href="new-ticket.html" class="btn btn-primary">+ Crear Nuevo Ticket</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Título</th><th>Descripción</th><th>Fecha</th>
                            <th>Prioridad</th><th>Departamento</th><th>Estado</th>
                            <th>Técnico</th><th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['titulo']; ?></td>
                            <td><?= $row['descripcion']; ?></td>
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
                            <td><?= $row['tecnico'] ?? 'Sin asignar'; ?></td>
                            <td>
                                <form method="POST" action="controllers/delete-ticket.php" onsubmit="return confirm('¿Seguro que deseas eliminar este ticket?');">
                                    <input type="hidden" name="id_ticket" value="<?= $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
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
