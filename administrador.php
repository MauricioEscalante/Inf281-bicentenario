<?php
session_start();
// Opcional: verificar que el usuario sea Administrador
if ($_SESSION['usuario']['Rol'] != 1) {
   header("Location: otra_interface.php");
   exit;
}

include 'header1.php'; 
?>

<div class="container mt-5">
    <h1>Panel de Administración</h1>
    <hr>
    
    <!-- Gestión de Usuarios -->
    <section class="mb-4">
        <h2>Gestión de Usuarios</h2>
        <p>Aquí iría tu CRUD de usuarios (listar, crear, editar, eliminar).</p>
        <!-- Ejemplo de tabla de usuarios -->
        <!-- 
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                // Aquí iterarías con los usuarios de la BD
            </tbody>
        </table>
        -->
    </section>

    <hr>

    <!-- Gestión de Eventos -->
    <section class="mb-4">
        <h2>Gestión de Eventos</h2>
        <p>Aquí puedes listar todos los eventos creados por los organizadores y aprobar o denegar.</p>
        <!-- Ejemplo de tabla de eventos -->
        <!-- 
        <table class="table">
            <thead>
                <tr>
                    <th>ID Evento</th>
                    <th>Nombre Evento</th>
                    <th>Organizador</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                // Iterar eventos: 
                // - Mostrar estado (pendiente, aprobado, rechazado)
                // - Botones para aprobar/denegar
            </tbody>
        </table>
        -->
    </section>

    <hr>

    <!-- Gestión de Artículos en venta -->
    <section class="mb-4">
        <h2>Gestión de Artículos</h2>
        <p>Revisa los objetos que los vendedores desean poner a la venta. Aprueba o rechaza.</p>
        <!-- Ejemplo de tabla de artículos -->
        <!-- 
        <table class="table">
            <thead>
                <tr>
                    <th>ID Artículo</th>
                    <th>Nombre</th>
                    <th>Vendedor</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                // Iterar artículos
            </tbody>
        </table>
        -->
    </section>
</div>

<?php
include 'footer.php'; 
?>
