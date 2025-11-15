<?php

use app\controllers\clienteController;

$clienteCtrl = new clienteController();

// Determinar la acción actual
$accion = $_GET['accion'] ?? 'form';
$id = $_GET['id'] ?? null;

// Variable para mensajes toast
$toastMessage = '';
$toastType = '';

// Procesar eliminación
if ($accion === 'eliminar' && $id) {
    if ($clienteCtrl->eliminarClienteControlador($id)) {
        $toastMessage = 'Cliente eliminado correctamente';
        $toastType = 'success';
    } else {
        $toastMessage = 'Error al eliminar el cliente';
        $toastType = 'error';
    }
    $accion = 'lista'; // Mostrar lista después de eliminar
}

// Procesar POST de formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($accion === 'form') { // Nuevo cliente
        $datos = [
            "tipo_documento" => $_POST['tipo_documento'] ?? '',
            "numero_documento" => $_POST['numero_documento'] ?? '',
            "nombre" => $_POST['nombre'] ?? '',
            "apellido" => $_POST['apellido'] ?? '',
            "provincia" => $_POST['provincia'] ?? '',
            "ciudad" => $_POST['ciudad'] ?? '',
            "direccion" => $_POST['direccion'] ?? '',
            "telefono" => $_POST['telefono'] ?? '',
            "email" => $_POST['email'] ?? ''
        ];

        if ($clienteCtrl->crearClienteControlador($datos)) {
            $toastMessage = 'Cliente creado correctamente';
            $toastType = 'success';
            $accion = 'lista';
        } else {
            $toastMessage = 'Error al crear el cliente';
            $toastType = 'error';
        }
    } elseif ($accion === 'editar' && $id) { // Editar cliente
        $datos = [
            "tipo_documento" => $_POST['tipo_documento'],
            "numero_documento" => $_POST['numero_documento'],
            "nombre" => $_POST['nombre'],
            "apellido" => $_POST['apellido'],
            "provincia" => $_POST['provincia'],
            "ciudad" => $_POST['ciudad'],
            "direccion" => $_POST['direccion'],
            "telefono" => $_POST['telefono'],
            "email" => $_POST['email']
        ];

        if ($clienteCtrl->actualizarClienteControlador($id, $datos)) {
            $toastMessage = 'Cliente actualizado correctamente';
            $toastType = 'success';
            $accion = 'lista';
        } else {
            $toastMessage = 'Error al actualizar el cliente';
            $toastType = 'error';
        }
    }
}

// Obtener datos para editar
if ($accion === 'editar' && $id) {
    $clienteEditar = $clienteCtrl->obtenerClienteControlador($id);
}

if ($accion === 'lista') {
    $clientes = $clienteCtrl->listarClientesControlador();
}
?>

