<?php
use app\controllers\categoriaController;

$categoriaCtrl = new categoriaController();

$categoriaEditar = null;
if (isset($_GET['editar'])) {
    $categoriaEditar = $categoriaCtrl->obtenerCategoriaControlador((int)$_GET['editar']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {
        $categoriaCtrl->crearCategoriaControlador();
    } elseif (isset($_POST['accion']) && $_POST['accion'] == 'editar') {
        $categoriaCtrl->actualizarCategoriaControlador((int)$_POST['id']);
    }
}

if (isset($_GET['eliminar'])) {
    $categoriaCtrl->eliminarCategoriaControlador($_GET['eliminar']);
}

$categorias = $categoriaCtrl->listarCategoriasControlador();
?>

<div class="container">
    <h2 class="title">Gestión de Categorías</h2>

    <table class="table is-fullwidth">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $cat): ?>
            <tr>
                <td><?= $cat['categoria_id'] ?></td>
                <td><?= $cat['categoria_nombre'] ?></td>
                <td><?= $cat['categoria_ubicacion'] ?></td>
                <td>
                    <a href="?views=categoria&editar=<?= $cat['categoria_id'] ?>">Editar</a> |
                    <a href="?views=categoria&eliminar=<?= $cat['categoria_id'] ?>" onclick="return confirm('¿Eliminar categoría?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <h3><?= isset($_GET['editar']) ? 'Editar Categoría' : 'Nueva Categoría' ?></h3>

    <form method="POST">
        <!-- Acción: 'crear' o 'editar' -->
        <input type="hidden" name="accion" value="<?= isset($_GET['editar']) ? 'editar' : 'crear' ?>">
        <input type="hidden" name="id" value="<?= $categoriaEditar['categoria_id'] ?? '' ?>">

        <div><label>Nombre:</label><input type="text" name="nombre" required value="<?= $categoriaEditar['categoria_nombre'] ?? '' ?>"></div>
        <div><label>Ubicación:</label><input type="text" name="ubicacion" required value="<?= $categoriaEditar['categoria_ubicacion'] ?? '' ?>"></div>

        <button type="submit">Guardar categoría</button>
    </form>
</div>
