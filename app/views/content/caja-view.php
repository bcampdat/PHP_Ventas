<?php
use app\controllers\cajaController;

$cajaCtrl = new cajaController();

$cajaEditar = null;
if (isset($_GET['editar'])) {
    $cajaEditar = $cajaCtrl->obtenerCajaControlador((int)$_GET['editar']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {
        $cajaCtrl->crearCajaControlador();
    } elseif (isset($_POST['accion']) && $_POST['accion'] == 'editar') {
        $cajaCtrl->actualizarCajaControlador((int)$_POST['id']);
    }
}

if (isset($_GET['eliminar'])) {
    $cajaCtrl->eliminarCajaControlador($_GET['eliminar']);
}

$cajas = $cajaCtrl->listarCajasControlador();
?>

<div class="container">
    <h2 class="title">Gestión de Cajas</h2>

    <table class="table is-fullwidth">
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
            <?php foreach ($cajas as $caja): ?>
            <tr>
                <td><?= $caja['caja_id'] ?></td>
                <td><?= $caja['caja_numero'] ?></td>
                <td><?= $caja['caja_nombre'] ?></td>
                <td><?= number_format($caja['caja_efectivo'], 2) ?></td>
                <td>
                    <a href="?views=caja&editar=<?= $caja['caja_id'] ?>">Editar</a> |
                    <a href="?views=caja&eliminar=<?= $caja['caja_id'] ?>" onclick="return confirm('¿Eliminar caja?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <h3><?= isset($_GET['editar']) ? 'Editar Caja' : 'Nueva Caja' ?></h3>

    <form method="POST">
        <!-- Acción: 'crear' o 'editar' -->
        <input type="hidden" name="accion" value="<?= isset($_GET['editar']) ? 'editar' : 'crear' ?>">
        <input type="hidden" name="id" value="<?= $cajaEditar['caja_id'] ?? '' ?>">

        <div><label>Número:</label><input type="number" name="numero" required value="<?= $cajaEditar['caja_numero'] ?? '' ?>"></div>
        <div><label>Nombre:</label><input type="text" name="nombre" required value="<?= $cajaEditar['caja_nombre'] ?? '' ?>"></div>
        <div><label>Efectivo:</label><input type="number" step="0.01" name="efectivo" required value="<?= $cajaEditar['caja_efectivo'] ?? '0.00' ?>"></div>

        <button type="submit">Guardar Caja</button>
    </form>
</div>
