<?php
session_start();
// Opcional: verificar que el usuario sea Comprador
if ($_SESSION['usuario']['Rol'] != 2) {
    header("Location: otra_interface.php");
   exit;
 }

include 'header1.php'; 
?>

<div class="container mt-5">
    <h1>Panel de Comprador</h1>
    <hr>
    
    <!-- Comprar Entradas para Eventos -->
    <section class="mb-4">
        <h2>Entradas para Eventos</h2>
        <p>Listado de eventos aprobados y disponibles. El comprador puede seleccionar cuántas entradas comprar.</p>
        <!-- Ejemplo de tabla de eventos aprobados -->
        <!-- 
        <table class="table">
            <thead>
                <tr>
                    <th>ID Evento</th>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                // Listar eventos con estado = 'aprobado'
                // Botón para "Agregar al carrito" o "Comprar"
            </tbody>
        </table>
        -->
    </section>

    <hr>

    <!-- Comprar Artículos -->
    <section class="mb-4">
        <h2>Artículos en Venta</h2>
        <p>Listado de artículos aprobados. El comprador puede agregarlos a su carrito.</p>
        <!-- Ejemplo de tabla de artículos -->
        <!-- 
        <table class="table">
            <thead>
                <tr>
                    <th>ID Artículo</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                // Listar artículos con estado = 'aprobado'
                // Botón para "Agregar al carrito"
            </tbody>
        </table>
        -->
    </section>

    <hr>

    <!-- Carrito de Compras -->
    <section class="mb-4">
        <h2>Carrito de Compras</h2>
        <p>Aquí se muestran los eventos/artículos que el comprador ha seleccionado. Puede elegir método de pago y generar factura.</p>
        <!-- Ejemplo de listado del carrito -->
        <!-- 
        <table class="table">
            <thead>
                <tr>
                    <th>Tipo</th> <!-- Evento o Artículo -->
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                // Iterar items del carrito
            </tbody>
        </table>

        <div class="mt-3">
            <label for="metodoPago" class="form-label">Método de Pago</label>
            <select class="form-select" id="metodoPago" name="metodoPago">
                <option value="Tarjeta">Tarjeta</option>
                <option value="PayPal">QR</option>
                <option value="Efectivo">Efectivo</option>
            </select>
        </div>
        <button class="btn btn-success mt-2">Finalizar Compra</button>
        -->
    </section>
</div>

<?php
include 'footer.php'; 
?>
