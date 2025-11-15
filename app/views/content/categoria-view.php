<?php
use app\controllers\categoriaController;

$categoriaCtrl = new categoriaController();

// Determinar la acción actual
$accion = $_GET['accion'] ?? 'form';
$id = $_GET['id'] ?? null;

// Variable para mensajes toast
$toastMessage = '';
$toastType = '';

// Procesar eliminación
if($accion === 'eliminar' && $id){
    if($categoriaCtrl->eliminarCategoriaControlador($id)) {
        $toastMessage = 'Categoría eliminada correctamente';
        $toastType = 'success';
    } else {
        $toastMessage = 'Error al eliminar la categoría';
        $toastType = 'error';
    }
    $accion = 'lista'; // Mostrar lista después de eliminar
}

// Procesar POST de formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($accion === 'form'){ // Nueva categoría
        $datos = [
            "nombre" => $_POST['nombre'] ?? '',
            "ubicacion" => $_POST['ubicacion'] ?? ''
        ];
        
        if($categoriaCtrl->crearCategoriaControlador($datos)) {
            $toastMessage = 'Categoría creada correctamente';
            $toastType = 'success';
            $accion = 'lista'; 
        } else {
            $toastMessage = 'Error al crear la categoría';
            $toastType = 'error';
        }
    } elseif($accion === 'editar' && $id){ // Editar categoría
        $datos = [
            "nombre" => $_POST['nombre'],
            "ubicacion" => $_POST['ubicacion']
        ];
        
        if($categoriaCtrl->actualizarCategoriaControlador($id, $datos)) {
            $toastMessage = 'Categoría actualizada correctamente';
            $toastType = 'success';
            $accion = 'lista'; 
        } else {
            $toastMessage = 'Error al actualizar la categoría';
            $toastType = 'error';
        }
    }
}

// Obtener datos para editar
if($accion === 'editar' && $id){
    $categoriaEditar = $categoriaCtrl->obtenerCategoriaControlador($id);
}

if($accion === 'lista'){
    $categorias = $categoriaCtrl->listarCategoriasControlador();
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
                <!-- FORMULARIO NUEVA CATEGORÍA -->
                <div class="box">
                    <h3 class="title is-4 has-text-centered">Nueva Categoría</h3>
                    <form method="POST" autocomplete="off">
                        <div class="field">
                            <label class="label">Nombre</label>
                            <div class="control">
                                <input class="input" type="text" name="nombre" required placeholder="nombre de la categoría">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Ubicación</label>
                            <div class="control">
                                <input class="input" type="text" name="ubicacion" required placeholder="ubicación">
                            </div>
                        </div>

                        <div class="field mt-4 has-text-centered">
                            <button type="submit" class="button is-info is-rounded">Registrar</button>
                            <a href="<?= APP_URL ?>categoria?accion=lista" class="button is-light is-rounded">Ver Lista</a>
                        </div>
                    </form>
                </div>

            <?php elseif($accion === 'editar' && isset($categoriaEditar)): ?>
                <!-- FORMULARIO EDITAR CATEGORÍA -->
                <div class="box">
                    <h3 class="title is-4 has-text-centered">Editar Categoría</h3>
                    <form method="POST" autocomplete="off">
                        <div class="field">
                            <label class="label">Nombre</label>
                            <div class="control">
                                <input class="input" type="text" name="nombre" value="<?= $categoriaEditar['categoria_nombre'] ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Ubicación</label>
                            <div class="control">
                                <input class="input" type="text" name="ubicacion" value="<?= $categoriaEditar['categoria_ubicacion'] ?>" required>
                            </div>
                        </div>

                        <div class="field mt-4 has-text-centered">
                            <button type="submit" class="button is-info is-rounded">Actualizar</button>
                            <a href="<?= APP_URL ?>categoria?accion=lista" class="button is-light is-rounded">Cancelar</a>
                        </div>
                    </form>
                </div>

            <?php elseif($accion === 'lista'): ?>
                <!-- TABLA DE CATEGORÍAS -->
                <div class="box">
                    <div class="level">
                        <div class="level-left">
                            <h3 class="title is-4">Categorías Registradas</h3>
                        </div>
                        <div class="level-right">
                            <a href="<?= APP_URL ?>categoria?accion=form" class="button is-info is-rounded">
                                <i class="fas fa-plus"></i> &nbsp; Nueva Categoría
                            </a>
                        </div>
                    </div>
                    
                    <table class="table is-striped is-fullwidth">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Ubicación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($categorias)): ?>
                                <?php foreach($categorias as $cat): ?>
                                    <tr>
                                        <td><?= $cat['categoria_id']; ?></td>
                                        <td><?= $cat['categoria_nombre']; ?></td>
                                        <td><?= $cat['categoria_ubicacion']; ?></td>
                                        <td>
                                            <a href="<?= APP_URL ?>categoria?accion=editar&id=<?= $cat['categoria_id'] ?>">Editar</a> |
                                            </a>
                                            <a href="<?= APP_URL ?>categoria?accion=eliminar&id=<?= $cat['categoria_id'] ?>">Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="has-text-centered">No hay categorías registradas</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>