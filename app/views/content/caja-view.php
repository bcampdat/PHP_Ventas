<?php
use app\controllers\cajaController;

$cajaCtrl = new cajaController();

// Determinar la acción actual
$accion = $_GET['accion'] ?? 'form';
$id = $_GET['id'] ?? null;

// Variable para mensajes toast
$toastMessage = '';
$toastType = '';

// Procesar eliminación
if($accion === 'eliminar' && $id){
    if($cajaCtrl->eliminarCajaControlador($id)) {
        $toastMessage = 'Caja eliminada correctamente';
        $toastType = 'success';
    } else {
        $toastMessage = 'Error al eliminar la caja';
        $toastType = 'error';
    }
    $accion = 'lista'; // Mostrar lista después de eliminar
}

// Procesar POST de formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($accion === 'form'){ // Nueva caja
        $datos = [
            "numero" => $_POST['numero'] ?? '',
            "nombre" => $_POST['nombre'] ?? '',
            "efectivo" => $_POST['efectivo'] ?? 0.00
        ];
        
        if($cajaCtrl->crearCajaControlador($datos)) {
            $toastMessage = 'Caja creada correctamente';
            $toastType = 'success';
            $accion = 'lista'; 
        } else {
            $toastMessage = 'Error al crear la caja';
            $toastType = 'error';
        }
    } elseif($accion === 'editar' && $id){ // Editar caja
        $datos = [
            "numero" => $_POST['numero'],
            "nombre" => $_POST['nombre'],
            "efectivo" => $_POST['efectivo']
        ];
        
        if($cajaCtrl->actualizarCajaControlador($id, $datos)) {
            $toastMessage = 'Caja actualizada correctamente';
            $toastType = 'success';
            $accion = 'lista'; 
        } else {
            $toastMessage = 'Error al actualizar la caja';
            $toastType = 'error';
        }
    }
}

// Obtener datos para editar
if($accion === 'editar' && $id){
    $cajaEditar = $cajaCtrl->obtenerCajaControlador($id);
}

if($accion === 'lista'){
    $cajas = $cajaCtrl->listarCajasControlador();
}
?>

<div class="container">
    <div class="columns">
        <div class="column is-8 is-offset-2">

            <!-- Toast Notification -->
            <?php if($toastMessage): ?>
            <div class="notification is-<?= $toastType === 'success' ? 'success' : 'danger' ?> is-light" id="toast">
                <button class="delete" onclick="document.getElementById('toast').remove()"></button>
                <?= $toastMessage ?>
            </div>
            <script>
                setTimeout(() => {
                    const toast = document.getElementById('toast');
                    if(toast) toast.remove();
                }, 2000);
            </script>
            <?php endif; ?>

            <?php if($accion === 'form'): ?>
                <!-- FORMULARIO NUEVA CAJA -->
                <div class="box">
                    <h3 class="title is-4 has-text-centered">Nueva Caja</h3>
                    <form method="POST" autocomplete="off">
                        <div class="field">
                            <label class="label">Número</label>
                            <div class="control">
                                <input class="input" type="number" name="numero" required placeholder="Número de caja">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Nombre</label>
                            <div class="control">
                                <input class="input" type="text" name="nombre" required placeholder="Nombre de la caja">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Efectivo Inicial</label>
                            <div class="control">
                                <input class="input" type="number" step="0.01" name="efectivo" required value="0.00" placeholder="0.00">
                            </div>
                        </div>

                        <div class="field mt-4 has-text-centered">
                            <button type="submit" class="button is-info is-rounded">Registrar</button>
                            <a href="<?= APP_URL ?>caja?accion=lista" class="button is-light is-rounded">Ver Lista</a>
                        </div>
                    </form>
                </div>

            <?php elseif($accion === 'editar' && isset($cajaEditar)): ?>
                <!-- FORMULARIO EDITAR CAJA -->
                <div class="box">
                    <h3 class="title is-4 has-text-centered">Editar Caja</h3>
                    <form method="POST" autocomplete="off">
                        <div class="field">
                            <label class="label">Número</label>
                            <div class="control">
                                <input class="input" type="number" name="numero" value="<?= $cajaEditar['caja_numero'] ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Nombre</label>
                            <div class="control">
                                <input class="input" type="text" name="nombre" value="<?= $cajaEditar['caja_nombre'] ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Efectivo</label>
                            <div class="control">
                                <input class="input" type="number" step="0.01" name="efectivo" value="<?= $cajaEditar['caja_efectivo'] ?>" required>
                            </div>
                        </div>

                        <div class="field mt-4 has-text-centered">
                            <button type="submit" class="button is-info is-rounded">Actualizar</button>
                            <a href="<?= APP_URL ?>caja?accion=lista" class="button is-light is-rounded">Cancelar</a>
                        </div>
                    </form>
                </div>

            <?php elseif($accion === 'lista'): ?>
                <!-- TABLA DE CAJAS -->
                <div class="box">
                    <div class="level">
                        <div class="level-left">
                            <h3 class="title is-4">Cajas Registradas</h3>
                        </div>
                        <div class="level-right">
                            <a href="<?= APP_URL ?>caja?accion=form" class="button is-info is-rounded">
                                <i class="fas fa-plus"></i> &nbsp; Nueva Caja
                            </a>
                        </div>
                    </div>
                    
                    <table class="table is-striped is-fullwidth">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Número</th>
                                <th>Nombre</th>
                                <th>Efectivo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($cajas)): ?>
                                <?php foreach($cajas as $caja): ?>
                                    <tr>
                                        <td><?= $caja['caja_id']; ?></td>
                                        <td><?= $caja['caja_numero']; ?></td>
                                        <td><?= $caja['caja_nombre']; ?></td>
                                        <td><?= number_format($caja['caja_efectivo'], 2); ?></td>
                                        <td>
                                            <a href="<?= APP_URL ?>caja?accion=editar&id=<?= $caja['caja_id'] ?>">Editar</a> |
                                            <a href="<?= APP_URL ?>caja?accion=eliminar&id=<?= $caja['caja_id'] ?>">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="has-text-centered">No hay cajas registradas</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>