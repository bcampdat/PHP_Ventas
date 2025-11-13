<?php
use app\controllers\productoController;

$productoCtrl = new productoController();

$productoEditar = null;
if (isset($_GET['editar'])) {
    $productoEditar = $productoCtrl->obtenerProductoControlador((int)$_GET['editar']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {
        $productoCtrl->crearProductoControlador();
    } elseif (isset($_POST['accion']) && $_POST['accion'] == 'editar') {
        $productoCtrl->actualizarProductoControlador();
    }
}

if (isset($_GET['eliminar'])) {
    $productoCtrl->eliminarProductoControlador($_GET['eliminar']);
}

$productos = $productoCtrl->listarProductos();
?>

<div class="container">
    <h2 class="title">Gestión de Productos</h2>

    <table class="table is-fullwidth">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Stock</th>
                <th>Precio Venta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $prod): ?>
            <tr>
                <td><?= $prod['producto_id'] ?></td>
                <td><?= $prod['producto_codigo'] ?></td>
                <td><?= $prod['producto_nombre'] ?></td>
                <td><?= $prod['producto_stock_total'] ?></td>
                <td><?= $prod['producto_precio_venta'] ?></td>
                <td>
                    <a href="?views=producto&editar=<?= $prod['producto_id'] ?>">Editar</a> |
                    <a href="?views=producto&eliminar=<?= $prod['producto_id'] ?>" onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <h3><?= isset($_GET['editar']) ? 'Editar Producto' : 'Nuevo Producto' ?></h3>

    <form method="POST" enctype="multipart/form-data">
        <!-- Accion: 'crear' o 'editar' -->
        <input type="hidden" name="accion" value="<?= isset($_GET['editar']) ? 'editar' : 'crear' ?>">
        <input type="hidden" name="id" value="<?= $productoEditar['producto_id'] ?? '' ?>">

        <div><label>Código:</label><input type="text" name="codigo" value="<?= $productoEditar['producto_codigo'] ?? '' ?>"></div>
        <div><label>Nombre:</label><input type="text" name="nombre" required value="<?= $productoEditar['producto_nombre'] ?? '' ?>"></div>
        <div><label>Stock total:</label><input type="number" name="stock" required value="<?= $productoEditar['producto_stock_total'] ?? '' ?>"></div>
        <div><label>Unidad:</label><input type="text" name="unidad" value="<?= $productoEditar['producto_tipo_unidad'] ?? 'Unidad' ?>"></div>
        <div><label>Precio compra:</label><input type="number" step="0.01" name="precio_compra" value="<?= $productoEditar['producto_precio_compra'] ?? '' ?>"></div>
        <div><label>Precio venta:</label><input type="number" step="0.01" name="precio_venta" required value="<?= $productoEditar['producto_precio_venta'] ?? '' ?>"></div>
        <div><label>Marca:</label><input type="text" name="marca" value="<?= $productoEditar['producto_marca'] ?? '' ?>"></div>
        <div><label>Modelo:</label><input type="text" name="modelo" value="<?= $productoEditar['producto_modelo'] ?? '' ?>"></div>
        <div>
            <label>Estado:</label>
            <select name="estado">
                <option value="Activo" <?= (isset($productoEditar) && $productoEditar['producto_estado']=='Activo') ? 'selected' : '' ?>>Activo</option>
                <option value="Inactivo" <?= (isset($productoEditar) && $productoEditar['producto_estado']=='Inactivo') ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>
        <div><label>Categoría:</label><input type="number" name="categoria" value="<?= $productoEditar['categoria_id'] ?? 1 ?>"></div>

        <button type="submit">Guardar producto</button>
    </form>
</div>
