<?php
use app\controllers\ventaController;

$ventaCtrl = new ventaController();

// Obtener datos de la venta a editar, si existe
$ventaEditar = null;
if (isset($_GET['editar'])) {
    $ventaEditar = $ventaCtrl->obtenerVentaControlador((int)$_GET['editar']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {
        $ventaCtrl->crearVentaControlador();
    } elseif (isset($_POST['accion']) && $_POST['accion'] == 'editar') {
        $ventaCtrl->actualizarVentaControlador((int)$_POST['id']);
    }
}

if (isset($_GET['eliminar'])) {
    $ventaCtrl->eliminarVentaControlador($_GET['eliminar']);
}

// Listar todas las ventas
$ventas = $ventaCtrl->listarVentasControlador();
?>

<div class="container">
    <h2 class="title">Gestión de Ventas</h2>

    <table class="table is-fullwidth">
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
            <?php foreach ($ventas as $venta): ?>
            <tr>
                <td><?= $venta['venta_id'] ?></td>
                <td><?= $venta['venta_codigo'] ?></td>
                <td><?= $venta['venta_fecha'] ?></td>
                <td><?= $venta['venta_hora'] ?></td>
                <td><?= $venta['venta_total'] ?></td>
                <td><?= $venta['venta_pagado'] ?></td>
                <td><?= $venta['venta_cambio'] ?></td>
                <td>
                    <a href="?views=venta&editar=<?= $venta['venta_id'] ?>">Editar</a> |
                    <a href="?views=venta&eliminar=<?= $venta['venta_id'] ?>" onclick="return confirm('¿Eliminar venta?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <h3><?= isset($_GET['editar']) ? 'Editar Venta' : 'Nueva Venta' ?></h3>

    <form method="POST">
        <input type="hidden" name="accion" value="<?= isset($_GET['editar']) ? 'editar' : 'crear' ?>">
        <input type="hidden" name="id" value="<?= $ventaEditar['venta_id'] ?? '' ?>">

        <div><label>Código:</label><input type="text" name="codigo" required value="<?= $ventaEditar['venta_codigo'] ?? '' ?>"></div>
        <div><label>Fecha:</label><input type="date" name="fecha" required value="<?= $ventaEditar['venta_fecha'] ?? date('Y-m-d') ?>"></div>
        <div><label>Hora:</label><input type="time" name="hora" required value="<?= $ventaEditar['venta_hora'] ?? date('H:i') ?>"></div>
        <div><label>Total:</label><input type="number" name="total" step="0.01" required value="<?= $ventaEditar['venta_total'] ?? '' ?>"></div>
        <div><label>Pagado:</label><input type="number" name="pagado" step="0.01" required value="<?= $ventaEditar['venta_pagado'] ?? '' ?>"></div>
        <div><label>Cambio:</label><input type="number" name="cambio" step="0.01" required value="<?= $ventaEditar['venta_cambio'] ?? '' ?>"></div>

        <div><label>Cliente ID:</label><input type="number" name="cliente_id" required value="<?= $ventaEditar['cliente_id'] ?? '' ?>"></div>
        <div><label>Caja ID:</label><input type="number" name="caja_id" required value="<?= $ventaEditar['caja_id'] ?? '' ?>"></div>
        <div><label>Empresa ID:</label><input type="number" name="empresa_id" required value="<?= $ventaEditar['empresa_id'] ?? '' ?>"></div>

        <button type="submit">Guardar venta</button>
    </form>
</div>
