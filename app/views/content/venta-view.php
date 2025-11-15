<?php
use app\controllers\ventaController;

$ventaCtrl = new ventaController();

// Determinar la acción actual
$accion = $_GET['accion'] ?? 'form';
$id = $_GET['id'] ?? null;

// Variable para mensajes toast
$toastMessage = '';
$toastType = '';

// Procesar eliminación
if($accion === 'eliminar' && $id){
    if($ventaCtrl->eliminarVentaControlador($id)) {
        $toastMessage = 'Venta eliminada correctamente';
        $toastType = 'success';
    } else {
        $toastMessage = 'Error al eliminar la venta';
        $toastType = 'error';
    }
    $accion = 'lista';
}

// Procesar POST de formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($accion === 'form'){ // Nueva venta
        $datos = [
            "codigo" => $_POST['codigo'] ?? '',
            "fecha" => $_POST['fecha'] ?? date('Y-m-d'),
            "hora" => $_POST['hora'] ?? date('H:i'),
            "total" => $_POST['total'] ?? 0,
            "pagado" => $_POST['pagado'] ?? 0,
            "cambio" => $_POST['cambio'] ?? 0,
            "usuario_id" => $_SESSION['id'] ?? 1,
            "cliente_id" => $_POST['cliente_id'] ?? 0,
            "caja_id" => $_POST['caja_id'] ?? 0,
            "empresa_id" => $_POST['empresa_id'] ?? 0
        ];
        
        if($ventaCtrl->crearVentaControlador($datos)) {
            $toastMessage = 'Venta creada correctamente';
            $toastType = 'success';
            $accion = 'lista';
        } else {
            $toastMessage = 'Error al crear la venta';
            $toastType = 'error';
        }
    } elseif($accion === 'editar' && $id){ // Editar venta
        $datos = [
            "codigo" => $_POST['codigo'],
            "fecha" => $_POST['fecha'],
            "hora" => $_POST['hora'],
            "total" => $_POST['total'],
            "pagado" => $_POST['pagado'],
            "cambio" => $_POST['cambio'],
            "usuario_id" => $_SESSION['id'] ?? 1,
            "cliente_id" => $_POST['cliente_id'],
            "caja_id" => $_POST['caja_id'],
            "empresa_id" => $_POST['empresa_id']
        ];
        
        if($ventaCtrl->actualizarVentaControlador($id, $datos)) {
            $toastMessage = 'Venta actualizada correctamente';
            $toastType = 'success';
            $accion = 'lista';
        } else {
            $toastMessage = 'Error al actualizar la venta';
            $toastType = 'error';
        }
    }
}

// Obtener datos para editar
if($accion === 'editar' && $id){
    $ventaEditar = $ventaCtrl->obtenerVentaControlador($id);
}

