<?php

use app\controllers\usuarioController;
use app\controllers\empresaController;

$usuarioController = new usuarioController();
$empresaController = new empresaController();

// Obtener datos del usuario logueado
$usuario_id = $_SESSION['id'] ?? null;
$usuario = null;

if ($usuario_id) {
    $usuario = $usuarioController->obtenerUsuario($usuario_id);
}

// Verificar si estamos en modo edición
$modo_edicion = isset($_GET['accion']) && $_GET['accion'] === 'editar';

// PROCESAR FORMULARIO EN LA MISMA VISTA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario_id'])) {
    $usuario_id = $_POST['usuario_id'];

    // Verificar si es eliminar foto
    if (isset($_POST['eliminar_foto'])) {
        $usuarioActual = $usuarioController->obtenerUsuario($usuario_id);
        if ($usuarioActual['usuario_foto'] && $usuarioActual['usuario_foto'] !== 'default.png') {
            // Eliminar archivo físico
            $rutaArchivo = $_SERVER['DOCUMENT_ROOT'] . '/POS/app/views/fotos/' . $usuarioActual['usuario_foto'];
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
            // Actualizar base de datos
            $datos = [
                'nombre' => $usuarioActual['usuario_nombre'],
                'apellido' => $usuarioActual['usuario_apellido'],
                'email' => $usuarioActual['usuario_email'],
                'usuario' => $usuarioActual['usuario_usuario'],
                'cargo' => $usuarioActual['usuario_cargo'],
                'caja' => $usuarioActual['caja_id'],
                'empresa' => $usuarioActual['empresa_id'],
                'foto' => 'default.png'
            ];
            $resultado = $usuarioController->actualizarUsuario($usuario_id, $datos);
            if ($resultado) {
                $usuario = $usuarioController->obtenerUsuario($usuario_id);
                $toast_message = "Foto eliminada correctamente";
                $toast_type = "success";
            }
        }
    } else {
        // Actualizar datos normales
        $datos = [
            'nombre' => $_POST['nombre'] ?? '',
            'apellido' => $_POST['apellido'] ?? '',
            'email' => $_POST['email'] ?? '',
            'usuario' => $_POST['usuario'] ?? '',
            'cargo' => $_POST['cargo'] ?? '',
            'caja' => $_POST['caja'] ?? 1,
            'empresa' => $_POST['empresa'] ?? 1
        ];

        $resultado = $usuarioController->actualizarUsuario($usuario_id, $datos);

        if ($resultado) {
            $usuario = $usuarioController->obtenerUsuario($usuario_id);
            $modo_edicion = false;
            $toast_message = "Datos actualizados correctamente";
            $toast_type = "success";

            // Actualizar datos en sesión
            $_SESSION['nombre'] = $usuario['usuario_nombre'];
            $_SESSION['apellido'] = $usuario['usuario_apellido'];
            $_SESSION['usuario'] = $usuario['usuario_usuario'];
            $_SESSION['cargo'] = $usuario['usuario_cargo'];
        } else {
            $toast_message = "Error al actualizar los datos";
            $toast_type = "error";
        }
    }
}
?>

<!-- Toast Notification -->
<?php if (isset($toast_message)): ?>
    <div id="toast" class="notification is-<?= $toast_type ?> is-light" style="position: fixed; top: 20px; right: 20px; z-index: 1000; min-width: 300px;">
        <button class="delete" onclick="document.getElementById('toast').remove()"></button>
        <?= $toast_message ?>
    </div>
    <script>
        setTimeout(function() {
            const toast = document.getElementById('toast');
            if (toast) toast.remove();
        }, 2000);
    </script>
<?php endif; ?>

