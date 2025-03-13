<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <title>Registro</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        html, body {
            height: 100%; /* Asegura que el html y body ocupen el 100% de la altura */
            margin: 0; /* Elimina el margen por defecto */
            
        }
        body {
            background-image: url('img/bicen1.jpeg'); /* Cambia esto a la imagen que deseas usar */
             background-size:cover; /* Esto asegura que la imagen cubra todo el fondo */
             background-position: center; /* Centra la imagen */
             background-repeat: no-repeat; /* Evita que la imagen se repita */
             background-attachment: fixed; /* Fija la imagen de fondo */
            
        }
        .form-container {
        max-width: 600px; /* Ancho máximo del formulario */
        margin: auto; /* Centrado horizontal */
        padding: 20px; /* Espaciado interno */
        background-color: rgba(255, 255, 255, 0.9); /* Fondo blanco con opacidad para el formulario */
        border-radius: 8px; /* Bordes redondeados */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra */
        position: relative; /* Asegura que el contenedor esté en relación a su posición */
        z-index: 1; /* Asegura que el formulario esté por encima de la imagen */
        top: 50px; /* Ajusta la posición del formulario si es necesario */
    }
        .text-link {
            color: black; /* Cambia 'blue' por el color que desees */
        }
        .password-toggle {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="text-center">Crea una Cuenta</h2>

        <?php
      include ("modelo/conexion_bd.php");
     include ("controlador/controlador_registrar_usuario.php"); // Verificar y mostrar mensajes
        if (isset($_SESSION['mensaje'])) {
    echo '<script>var mensaje = ' . json_encode($_SESSION['mensaje']) . ';</script>';
    unset($_SESSION['mensaje']); // Limpiar el mensaje de la sesión
        }
        ?>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" placeholder="Ingresa tus Nombres" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellidos</label>
                <input type="text" placeholder="Ingresa tus Apellidos" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" placeholder="name@example.com" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" placeholder="Crea tu contraseña" id="contrasena" name="contraseña" required>
                    <span class="input-group-text password-toggle" onclick="togglePassword('contrasena', this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <label for="repiteContrasena" class="form-label">Repite la Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" placeholder="Repite la Contraseña" id="repiteContrasena" name="rcontraseña" required>
                    <span class="input-group-text password-toggle" onclick="togglePassword('repiteContrasena', this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" placeholder="Ingresa tu telefono" class="form-control" id="telefono" name="telefono" required>
            </div>

            <div class="mb-3">
                <label for="genero" class="form-label">Genero</label>
                <select class="form-select" id="genero" name="genero"  required>
                <option value="">Selecciona el Genero</option>
                    <option value="masculino">Masculino</option>
                    <option value="femenino">Femenino</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="pais" class="form-label">País</label>
                <select class="form-select" id="pais" name="pais" onchange="cargarCiudades()" required>
                <option value="">Selecciona un país</option>

                <?php
                $result = $conexion->query("SELECT * FROM pais");
                while ($pais = $result->fetch_assoc()) {
                 echo "<option value='" . $pais['Id_pais'] . "'>" . $pais['nombre'] . "</option>";
                    }
                 ?>

                </select>
            </div>
            <div class="mb-3">
                <label for="ciudad" class="form-label">Ciudad</label>
                <select class="form-select" id="ciudad" name="ciudad" required>
                <option value="">Selecciona una ciudad</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-select" id="rol" name="rol" required>
                    <option value="">Selecciona un rol</option>
                    <option value="1">Adminstrador</option>
                    <option value="2">Comprador</option>
                    <option value="3r">Organizador</option>
                    <option value="4">Vendedor</option>
                    <!-- Otras opciones -->
                </select>
            </div>
            <!-- Widget de reCAPTCHA -->
        <div class="mb-3">
        <div class="g-recaptcha" data-sitekey="6LdzM-wqAAAAAHRL_oTuL6vg3aHgeVsmJRcZqEi-"></div>
     </div>
            <button type="submit" value="Registrar" class="btn btn-primary w-100" name="registro" >Crear Cuenta</button>
             <div class="card-footer text-center py-3 ">
        <div class="small"><a href="index.php" class="text-link">¿Tienes una cuenta? Inicia sesión</a></div>
        </div>
        </div>
    
        </form>
    
        <!-- Modal -->
    <div class="modal fade" id="mensajeModal" tabindex="-1" aria-labelledby="mensajeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mensajeModalLabel">Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="mensajeModalBody">
                    <!-- Mensaje se inyectará aquí -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        // Mostrar el modal si hay un mensaje
        document.addEventListener('DOMContentLoaded', function() {
    if (typeof mensaje !== 'undefined') {
        const modalBody = document.getElementById('mensajeModalBody');
        modalBody.innerHTML = `<div class="alert alert-${mensaje.tipo}">${mensaje.texto}</div>`;
        const modal = new bootstrap.Modal(document.getElementById('mensajeModal'));
        modal.show();
    }
});

       function cargarCiudades() {
            console.log("Evento onchange activado"); // Verifica si la función se ejecuta
    const paisId = document.getElementById("pais").value;
    console.log("ID del país seleccionado:", paisId); // Muestra el ID del país
    const ciudadSelect = document.getElementById("ciudad");
    
    ciudadSelect.innerHTML = '<option value="">Selecciona una ciudad</option>'; // Limpiar opciones anteriores
    
    if (paisId) {
        fetch(`cargar_ciudades.php?Id_pais=${paisId}`)
            .then(response => {
                console.log("Respuesta recibida:", response);
                if (!response.ok) {
                    throw new Error(`Error en la red: ${response.status}`);
                    //throw new Error('Error en la red');
                }
                return response.json();
            })
            .then(data => {
                console.log("Ciudades recibidas:", data); // Debug
                if (data.length === 0) {
                    ciudadSelect.innerHTML += '<option value="">No hay ciudades disponibles</option>';
                }else{
                    data.forEach(ciudad => {
                    ciudadSelect.innerHTML += `<option value="${ciudad.Id_ciudad}">${ciudad.nombre}</option>`;
                });
                }
                
            })
            .catch(error => console.error('Error:', error));
    }
}
    </script>
    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            icon.querySelector('i').classList.toggle('fa-eye-slash');
        }
       
    </script>  

<?php include("footer.php"); ?>
</body>
</html>
