<?php

use app\controllers\empresaController;

$empresaController = new empresaController();

if ($empresa_id) {
    $empresa = $empresaController->obtenerEmpresaControlador($empresa_id);
} else {
    $empresa = null;
}

// Verificar si estamos en modo edición
$modo_edicion = isset($_GET['accion']) && $_GET['accion'] === 'editar';

// PROCESAR FORMULARIO EN LA MISMA VISTA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['empresa_id'])) {
    $empresa_id = $_POST['empresa_id'];

    // Verificar si es eliminar foto
    if (isset($_POST['eliminar_foto'])) {
        $empresaActual = $empresaController->obtenerEmpresaControlador($empresa_id);
        if ($empresaActual['empresa_foto'] && $empresaActual['empresa_foto'] !== 'default.png') {
            // Eliminar archivo físico
            $rutaArchivo = $_SERVER['DOCUMENT_ROOT'] . '/POS/app/views/img/' . $empresaActual['empresa_foto'];
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
            // Actualizar base de datos
            $datos = [
                'nombre' => $empresaActual['empresa_nombre'],
                'telefono' => $empresaActual['empresa_telefono'],
                'email' => $empresaActual['empresa_email'],
                'direccion' => $empresaActual['empresa_direccion'],
                'foto' => 'default.png'
            ];
            $resultado = $empresaController->actualizarEmpresa($empresa_id, $datos);
            if ($resultado) {
                $empresa = $empresaController->obtenerEmpresaControlador($empresa_id);
                $toast_message = "Foto eliminada correctamente";
                $toast_type = "success";
            }
        }
    } else {
        // Actualizar datos normales
        $resultado = $empresaController->actualizarEmpresaControlador($empresa_id);

        if ($resultado) {
            $empresa = $empresaController->obtenerEmpresaControlador($empresa_id);
            $modo_edicion = false;
            $toast_message = "Empresa actualizada correctamente";
            $toast_type = "success";
        } else {
            $toast_message = "Error al actualizar la empresa";
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
        // Auto-remove toast after 2 seconds
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
                        <h3 class="title is-4">Datos de la Empresa</h3>
                    </div>
                    <div class="level-right">
                        <?php if ($empresa && !$modo_edicion): ?>
                            <a href="?accion=editar" class="button is-info is-rounded">
                                <i class="fas fa-edit"></i>&nbsp; Editar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($empresa): ?>

                    <?php if ($modo_edicion): ?>
                        <!-- MODO EDICIÓN -->
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="empresa_id" value="<?= $empresa['empresa_id']; ?>">

                            <div class="field">
                                <label class="label">Nombre</label>
                                <div class="control">
                                    <input class="input" type="text" name="nombre"
                                        value="<?= htmlspecialchars($empresa['empresa_nombre']); ?>" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Teléfono</label>
                                <div class="control">
                                    <input class="input" type="text" name="telefono"
                                        value="<?= htmlspecialchars($empresa['empresa_telefono']); ?>">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Email</label>
                                <div class="control">
                                    <input class="input" type="email" name="email"
                                        value="<?= htmlspecialchars($empresa['empresa_email']); ?>">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Dirección</label>
                                <div class="control">
                                    <textarea class="textarea" name="direccion"><?= htmlspecialchars($empresa['empresa_direccion']); ?></textarea>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Logo</label>

                                <?php if ($empresa['empresa_foto'] && $empresa['empresa_foto'] !== 'default.png'): ?>
                                    <!-- Mostrar logo actual si existe con papelera -->
                                     <p class="help">Logo actual:</p>
                                    <div class="mb-3" style="position: relative; display: inline-block;">                                        
                                        <img src="<?= APP_URL ?>app/views/img/<?= htmlspecialchars($empresa['empresa_foto']); ?>"
                                            alt="Logo empresa"
                                            style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                                        <button type="submit" name="eliminar_foto" value="1"
                                            class="button is-small is-ghost btn-eliminar-foto"
                                            title="Eliminar foto">
                                            <i class="fas fa-times icono-eliminar"></i>
                                        </button>
                                        <p class="help"><?= htmlspecialchars($empresa['empresa_foto']); ?></p>
                                    </div>
                                <?php else: ?>
                                    <!-- Mostrar logo por defecto -->
                                    <div class="mb-3">
                                        <p class="help">Logo por defecto:</p>
                                        <img src="<?= APP_URL ?>app/views/img/default.png"
                                            alt="Logo por defecto"
                                            style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                                    </div>
                                <?php endif; ?>

                                <div class="control">
                                    <input class="input" type="file" name="foto" accept="image/*">
                                </div>
                                <p class="help">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</p>
                            </div>

                            <div class="field is-grouped">
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
                        <div class="content">
                            <p><strong>Nombre:</strong> <?= htmlspecialchars($empresa['empresa_nombre']); ?></p>
                            <p><strong>Teléfono:</strong> <?= htmlspecialchars($empresa['empresa_telefono']); ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($empresa['empresa_email']); ?></p>
                            <p><strong>Dirección:</strong> <?= htmlspecialchars($empresa['empresa_direccion']); ?></p>

                            <?php if ($empresa['empresa_foto'] && $empresa['empresa_foto'] !== 'default.png'): ?>
                                <figure class="image is-128x128 mt-3">
                                    <img src="<?= APP_URL ?>app/views/img/<?= htmlspecialchars($empresa['empresa_foto']); ?>" alt="Logo empresa">
                                </figure>
                            <?php else: ?>
                                <figure class="image is-128x128 mt-3">
                                    <img src="<?= APP_URL ?>app/views/img/default.png" alt="Logo por defecto">
                                </figure>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <p class="has-text-centered">No se encontró la empresa asociada a este usuario.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>