<div class="container">
    <div class="columns">
        <div class="column is-12">

            <!-- Toast Notification -->
            <?php if ($toastMessage): ?>
                <div class="notification is-<?= $toastType === 'success' ? 'success' : 'danger' ?> is-light" id="toast">
                    <button class="delete" onclick="document.getElementById('toast').remove()"></button>
                    <?= $toastMessage ?>
                </div>
                <script>
                    setTimeout(() => {
                        const toast = document.getElementById('toast');
                        if (toast) toast.remove();
                    }, 2000);
                </script>
            <?php endif; ?>

            <?php if ($accion === 'form'): ?>
                <!-- FORMULARIO NUEVO CLIENTE -->
                <div class="box">
                    <h3 class="title is-4 has-text-centered">Nuevo Cliente</h3>
                    <form method="POST" autocomplete="off">
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Tipo de Documento</label>
                                    <div class="control">
                                        <input class="input" type="text" name="tipo_documento" required placeholder="Ej: DNI, Pasaporte">
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Número de Documento</label>
                                    <div class="control">
                                        <input class="input" type="text" name="numero_documento" required placeholder="Número de documento">
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Nombre</label>
                                    <div class="control">
                                        <input class="input" type="text" name="nombre" required placeholder="Nombre del cliente">
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Apellido</label>
                                    <div class="control">
                                        <input class="input" type="text" name="apellido" required placeholder="Apellido del cliente">
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Provincia</label>
                                    <div class="control">
                                        <input class="input" type="text" name="provincia" placeholder="Provincia">
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Ciudad</label>
                                    <div class="control">
                                        <input class="input" type="text" name="ciudad" placeholder="Ciudad">
                                    </div>
                                </div>
                            </div>
                            <div class="column is-12">
                                <div class="field">
                                    <label class="label">Dirección</label>
                                    <div class="control">
                                        <input class="input" type="text" name="direccion" placeholder="Dirección completa">
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Teléfono</label>
                                    <div class="control">
                                        <input class="input" type="text" name="telefono" placeholder="Teléfono">
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Email</label>
                                    <div class="control">
                                        <input class="input" type="email" name="email" placeholder="correo@ejemplo.com">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field mt-4 has-text-centered">
                            <button type="submit" class="button is-info is-rounded">Registrar</button>
                            <a href="<?= APP_URL ?>cliente?accion=lista" class="button is-light is-rounded">Ver Lista</a>
                        </div>
                    </form>
                </div>

            <?php elseif ($accion === 'editar' && isset($clienteEditar)): ?>
                <!-- FORMULARIO EDITAR CLIENTE -->
                <div class="box">
                    <h3 class="title is-4 has-text-centered">Editar Cliente</h3>
                    <form method="POST" autocomplete="off">
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Tipo de Documento</label>
                                    <div class="control">
                                        <input class="input" type="text" name="tipo_documento" value="<?= $clienteEditar['cliente_tipo_documento'] ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Número de Documento</label>
                                    <div class="control">
                                        <input class="input" type="text" name="numero_documento" value="<?= $clienteEditar['cliente_numero_documento'] ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Nombre</label>
                                    <div class="control">
                                        <input class="input" type="text" name="nombre" value="<?= $clienteEditar['cliente_nombre'] ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Apellido</label>
                                    <div class="control">
                                        <input class="input" type="text" name="apellido" value="<?= $clienteEditar['cliente_apellido'] ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Provincia</label>
                                    <div class="control">
                                        <input class="input" type="text" name="provincia" value="<?= $clienteEditar['cliente_provincia'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Ciudad</label>
                                    <div class="control">
                                        <input class="input" type="text" name="ciudad" value="<?= $clienteEditar['cliente_ciudad'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="column is-12">
                                <div class="field">
                                    <label class="label">Dirección</label>
                                    <div class="control">
                                        <input class="input" type="text" name="direccion" value="<?= $clienteEditar['cliente_direccion'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Teléfono</label>
                                    <div class="control">
                                        <input class="input" type="text" name="telefono" value="<?= $clienteEditar['cliente_telefono'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Email</label>
                                    <div class="control">
                                        <input class="input" type="email" name="email" value="<?= $clienteEditar['cliente_email'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field mt-4 has-text-centered">
                            <button type="submit" class="button is-info is-rounded">Actualizar</button>
                            <a href="<?= APP_URL ?>cliente?accion=lista" class="button is-light is-rounded">Cancelar</a>
                        </div>
                    </form>
                </div>

            <?php elseif ($accion === 'lista'): ?>
                <!-- TABLA DE CLIENTES -->
                <div class="box">
                    <div class="level">
                        <div class="level-left">
                            <h3 class="title is-4">Clientes Registrados</h3>
                        </div>
                        <div class="level-right">
                            <a href="<?= APP_URL ?>cliente?accion=form" class="button is-info is-rounded">
                                <i class="fas fa-plus"></i> &nbsp; Nuevo Cliente
                            </a>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="table is-striped is-fullwidth is-hoverable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Documento</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($clientes)): ?>
                                    <?php foreach ($clientes as $cliente): ?>
                                        <tr>
                                            <td><?= $cliente['cliente_id']; ?></td>
                                            <td>
                                                <small class="has-text-grey"><?= $cliente['cliente_tipo_documento'] ?></small><br>
                                                <?= $cliente['cliente_numero_documento']; ?>
                                            </td>
                                            <td><?= $cliente['cliente_nombre']; ?></td>
                                            <td><?= $cliente['cliente_apellido']; ?></td>
                                            <td><?= $cliente['cliente_telefono']; ?></td>
                                            <td><?= $cliente['cliente_email']; ?></td>
                                            <td>
                                                <a href="<?= APP_URL ?>cliente?accion=editar&id=<?= $cliente['cliente_id'] ?>">Editar</a> |
                                                </a>
                                                <a href="<?= APP_URL ?>cliente?accion=eliminar&id=<?= $cliente['cliente_id'] ?>">Eliminar
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="has-text-centered">No hay clientes registrados</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>