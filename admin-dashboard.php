<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'administrador') {
    header("Location: sign-in.html");
    exit();
}

include('connetion/conexion.php');

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
}

// Asignar técnico a un ticket
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['asignar_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
    $id_tecnico = $_POST['id_tecnico'];
    $sql = "UPDATE tickets SET id_tecnico=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_tecnico, $id_ticket);
    $stmt->execute();
}

// Consultar tickets
$sqlTickets = "SELECT t.*, c.nombre AS cliente, te.nombre AS tecnico 
               FROM tickets t
               JOIN usuarios c ON t.id_cliente = c.id
               LEFT JOIN usuarios te ON t.id_tecnico = te.id";
$tickets = $conn->query($sqlTickets);

// Consultar técnicos
$sqlTecnicos = "SELECT * FROM usuarios WHERE rol='tecnico'";
$tecnicos = $conn->query($sqlTecnicos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e1f5fe, #b3e5fc);
            min-height: 100vh;
        }
        .navbar {
            background: #01579b;
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
        h2, h3 {
            color: #01579b;
        }
        .btn-primary {
            background: #0277bd;
            border: none;
        }
        .btn-primary:hover {
            background: #004d80;
        }
        table th {
            background: #01579b;
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
          <span class="navbar-text me-3">Administrador: <?php echo $_SESSION['correo']; ?></span>
          <a href="controllers/logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
        </div>
      </div>
    </nav>

    <!-- Contenido -->
    <div class="container container-dashboard">
        <div class="card p-4 mb-4">
            <h3 class="mb-3">Crear Técnico</h3>
            <form method="POST" class="row g-3">
                <input type="hidden" name="crear_tecnico" value="1">
                <div class="col-md-6">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="apellido" class="form-control" placeholder="Apellido" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="documento" class="form-control" placeholder="Documento" required>
                </div>
                <div class="col-md-6">
                    <input type="email" name="correo" class="form-control" placeholder="Correo" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="telefono" class="form-control" placeholder="Teléfono">
                </div>
                <div class="col-md-6">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success">Crear Técnico</button>
                </div>
            </form>
        </div>

        <div class="card p-4">
            <h3 class="mb-3">Tickets</h3>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Título</th><th>Cliente</th><th>Fecha</th>
                            <th>Prioridad</th><th>Departamento</th><th>Estado</th>
                            <th>Técnico</th><th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $tickets->fetch_assoc()): ?>
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
                            <td><?= $row['tecnico'] ?? 'Sin asignar'; ?></td>
                            <td>
                                <form method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="asignar_ticket" value="1">
                                    <input type="hidden" name="id_ticket" value="<?= $row['id']; ?>">
                                    <select name="id_tecnico" class="form-select form-select-sm">
                                        <?php
                                        // Reiniciar puntero del result set
                                        $sqlTecnicos = "SELECT * FROM usuarios WHERE rol='tecnico'";
                                        $resTecnicos = $conn->query($sqlTecnicos);
                                        while($tec = $resTecnicos->fetch_assoc()): ?>
                                            <option value="<?= $tec['id']; ?>"><?= $tec['nombre']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">Asignar</button>
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
