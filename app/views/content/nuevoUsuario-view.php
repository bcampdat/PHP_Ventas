<?php
use app\controllers\usuarioController;
use app\controllers\empresaController;

$usuarioController = new usuarioController();
$empresaController = new empresaController();
$empresas = $empresaController->listarEmpresasControlador();

// Acción a ejecutar
$accion = $_GET['accion'] ?? 'form';
$id = $_GET['id'] ?? null;

// Variable para mensajes toast
$toastMessage = '';
$toastType = '';

// Procesar eliminación
if($accion === 'eliminar' && $id){
    if($usuarioController->eliminarUsuario($id)) {
        $toastMessage = 'Usuario eliminado correctamente';
        $toastType = 'success';
    } else {
        $toastMessage = 'Error al eliminar el usuario';
        $toastType = 'error';
    }
    $accion = 'lista';
}

// Procesar POST de formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($accion === 'form'){ // Nuevo usuario
        $datos = [
            "nombre"   => $_POST['nombre'] ?? '',
            "apellido" => $_POST['apellido'] ?? '',
            "email"    => $_POST['email'] ?? '',
            "usuario"  => $_POST['usuario'] ?? '',
            "clave"    => password_hash($_POST['clave'] ?? '', PASSWORD_BCRYPT),
            "cargo"    => $_POST['cargo'] ?? 'Empleado',
            "caja"     => $_POST['caja'] ?? 1,
            "empresa"  => $_POST['empresa'] ?? 1
        ];
        
        if($usuarioController->registrarUsuario($datos)) {
            $toastMessage = 'Usuario registrado correctamente';
            $toastType = 'success';
            $accion = 'lista';
        } else {
            $toastMessage = 'Error al registrar el usuario';
            $toastType = 'error';
        }
    } elseif($accion === 'editar' && $id){ // Editar usuario
        $datos = [
            "nombre"   => $_POST['nombre'],
            "apellido" => $_POST['apellido'],
            "email"    => $_POST['email'],
            "usuario"  => $_POST['usuario'],
            "cargo"    => $_POST['cargo'],
            "caja"     => $_POST['caja']
        ];
        
        if($usuarioController->actualizarUsuario($id, $datos)) {
            $toastMessage = 'Usuario actualizado correctamente';
            $toastType = 'success';
            $accion = 'lista';
        } else {
            $toastMessage = 'Error al actualizar el usuario';
            $toastType = 'error';
        }
    }
}

// Obtener datos para editar
if($accion === 'editar' && $id){
    $usuarioEditar = $usuarioController->obtenerUsuario($id);
}

if($accion === 'lista'){
    $usuarios = $usuarioController->listarUsuarios();
}
?>

