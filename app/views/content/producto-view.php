<?php

use app\controllers\productoController;
use app\controllers\categoriaController;

$productoCtrl = new productoController();
$categoriaCtrl = new categoriaController();

$accion = $_GET['accion'] ?? 'lista';
$categoria_view = $_GET['categoria'] ?? false;
$id = $_GET['id'] ?? null;

// Procesos básicos
if ($accion === 'eliminar' && $id) {
    $productoCtrl->eliminarProductoControlador($id);
    $accion = 'lista';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($accion === 'form') {
        $datos = [
            'codigo' => $_POST['codigo'] ?? uniqid('P-'),
            'nombre' => $_POST['nombre'] ?? '',
            'stock' => $_POST['stock'] ?? 0,
            'unidad' => $_POST['unidad'] ?? 'Unidad',
            'precio_compra' => $_POST['precio_compra'] ?? 0,
            'precio_venta' => $_POST['precio_venta'] ?? 0,
            'marca' => $_POST['marca'] ?? '',
            'modelo' => $_POST['modelo'] ?? '',
            'estado' => $_POST['estado'] ?? 'Activo',
            'categoria' => $_POST['categoria'] ?? 1,
            'empresa' => $_SESSION['empresa_id'] ?? 1
        ];
        $productoCtrl->crearProductoControlador($datos);
        $accion = 'lista';
    }
    if ($accion === 'editar' && $id) {
        $datos = [
            'codigo' => $_POST['codigo'],
            'nombre' => $_POST['nombre'],
            'stock' => $_POST['stock'],
            'unidad' => $_POST['unidad'],
            'precio_compra' => $_POST['precio_compra'],
            'precio_venta' => $_POST['precio_venta'],
            'marca' => $_POST['marca'],
            'modelo' => $_POST['modelo'],
            'estado' => $_POST['estado'],
            'categoria' => $_POST['categoria'],
            'empresa' => $_SESSION['empresa_id'] ?? 1
        ];
        $productoCtrl->actualizarProductoControlador($id, $datos);
        $accion = 'lista';
    }
}

// Datos
if ($accion === 'editar' && $id) $productoEditar = $productoCtrl->obtenerProductoControlador($id);
$productos = $productoCtrl->listarProductos();
if ($categoria_view) $categorias = $categoriaCtrl->listarCategoriasControlador();
?>

