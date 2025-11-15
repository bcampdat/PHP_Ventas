<?php
use app\controllers\productoController;
use app\controllers\categoriaController;

$productoCtrl = new productoController();
$categoriaCtrl = new categoriaController();

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: ' . APP_URL . 'producto?accion=lista');
    exit;
}

$producto = $productoCtrl->obtenerProductoControlador($id);
if (!$producto) {
    header('Location: ' . APP_URL . 'producto?accion=lista');
    exit;
}

$categorias = $categoriaCtrl->listarCategoriasControlador();
$categoriaNombre = 'Sin categoría';
foreach ($categorias as $cat) {
    if ($cat['categoria_id'] == $producto['categoria_id']) {
        $categoriaNombre = $cat['categoria_nombre'];
        break;
    }
}

// Calcular margen
$margen = 0;
if ($producto['producto_precio_compra'] > 0) {
    $margen = (($producto['producto_precio_venta'] - $producto['producto_precio_compra']) / $producto['producto_precio_compra']) * 100;
}
?>

<div class="container">
    <div class="columns">
        <div class="column is-12">
            <div class="level">
                <div class="level-left">
                    <h3 class="title is-4">Detalle Producto</h3>
                </div>
                <div class="level-right">
                    <a href="<?= APP_URL ?>producto?accion=lista" class="button is-light is-rounded">Volver</a>
                    <a href="<?= APP_URL ?>producto?accion=editar&id=<?= $producto['producto_id'] ?>" class="button is-info is-rounded">Editar</a>
                </div>
            </div>

            <div class="box">
                <div class="columns">
                    <div class="column is-4">
                        <div class="has-text-centered">
                            <img src="<?= APP_URL ?>app/views/productos/<?= $producto['producto_foto'] ?>" 
                                 alt="<?= $producto['producto_nombre'] ?>"
                                 style="max-width: 250px; border: 1px solid #ddd; border-radius: 8px; padding: 10px;"
                                 onerror="this.src='<?= APP_URL ?>app/views/productos/default.png'">
                        </div>
                    </div>
                    <div class="column is-8">
                        <h4 class="title is-3 mb-4"><?= $producto['producto_nombre'] ?></h4>
                        
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label has-text-weight-semibold">Código</label>
                                    <div class="control">
                                        <div class="is-size-5"><?= $producto['producto_codigo'] ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label has-text-weight-semibold">Estado</label>
                                    <div class="control">
                                        <span class="tag is-<?= $producto['producto_estado'] === 'Activo' ? 'success' : 'danger' ?> is-medium">
                                            <?= $producto['producto_estado'] ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label has-text-weight-semibold">Marca</label>
                                    <div class="control">
                                        <div class="is-size-6"><?= $producto['producto_marca'] ?: 'No especificada' ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label has-text-weight-semibold">Modelo</label>
                                    <div class="control">
                                        <div class="is-size-6"><?= $producto['producto_modelo'] ?: 'No especificado' ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label has-text-weight-semibold">Categoría</label>
                                    <div class="control">
                                        <span class="tag is-info is-light"><?= $categoriaNombre ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label has-text-weight-semibold">Stock</label>
                                    <div class="control">
                                        <div class="is-size-5 has-text-info"><?= $producto['producto_stock_total'] ?> 
                                            <span class="is-size-6 has-text-grey"><?= $producto['producto_tipo_unidad'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <div class="has-text-centered">
                                    <label class="label has-text-weight-semibold has-text-grey">Precio Compra</label>
                                    <div class="is-size-5"><?= number_format($producto['producto_precio_compra'], 2) ?> €</div>
                                </div>
                            </div>
                            
                            <div class="column is-4">
                                <div class="has-text-centered">
                                    <label class="label has-text-weight-semibold has-text-grey">Precio Venta</label>
                                    <div class="is-size-4 has-text-success has-text-weight-bold"><?= number_format($producto['producto_precio_venta'], 2) ?> €</div>
                                </div>
                            </div>
                            
                            <div class="column is-4">
                                <div class="has-text-centered">
                                    <label class="label has-text-weight-semibold has-text-grey">Margen</label>
                                    <div class="is-size-5 has-text-<?= $margen >= 0 ? 'info' : 'danger' ?> has-text-weight-bold">
                                        <?= number_format($margen, 2) ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>