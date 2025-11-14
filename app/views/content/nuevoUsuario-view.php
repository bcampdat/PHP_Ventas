<?php
use app\controllers\usuarioController;
use app\controllers\empresaController;

$usuarioController = new usuarioController();
$empresaController = new empresaController();
$empresas = $empresaController->listarEmpresasControlador();

// Acción a ejecutar
$accion = $_GET['accion'] ?? 'form';
$id = $_GET['id'] ?? null;


// Procesar eliminación
if($accion === 'eliminar' && $id){
    $usuarioController->eliminarUsuario($id);
    echo "<script>alert('Usuario eliminado'); window.location.href='".APP_URL."nuevoUsuario?accion=lista';</script>";
    exit;
}

// Procesar POST de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($accion === 'form'){ // Nuevo usuario
        $resultado = $usuarioController->registrarUsuario([
            "nombre"   => $_POST['nombre'] ?? '',
            "apellido" => $_POST['apellido'] ?? '',
            "email"    => $_POST['email'] ?? '',
            "usuario"  => $_POST['usuario'] ?? '',
            "clave"    => password_hash($_POST['clave'] ?? '', PASSWORD_BCRYPT),
            "cargo"    => $_POST['cargo'] ?? 'Empleado',
            "caja"     => $_POST['caja'] ?? 1
        ]);
        if($resultado){
            echo "<script>alert('Usuario registrado correctamente'); window.location.href='".APP_URL."nuevoUsuario?accion=form';</script>";
            exit;
        }
    } elseif($accion === 'editar' && $id){ // Editar usuario
        $usuarioController->actualizarUsuario($id, [
            "nombre"   => $_POST['nombre'],
            "apellido" => $_POST['apellido'],
            "email"    => $_POST['email'],
            "usuario"  => $_POST['usuario'],
            "cargo"    => $_POST['cargo'],
            "caja"     => $_POST['caja']
        ]);
        echo "<script>alert('Usuario actualizado'); window.location.href='".APP_URL."nuevoUsuario?accion=lista';</script>";
        exit;
    }
}

// Obtener datos para editar
if($accion === 'editar' && $id){
    $usuarioEditar = $usuarioController->obtenerUsuario($id);
}

// Obtener lista de usuarios
if($accion === 'lista'){
    $usuarios = $usuarioController->listarUsuarios();
}
?>

<div class="container">
    <div class="columns">
        <div class="column is-8 is-offset-2">

            <?php if($accion === 'form'): ?>
                <!-- FORMULARIO NUEVO USUARIO -->
                <div class="box">
                    <h3 class="title is-4 has-text-centered">Nuevo Usuario</h3>
                    <form method="POST" autocomplete="off">
                        <div class="field">
                            <label class="label">Nombre</label>
                            <div class="control">
                                <input class="input" type="text" name="nombre" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Apellido</label>
                            <div class="control">
                                <input class="input" type="text" name="apellido" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control">
                                <input class="input" type="email" name="email" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Usuario</label>
                            <div class="control">
                                <input class="input" type="text" name="usuario" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Contraseña</label>
                            <div class="control">
                                <input class="input" type="password" name="clave" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Cargo</label>
                            <div class="control">
                                <select name="cargo" class="input">
                                    <option value="Empleado">Empleado</option>
                                    <option value="Administrador">Administrador</option>
                                </select>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Caja</label>
                            <div class="control">
                                <input class="input" type="number" name="caja" value="1" required>
                            </div>
                        </div>

                         <div class="field">
                            <label class="label">Empresa</label>
                            <div class="control">
                                <select name="empresa" class="input" required>
                                    <?php foreach($empresas as $e): ?>
                                       <option value="<?= $e['empresa_id'] ?>"><?= $e['empresa_nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="field mt-4 has-text-centered">
                            <button type="submit" class="button is-info is-rounded">Registrar</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <?php if($accion === 'editar' && isset($usuarioEditar)): ?>
                <!-- FORMULARIO EDITAR USUARIO -->
                <div class="box">
                    <h3 class="title is-4 has-text-centered">Editar Usuario</h3>
                    <form method="POST" autocomplete="off">
                        <div class="field">
                            <label class="label">Nombre</label>
                            <div class="control">
                                <input class="input" type="text" name="nombre" value="<?= $usuarioEditar['usuario_nombre'] ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Apellido</label>
                            <div class="control">
                                <input class="input" type="text" name="apellido" value="<?= $usuarioEditar['usuario_apellido'] ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control">
                                <input class="input" type="email" name="email" value="<?= $usuarioEditar['usuario_email'] ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Usuario</label>
                            <div class="control">
                                <input class="input" type="text" name="usuario" value="<?= $usuarioEditar['usuario_usuario'] ?>" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Cargo</label>
                            <div class="control">
                                <select name="cargo" class="input">
                                    <option value="Empleado" <?= $usuarioEditar['usuario_cargo']=='Empleado'?'selected':'' ?>>Empleado</option>
                                    <option value="Administrador" <?= $usuarioEditar['usuario_cargo']=='Administrador'?'selected':'' ?>>Administrador</option>
                                </select>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Caja</label>
                            <div class="control">
                                <input class="input" type="number" name="caja" value="<?= $usuarioEditar['caja_id'] ?>" required>
                            </div>
                        </div>

                        <div class="field mt-4 has-text-centered">
                            <button type="submit" class="button is-info is-rounded">Actualizar</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <?php if($accion === 'lista'): ?>
                <!-- TABLA DE USUARIOS -->
                <div class="box">
                    <h3 class="title is-4 has-text-centered">Usuarios Registrados</h3>
                    <table class="table is-striped is-fullwidth">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Usuario</th>
                                <th>Cargo</th>
                                <th>Caja</th>
                                <th>Empresa</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($usuarios)): ?>
                                <?php foreach($usuarios as $u): ?>
                                    <tr>
                                        <td><?= $u['usuario_id']; ?></td>
                                        <td><?= $u['usuario_nombre']; ?></td>
                                        <td><?= $u['usuario_apellido']; ?></td>
                                        <td><?= $u['usuario_email']; ?></td>
                                        <td><?= $u['usuario_usuario']; ?></td>
                                        <td><?= $u['usuario_cargo']; ?></td>
                                        <td><?= $u['caja_id']; ?></td>
                                        <td><?= $u['empresa_nombre'] ?? 'No asignada'; ?></td>
                                        <td>
                                            <a href="<?= APP_URL ?>nuevoUsuario?accion=editar&id=<?= $u['usuario_id'] ?>">Editar</a> |
                                            <a href="<?= APP_URL ?>nuevoUsuario?accion=eliminar&id=<?= $u['usuario_id'] ?>" onclick="return confirm('¿Seguro que quieres eliminar este usuario?');">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="has-text-centered">No hay usuarios registrados</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
