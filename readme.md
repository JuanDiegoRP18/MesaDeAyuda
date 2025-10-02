# MesaDeAyuda

MesaDeAyuda es una aplicación web para la gestión y creación de tickets de soporte técnico. Permite a los usuarios registrar incidencias, hacer seguimiento y gestionar los tickets a través de diferentes roles (cliente, técnico, administrador).

## Características principales

- **Registro y autenticación de usuarios** (clientes, técnicos, administradores)
- **Creación de tickets** por parte de los clientes
- **Gestión y actualización de tickets** por técnicos y administradores
- **Paneles de control** personalizados según el rol:
  - Cliente: seguimiento de sus tickets
  - Técnico: visualización y gestión de tickets asignados
  - Administrador: gestión de usuarios y tickets
- **Interfaz web responsiva**

## Estructura del proyecto

```
MesaDeAyuda/
├── admin-dashboard.php           # Panel de administrador
├── cliente-dashboard.php         # Panel de cliente
├── tecnico-dashboard.php         # Panel de técnico
├── index.html                    # Página principal
├── menuindex.html                # Menú de navegación
├── new-ticket.html               # Formulario para crear tickets
├── sign-in.html                  # Inicio de sesión
├── sign-up.html                  # Registro de usuario
├── connetion/
│   └── conexion.php              # Conexión a la base de datos
├── controllers/
│   ├── delete-ticket.php         # Eliminar tickets
│   ├── logout.php                # Cerrar sesión
│   ├── manage-users.php          # Gestión de usuarios
│   ├── new-ticket.php            # Lógica para crear tickets
│   ├── sign-in.php               # Lógica de inicio de sesión
│   ├── sign-up.php               # Lógica de registro
│   └── update-ticket.php         # Actualizar tickets
├── public/
│   ├── css/                      # Hojas de estilo
│   ├── fonts/                    # Fuentes
│   ├── img/                      # Imágenes
│   └── js/                       # Scripts JavaScript
└── readme.md                     # Este archivo
```

## Instalación y uso

1. **Clona el repositorio:**
   ```bash
   git clone https://github.com/JuanDiegoRP18/MesaDeAyuda.git
   ```
2. **Configura la base de datos:**
   - Edita `MesaDeAyuda/connetion/conexion.php` con tus credenciales de MySQL.
   - Importa el esquema de la base de datos (no incluido, pero debe contener tablas para usuarios, tickets, etc.).
3. **Despliega en un servidor web** compatible con PHP (por ejemplo, XAMPP, WAMP, LAMP).
4. **Accede a la aplicación** desde tu navegador en la ruta correspondiente.

## Requisitos
- PHP 7.x o superior
- MySQL
- Servidor web (Apache, Nginx, etc.)

## Créditos
Desarrollado por JuanDiegoRP18 y Bren304

## Licencia
Este proyecto está bajo la licencia MIT.
