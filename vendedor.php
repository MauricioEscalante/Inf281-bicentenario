<?php
session_start();
// Opcional: verificar que el usuario sea Vendedor
 if ($_SESSION['usuario']['Rol'] != 4) {
     header("Location: otra_interface.php");
    exit;
}

include 'header1.php'; 
?>

<div class="container mt-5">
    <h1>Panel de Vendedor</h1>
    <hr>
    
    <!-- Subir Artículos a la Venta -->
    <section class="mb-4">
        <h2>Agregar Artículos</h2>
        <p>Formulario para crear un nuevo artículo que debe aprobar el administrador.</p>
        <!-- Ejemplo de formulario -->
        <!-- 
        <form method="POST" action="vendedor_interface.php">
            <div class="mb-3">
                <label for="nombreArticulo" class="form-label">Nombre del Artículo</label>
                <input type="text" class="form-control" id="nombreArticulo" name="nombreArticulo" required>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" required>
            </div>
            <button type="submit" class="btn btn-primary" name="agregarArticulo">Agregar</button>
        </form>
        -->
    </section>

    <hr>

    <!-- Listar / Eliminar Artículos -->
    <section class="mb-4">
        <h2>Mis Artículos</h2>
        <p>Aquí ves todos tus artículos. Puedes eliminarlos o revisar su estado (pendiente, aprobado, rechazado).</p>
        <!-- Ejemplo de tabla de artículos -->
        <!-- 
        <table class="table">
            <thead>
                <tr>
                    <th>ID Artículo</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                // Iterar artículos del vendedor
            </tbody>
        </table>
        -->
    </section>

    <hr>

    <!-- Historial de Ventas -->
    <section class="mb-4">
        <h2>Historial de Ventas</h2>
        <p>Ver quién compró tus artículos, cuántas unidades, fecha, etc.</p>
        <!-- Ejemplo de tabla de ventas -->
        <!-- 
        <table class="table">
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Artículo</th>
                    <th>Comprador</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                // Iterar ventas relacionadas con este vendedor
            </tbody>
        </table>
        -->
    </section>
</div>

<?php
include 'footer.php'; 
?>
