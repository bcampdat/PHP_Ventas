<?php
use app\controllers\clienteController;

$clienteCtrl = new clienteController();

$clienteEditar = null;
if (isset($_GET['editar'])) {
    $clienteEditar = $clienteCtrl->obtenerClienteControlador((int)$_GET['editar']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {
        $clienteCtrl->crearClienteControlador();
    } elseif (isset($_POST['accion']) && $_POST['accion'] == 'editar') {
        $clienteCtrl->actualizarClienteControlador((int)$_POST['id']);
    }
}

if (isset($_GET['eliminar'])) {
    $clienteCtrl->eliminarClienteControlador($_GET['eliminar']);
}

$clientes = $clienteCtrl->listarClientesControlador();
?>

<div class="container">
    <h2 class="title">Gestión de Clientes</h2>

    <!-- Mostrar la lista de clientes -->
    <table class="table is-fullwidth">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo de Documento</th>
                <th>Número de Documento</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Provincia</th>
                <th>Ciudad</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?= $cliente['cliente_id'] ?></td>
                <td><?= $cliente['cliente_tipo_documento'] ?></td>
                <td><?= $cliente['cliente_numero_documento'] ?></td>
                <td><?= $cliente['cliente_nombre'] ?></td>
                <td><?= $cliente['cliente_apellido'] ?></td>
                <td><?= $cliente['cliente_provincia'] ?></td>
                <td><?= $cliente['cliente_ciudad'] ?></td>
                <td><?= $cliente['cliente_direccion'] ?></td>
                <td><?= $cliente['cliente_telefono'] ?></td>
                <td><?= $cliente['cliente_email'] ?></td>
                <td>
                    <a href="?views=clientes&editar=<?= $cliente['cliente_id'] ?>">Editar</a> |
                    <a href="?views=clientes&eliminar=<?= $cliente['cliente_id'] ?>" onclick="return confirm('¿Eliminar cliente?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>


    <h3><?= isset($_GET['editar']) ? 'Editar Cliente' : 'Nuevo Cliente' ?></h3>

    <form method="POST">
        <input type="hidden" name="accion" value="<?= isset($_GET['editar']) ? 'editar' : 'crear' ?>">
        <input type="hidden" name="id" value="<?= $clienteEditar['cliente_id'] ?? '' ?>">

        <div><label>Tipo de Documento:</label><input type="text" name="tipo_documento" required value="<?= $clienteEditar['cliente_tipo_documento'] ?? '' ?>"></div>
        <div><label>Número de Documento:</label><input type="text" name="numero_documento" required value="<?= $clienteEditar['cliente_numero_documento'] ?? '' ?>"></div>
        <div><label>Nombre:</label><input type="text" name="nombre" required value="<?= $clienteEditar['cliente_nombre'] ?? '' ?>"></div>
        <div><label>Apellido:</label><input type="text" name="apellido" required value="<?= $clienteEditar['cliente_apellido'] ?? '' ?>"></div>
        <div><label>Provincia:</label><input type="text" name="provincia" value="<?= $clienteEditar['cliente_provincia'] ?? '' ?>"></div>
        <div><label>Ciudad:</label><input type="text" name="ciudad" value="<?= $clienteEditar['cliente_ciudad'] ?? '' ?>"></div>
        <div><label>Dirección:</label><input type="text" name="direccion" value="<?= $clienteEditar['cliente_direccion'] ?? '' ?>"></div>
        <div><label>Teléfono:</label><input type="text" name="telefono" value="<?= $clienteEditar['cliente_telefono'] ?? '' ?>"></div>
        <div><label>Email:</label><input type="email" name="email" value="<?= $clienteEditar['cliente_email'] ?? '' ?>"></div>

        <button type="submit">Guardar Cliente</button>
    </form>
</div>