<div class="container">
    <div class="columns">
        <div class="column is-10 is-offset-1">

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
                <!-- FORMULARIO NUEVO USUARIO -->
                <div class="box">
                    <h3 class="title is-4 has-text-centered">Nuevo Usuario</h3>
                    <form method="POST" autocomplete="off">
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Nombre</label>
                                    <div class="control">
                                        <input class="input" type="text" name="nombre" required placeholder="Nombre del usuario">
                                    </div>
                                </div>
                            </div>

                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Apellido</label>
                                    <div class="control">
                                        <input class="input" type="text" name="apellido" required placeholder="Apellido del usuario">
                                    </div>
                                </div>
                            </div>

                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Email</label>
                                    <div class="control">
                                        <input class="input" type="email" name="email" required placeholder="correo@ejemplo.com">
                                    </div>
                                </div>
                            </div>

                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Usuario</label>
                                    <div class="control">
                                        <input class="input" type="text" name="usuario" required placeholder="Nombre de usuario">
                                    </div>
                                </div>
                            </div>

                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Contraseña</label>
                                    <div class="control">
                                        <input class="input" type="password" name="clave" required placeholder="Contraseña segura">
                                    </div>
                                </div>
                            </div>

                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Cargo</label>
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select name="cargo" class="input">
                                                <option value="Empleado">Empleado</option>
                                                <option value="Administrador">Administrador</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Caja</label>
                                    <div class="control">
                                        <input class="input" type="number" name="caja" value="1" required placeholder="Número de caja">
                                    </div>
                                </div>
                            </div>

                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Empresa</label>
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select name="empresa" class="input" required>
                                                <?php foreach($empresas as $e): ?>
                                                   <option value="<?= $e['empresa_id'] ?>"><?= $e['empresa_nombre'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field mt-4 has-text-centered">
                            <button type="submit" class="button is-info is-rounded">Registrar</button>
                            <a href="<?= APP_URL ?>nuevoUsuario?accion=lista" class="button is-light is-rounded">Ver Lista</a>
                        </div>
                    </form>
                </div>

            <?php elseif($accion === 'editar' && isset($usuarioEditar)): ?>
                <!-- FORMULARIO EDITAR USUARIO -->
                <div class="box">
                    <h3 class="title is-4 has-text-centered">Editar Usuario</h3>
                    <form method="POST" autocomplete="off">
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Nombre</label>
                                    <div class="control">
                                        <input class="input" type="text" name="nombre" value="<?= $usuarioEditar['usuario_nombre'] ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Apellido</label>
                                    <div class="control">
                                        <input class="input" type="text" name="apellido" value="<?= $usuarioEditar['usuario_apellido'] ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Email</label>
                                    <div class="control">
                                        <input class="input" type="email" name="email" value="<?= $usuarioEditar['usuario_email'] ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Usuario</label>
                                    <div class="control">
                                        <input class="input" type="text" name="usuario" value="<?= $usuarioEditar['usuario_usuario'] ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Cargo</label>
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select name="cargo" class="input">
                                                <option value="Empleado" <?= $usuarioEditar['usuario_cargo']=='Empleado'?'selected':'' ?>>Empleado</option>
                                                <option value="Administrador" <?= $usuarioEditar['usuario_cargo']=='Administrador'?'selected':'' ?>>Administrador</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">Caja</label>
                                    <div class="control">
                                        <input class="input" type="number" name="caja" value="<?= $usuarioEditar['caja_id'] ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field mt-4 has-text-centered">
                            <button type="submit" class="button is-info is-rounded">Actualizar</button>
                            <a href="<?= APP_URL ?>nuevoUsuario?accion=lista" class="button is-light is-rounded">Cancelar</a>
                        </div>
                    </form>
                </div>

            <?php elseif($accion === 'lista'): ?>
                <!-- TABLA DE USUARIOS - MANTENIENDO TU ESTILO -->
                <div class="box">
                    <div class="level">
                        <div class="level-left">
                            <h3 class="title is-4">Usuarios Registrados</h3>
                        </div>
                        <div class="level-right">
                            <a href="<?= APP_URL ?>nuevoUsuario?accion=form" class="button is-info is-rounded">
                                <i class="fas fa-plus"></i> &nbsp; Nuevo Usuario
                            </a>
                        </div>
                    </div>
                    
                    <table class="table is-striped is-fullwidth is-hoverable">
                        <thead>
                            <tr>
                                <th class="is-narrow">ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Usuario</th>
                                <th class="is-narrow">Cargo</th>
                                <th class="is-narrow">Caja</th>
                                <th>Empresa</th>
                                <th class="is-narrow">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($usuarios)): ?>
                                <?php foreach($usuarios as $u): ?>
                                    <tr>
                                        <td class="is-narrow"><?= $u['usuario_id']; ?></td>
                                        <td><?= $u['usuario_nombre']; ?></td>
                                        <td><?= $u['usuario_apellido']; ?></td>
                                        <td><?= $u['usuario_email']; ?></td>
                                        <td><?= $u['usuario_usuario']; ?></td>
                                        <td class="is-narrow">
                                            <span class="tag is-light">
                                                <?= $u['usuario_cargo']; ?>
                                            </span>
                                        </td>
                                        <td class="is-narrow"><?= $u['caja_id']; ?></td>
                                        <td><?= $u['empresa_nombre'] ?? 'No asignada'; ?></td>
                                        <td class="is-narrow">
                                            <a href="<?= APP_URL ?>nuevoUsuario?accion=editar&id=<?= $u['usuario_id'] ?>">Editar</a> |
                                            <a href="<?= APP_URL ?>nuevoUsuario?accion=eliminar&id=<?= $u['usuario_id'] ?>">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="has-text-centered">No hay usuarios registrados</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>