<?php
session_start();
// Opcional: verificar que el usuario sea Organizador
if ($_SESSION['usuario']['Rol'] != 3) {
  header("Location: otra_interface.php");
  exit;
 }

include 'header1.php'; 
?>

<div class="container mt-5">
    <h1>Panel de Organizador</h1>
    <hr>
    
    <!-- Crear Eventos -->
    <section class="mb-4">
        <h2>Crear Evento</h2>
        <p>Formulario para proponer un nuevo evento. El administrador debe aprobarlo.</p>
        <!-- Ejemplo de formulario -->
        <!-- 
        <form method="POST" action="organizador_interface.php">
            <div class="mb-3">
                <label for="nombreEvento" class="form-label">Nombre del Evento</label>
                <input type="text" class="form-control" id="nombreEvento" name="nombreEvento" required>
            </div>
            <div class="mb-3">
                <label for="fechaEvento" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fechaEvento" name="fechaEvento" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="crearEvento">Crear</button>
        </form>
        -->
    </section>

    <hr>

    <!-- Listar / Eliminar Eventos -->
    <section class="mb-4">
        <h2>Mis Eventos</h2>
        <p>Lista de tus eventos, su estado (pendiente, aprobado, rechazado), y opción para eliminar.</p>
        <!-- Ejemplo de tabla de eventos -->
        <!-- 
        <table class="table">
            <thead>
                <tr>
                    <th>ID Evento</th>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                // Iterar eventos del organizador
            </tbody>
        </table>
        -->
    </section>
</div>

<?php
include 'footer.php'; 
?>