<div class="container">
    <div class="columns">
        <div class="column is-12">

            <!-- Navegación -->
            <div class="level mb-4">
                <div class="level-left">
                    <h3 class="title is-4">
                        <?= $categoria_view ? 'Productos por Categoría' : ($accion === 'form' ? 'Nuevo Producto' : ($accion === 'editar' ? 'Editar Producto' : 'Productos')) ?>
                    </h3>
                </div>
                <div class="level-right">
                    <a href="<?= APP_URL ?>producto?accion=form" class="button is-info is-rounded">Nuevo</a>
                    <a href="<?= APP_URL ?>producto?accion=lista" class="button is-light is-rounded">Lista</a>
                    <a href="<?= APP_URL ?>producto?categoria=true" class="button is-light is-rounded">Por Categoría</a>
                </div>
            </div>

            <?php if ($accion === 'form' || $accion === 'editar'): ?>
                <!-- Formulario -->
                <div class="box">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Código</label>
                                    <input class="input" type="text" name="codigo" value="<?= $productoEditar['producto_codigo'] ?? uniqid('P-') ?>">
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Nombre *</label>
                                    <input class="input" type="text" name="nombre" required value="<?= $productoEditar['producto_nombre'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="column is-12">
                                <div class="field">
                                    <label class="label">Imagen del Producto</label>

                                    <?php if (isset($productoEditar) && $productoEditar['producto_foto'] !== 'default.png'): ?>
                                        <!-- Mostrar imagen actual si existe -->
                                        <div class="mb-3">
                                            <p class="help">Imagen actual:</p>
                                            <img src="<?= APP_URL ?>app/views/productos/<?= $productoEditar['producto_foto'] ?>"
                                                alt="<?= $productoEditar['producto_nombre'] ?>"
                                                style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                                            <p class="help"><?= $productoEditar['producto_foto'] ?></p>
                                        </div>
                                        <input type="hidden" name="foto_actual" value="<?= $productoEditar['producto_foto'] ?>">
                                    <?php else: ?>
                                        <!-- Mostrar imagen por defecto si es nuevo o tiene default.png -->
                                        <div class="mb-3">
                                            <p class="help">Imagen por defecto:</p>
                                            <img src="<?= APP_URL ?>app/views/productos/default.png"
                                                alt="Imagen por defecto"
                                                style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                                        </div>
                                    <?php endif; ?>

                                    <div class="control">
                                        <input class="input" type="file" name="foto" accept="image/*">
                                    </div>
                                    <p class="help">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</p>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Stock *</label>
                                    <input class="input" type="number" name="stock" required value="<?= $productoEditar['producto_stock_total'] ?? 0 ?>">
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Unidad</label>
                                    <div class="select is-fullwidth">
                                        <select name="unidad">
                                            <?php foreach (PRODUCTO_UNIDAD as $unidad): ?>
                                                <option value="<?= $unidad ?>"
                                                    <?= (isset($productoEditar) && $productoEditar['producto_tipo_unidad'] == $unidad) ? 'selected' : '' ?>>
                                                    <?= $unidad ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Precio Compra</label>
                                    <input class="input" type="number" step="0.01" name="precio_compra" value="<?= $productoEditar['producto_precio_compra'] ?? 0 ?>">
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Precio Venta *</label>
                                    <input class="input" type="number" step="0.01" name="precio_venta" required value="<?= $productoEditar['producto_precio_venta'] ?? 0 ?>">
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Marca</label>
                                    <input class="input" type="text" name="marca" value="<?= $productoEditar['producto_marca'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Modelo</label>
                                    <input class="input" type="text" name="modelo" value="<?= $productoEditar['producto_modelo'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Estado</label>
                                    <div class="select is-fullwidth">
                                        <select name="estado">
                                            <option value="Activo" <?= (isset($productoEditar) && $productoEditar['producto_estado'] == 'Activo') ? 'selected' : '' ?>>Activo</option>
                                            <option value="Inactivo" <?= (isset($productoEditar) && $productoEditar['producto_estado'] == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Categoría ID</label>
                                    <input class="input" type="number" name="categoria" value="<?= $productoEditar['categoria_id'] ?? 1 ?>">
                                </div>
                            </div>
                        </div>

                        <div class="field has-text-centered mt-4">
                            <button type="submit" class="button is-info is-rounded">Guardar</button>
                            <a href="<?= APP_URL ?>producto?accion=lista" class="button is-light is-rounded">Cancelar</a>
                        </div>
                    </form>
                </div>

            <?php elseif ($categoria_view): ?>
                <!-- Por categoría -->
                <div class="box">
                    <?php foreach ($categorias as $cat): ?>
                        <?php $productosCategoria = array_filter($productos, fn($p) => $p['categoria_id'] == $cat['categoria_id']); ?>
                        <?php if ($productosCategoria): ?>
                            <div class="mb-4">
                                <h4 class="title is-5 has-background-light p-3">
                                    <?= $cat['categoria_nombre'] ?>
                                    <span class="tag is-info"><?= count($productosCategoria) ?></span>
                                </h4>
                                <table class="table is-striped is-fullwidth">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Stock</th>
                                            <th>P. Venta</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($productosCategoria as $p): ?>
                                            <tr>
                                                <td><?= $p['producto_codigo'] ?></td>
                                                <td><?= $p['producto_nombre'] ?></td>
                                                <td><?= $p['producto_stock_total'] ?></td>
                                                <td><?= number_format($p['producto_precio_venta'], MONEDA_DECIMALES, SEPARADOR_DECIMAL, SEPARADOR_MILLAR) ?>&nbsp;<?= MONEDA_SIMBOLO ?></td>
                                                <td><span class="tag is-<?= $p['producto_estado'] === 'Activo' ? 'success' : 'danger' ?>"><?= $p['producto_estado'] ?></span></td>
                                                <td>
                                                    <a href="<?= APP_URL ?>detallePdto?accion=ver&id=<?= $p['producto_id'] ?>">Ver</a> |
                                                    <a href="<?= APP_URL ?>producto?accion=editar&id=<?= $p['producto_id'] ?>">Editar</a> |
                                                    <a href="<?= APP_URL ?>producto?accion=eliminar&id=<?= $p['producto_id'] ?>">Eliminar</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <!-- Lista normal -->
                <div class="box">
                    <table class="table is-striped is-fullwidth">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Stock</th>
                                <th>P. Venta</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $p): ?>
                                <tr>
                                    <td><strong><?= $p['producto_codigo'] ?></strong></td>
                                    <td>
                                        <div><strong><?= $p['producto_nombre'] ?></strong></div>
                                        <small class="has-text-grey"><?= $p['producto_marca'] ?> - <?= $p['producto_modelo'] ?></small>
                                    </td>
                                    <td>
                                        <div><?= $p['producto_stock_total'] ?></div>
                                        <small class="has-text-grey"><?= $p['producto_tipo_unidad'] ?></small>
                                    </td>
                                    <td>
                                        <div><strong> <?= number_format($p['producto_precio_venta'], MONEDA_DECIMALES, SEPARADOR_DECIMAL, SEPARADOR_MILLAR) ?>&nbsp;<?= MONEDA_SIMBOLO ?></strong></div>
                                        <small class="has-text-grey">Compra: <?= number_format($p['producto_precio_compra'], MONEDA_DECIMALES, SEPARADOR_DECIMAL, SEPARADOR_MILLAR) ?>&nbsp;<?= MONEDA_SIMBOLO ?></small>
                                    </td>
                                    <td><span class="tag is-<?= $p['producto_estado'] === 'Activo' ? 'success' : 'danger' ?>"><?= $p['producto_estado'] ?></span></td>
                                    <td>
                                        <a href="<?= APP_URL ?>detallePdto?accion=ver&id=<?= $p['producto_id'] ?>">Ver</a> |
                                        <a href="<?= APP_URL ?>producto?accion=editar&id=<?= $p['producto_id'] ?>">Editar</a> |
                                        <a href="<?= APP_URL ?>producto?accion=eliminar&id=<?= $p['producto_id'] ?>">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>