if($accion === 'lista'){
    $ventas = $ventaCtrl->listarVentasControlador();
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
                <!-- FORMULARIO NUEVA VENTA -->
                <div class="box">
                    <h3 class="title is-4 has-text-centered">Nueva Venta</h3>
                    <form method="POST" autocomplete="off">
                        <div class="field">
                            <label class="label">Código</label>
                            <div class="control">
                                <input class="input" type="text" name="codigo" required placeholder="Código de venta">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Fecha</label>
                            <div class="control">
                                <input class="input" type="date" name="fecha" required value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Hora</label>
                            <div class="control">
                                <input class="input" type="time" name="hora" required value="<?= date('H:i') ?>">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Total</label>
                            <div class="control">
                                <input class="input" type="number" step="0.01" name="total" required placeholder="0.00">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Pagado</label>
                            <div class="control">
                                <input class="input" type="number" step="0.01" name="pagado" required placeholder="0.00">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Cambio</label>
                            <div class="control">
                                <input class="input" type="number" step="0.01" name="cambio" required placeholder="0.00">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Cliente ID</label>
                            <div class="control">
                                <input class="input" type="number" name="cliente_id" required placeholder="ID Cliente">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Caja ID</label>
                            <div class="control">
                                <input class="input" type="number" name="caja_id" required placeholder="ID Caja">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Empresa ID</label>
                            <div class="control">
                                <input class="input" type="number" name="empresa_id" required placeholder="ID Empresa">
                            </div>
                        </div>

                        <div class="field mt-4 has-text-centered">
                            <button type="submit" class="button is-info is-rounded">Registrar</button>
                            <a href="<?= APP_URL ?>venta?accion=lista" class="button is-light is-rounded">Ver Lista</a>
                        </div>
                    </form>
                </div>

            <?php elseif($accion === 'editar' && isset($ventaEditar)): ?>
                <!-- FORMULARIO EDITAR VENTA -->
                <div class="box">
                    <h3 class="title is-4 has-text-centered">Editar Venta</h3>
                    <form method="POST" autocomplete="off">
                        <div class="field">
                            <label class="label">Código</label>
                            <div class="control">
                                <input class="input" type="text" name="codigo" value="<?= $ventaEditar['venta_codigo'] ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Fecha</label>
                            <div class="control">
                                <input class="input" type="date" name="fecha" value="<?= $ventaEditar['venta_fecha'] ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Hora</label>
                            <div class="control">
                                <input class="input" type="time" name="hora" value="<?= $ventaEditar['venta_hora'] ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Total</label>
                            <div class="control">
                                <input class="input" type="number" step="0.01" name="total" value="<?= $ventaEditar['venta_total'] ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Pagado</label>
                            <div class="control">
                                <input class="input" type="number" step="0.01" name="pagado" value="<?= $ventaEditar['venta_pagado'] ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Cambio</label>
                            <div class="control">
                                <input class="input" type="number" step="0.01" name="cambio" value="<?= $ventaEditar['venta_cambio'] ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Cliente ID</label>
                            <div class="control">
                                <input class="input" type="number" name="cliente_id" value="<?= $ventaEditar['cliente_id'] ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Caja ID</label>
                            <div class="control">
                                <input class="input" type="number" name="caja_id" value="<?= $ventaEditar['caja_id'] ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Empresa ID</label>
                            <div class="control">
                                <input class="input" type="number" name="empresa_id" value="<?= $ventaEditar['empresa_id'] ?>" required>
                            </div>
                        </div>

                        <div class="field mt-4 has-text-centered">
                            <button type="submit" class="button is-info is-rounded">Actualizar</button>
                            <a href="<?= APP_URL ?>venta?accion=lista" class="button is-light is-rounded">Cancelar</a>
                        </div>
                    </form>
                </div>

            <?php elseif($accion === 'lista'): ?>
                <!-- TABLA DE VENTAS -->
                <div class="box">
                    <div class="level">
                        <div class="level-left">
                            <h3 class="title is-4">Ventas Registradas</h3>
                        </div>
                        <div class="level-right">
                            <a href="<?= APP_URL ?>venta?accion=form" class="button is-info is-rounded">
                                <i class="fas fa-plus"></i> &nbsp; Nueva Venta
                            </a>
                        </div>
                    </div>
                    
                    <table class="table is-striped is-fullwidth">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Total</th>
                                <th>Pagado</th>
                                <th>Cambio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($ventas)): ?>
                                <?php foreach($ventas as $venta): ?>
                                    <tr>
                                        <td><?= $venta['venta_id']; ?></td>
                                        <td><?= $venta['venta_codigo']; ?></td>
                                        <td><?= $venta['venta_fecha']; ?></td>
                                        <td><?= $venta['venta_hora']; ?></td>
                                        <td><?= number_format($venta['venta_total'], 2); ?></td>
                                        <td><?= number_format($venta['venta_pagado'], 2); ?></td>
                                        <td><?= number_format($venta['venta_cambio'], 2); ?></td>
                                        <td>
                                            <a href="<?= APP_URL ?>venta?accion=editar&id=<?= $venta['venta_id'] ?>">Editar</a> |
                                            <a href="<?= APP_URL ?>venta?accion=eliminar&id=<?= $venta['venta_id'] ?>" onclick="return confirm('¿Seguro que quieres eliminar esta venta?');">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="has-text-centered">No hay ventas registradas</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>