<div class="container">
    <div class="columns">
        <div class="column is-8 is-offset-2">
            <div class="box">
                <div class="level">
                    <div class="level-left">
                        <h3 class="title is-4">Mi Cuenta</h3>
                    </div>
                    <div class="level-right">
                        <?php if ($usuario && !$modo_edicion): ?>
                            <a href="?accion=editar" class="button is-info is-rounded">
                                <i class="fas fa-plus"></i>&nbsp; Editar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($usuario): ?>

                    <?php if ($modo_edicion): ?>
                        <!-- MODO EDICIÓN -->
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="usuario_id" value="<?= $usuario['usuario_id']; ?>">

                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Nombre</label>
                                        <div class="control">
                                            <input class="input" type="text" name="nombre"
                                                value="<?= htmlspecialchars($usuario['usuario_nombre']); ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Apellido</label>
                                        <div class="control">
                                            <input class="input" type="text" name="apellido"
                                                value="<?= htmlspecialchars($usuario['usuario_apellido']); ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Email</label>
                                        <div class="control">
                                            <input class="input" type="email" name="email"
                                                value="<?= htmlspecialchars($usuario['usuario_email']); ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Usuario</label>
                                        <div class="control">
                                            <input class="input" type="text" name="usuario"
                                                value="<?= htmlspecialchars($usuario['usuario_usuario']); ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Cargo</label>
                                        <div class="control">
                                            <input class="input" type="text" value="<?= htmlspecialchars($usuario['usuario_cargo']); ?>" readonly>
                                            <p class="help">El cargo no se puede modificar desde aquí</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Caja</label>
                                        <div class="control">
                                            <input class="input" type="number" name="caja"
                                                value="<?= htmlspecialchars($usuario['caja_id']); ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-12">
                                    <div class="field">
                                        <label class="label">Foto de Perfil</label>

                                        <?php if ($usuario['usuario_foto'] && $usuario['usuario_foto'] !== 'default.png'): ?>
                                            <p class="help">Foto actual:</p>
                                            <div class="mb-3" style="position: relative; display: inline-block;">                                                
                                                <div style="position: relative; display: inline-block;">
                                                    <img src="<?= APP_URL ?>app/views/fotos/<?= htmlspecialchars($usuario['usuario_foto']); ?>"
                                                        alt="Foto perfil"
                                                        style="max-width: 150px; max-height: 150px; border: 1px solid #ddd; border-radius: 50%;">
                                                    <button type="submit" name="eliminar_foto" value="1"
                                                        class="button is-small is-ghost btn-eliminar-foto"
                                                        title="Eliminar foto">
                                                        <i class="fas fa-times icono-eliminar"></i>
                                                    </button>
                                                </div>
                                                <p class="help"><?= htmlspecialchars($usuario['usuario_foto']); ?></p>
                                            </div>
                                        <?php else: ?>
                                            <!-- Mostrar foto por defecto -->
                                            <div class="mb-3">
                                                <p class="help">Foto por defecto:</p>
                                                <img src="<?= APP_URL ?>app/views/fotos/default.png"
                                                    alt="Foto por defecto"
                                                    style="max-width: 150px; max-height: 150px; border: 1px solid #ddd; border-radius: 50%;">
                                            </div>
                                        <?php endif; ?>

                                        <div class="control">
                                            <input class="input" type="file" name="foto" accept="image/*">
                                        </div>
                                        <p class="help">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</p>
                                    </div>
                                </div>
                            </div>

                            <div class="field is-grouped mt-4">
                                <div class="control">
                                    <button type="submit" class="button is-info is-rounded">
                                        <i class="fas fa-save"></i>&nbsp; Guardar Cambios
                                    </button>
                                </div>
                                <div class="control">
                                    <a href="?" class="button is-light is-rounded">
                                        <i class="fas fa-times"></i>&nbsp; Cancelar
                                    </a>
                                </div>
                            </div>
                        </form>

                    <?php else: ?>
                        <!-- MODO VISUALIZACIÓN -->
                        <div class="columns">
                            <div class="column is-4 has-text-centered">
                                <?php if ($usuario['usuario_foto'] && $usuario['usuario_foto'] !== 'default.png'): ?>
                                    <figure class="image is-128x128" style="margin: 0 auto;">
                                        <img class="is-rounded" src="<?= APP_URL ?>app/views/fotos/<?= htmlspecialchars($usuario['usuario_foto']); ?>" alt="Foto perfil">
                                    </figure>
                                <?php else: ?>
                                    <figure class="image is-128x128" style="margin: 0 auto;">
                                        <img class="is-rounded" src="<?= APP_URL ?>app/views/fotos/default.png" alt="Foto por defecto">
                                    </figure>
                                <?php endif; ?>
                            </div>
                            <div class="column is-8">
                                <div class="content">
                                    <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['usuario_nombre']); ?></p>
                                    <p><strong>Apellido:</strong> <?= htmlspecialchars($usuario['usuario_apellido']); ?></p>
                                    <p><strong>Email:</strong> <?= htmlspecialchars($usuario['usuario_email']); ?></p>
                                    <p><strong>Usuario:</strong> <?= htmlspecialchars($usuario['usuario_usuario']); ?></p>
                                    <p><strong>Cargo:</strong> <?= htmlspecialchars($usuario['usuario_cargo']); ?></p>
                                    <p><strong>Caja:</strong> <?= htmlspecialchars($usuario['caja_id']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <p class="has-text-centered">No se encontraron datos del usuario.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>