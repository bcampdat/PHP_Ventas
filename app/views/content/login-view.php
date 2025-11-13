<?php
use app\controllers\loginController;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $login = new loginController();

    if(isset($_POST['accion']) && $_POST['accion'] == "login"){
        $login->iniciarSesionControlador();
    } elseif(isset($_POST['accion']) && $_POST['accion'] == "registro"){
        $login->registrarUsuarioControlador();
    }
}
?>

<section class="section main-container">
  <div class="container">
    <div class="columns">
      <div class="column is-4 is-offset-4">

        <!-- Formulario de inicio de sesión -->
        <div id="login-form">
          <form class="box" method="POST" autocomplete="off">
            <p class="has-text-centered">
                <i class="fas fa-user-circle fa-5x"></i>
            </p>
            <h5 class="title is-5 has-text-centered">Inicia sesión con tu cuenta</h5>

            <input type="hidden" name="accion" value="login">

            <div class="field">
              <label class="label"><i class="fas fa-user-secret"></i> &nbsp; Usuario</label>
              <div class="control">
                <input class="input" type="text" name="login_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
              </div>
            </div>

            <div class="field">
              <label class="label"><i class="fas fa-key"></i> &nbsp; Clave</label>
              <div class="control">
                <input class="input" type="password" name="login_clave" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
              </div>
            </div>

            <p class="has-text-centered mb-4 mt-3">
              <button type="submit" class="button is-info is-rounded">LOG IN</button>
            </p>

            <!-- Enlace a registro -->
            <p class="has-text-centered">
              <a href="javascript:void(0);" id="show-register-form">¿No tienes cuenta? Regístrate aquí</a>
            </p>
          </form>
        </div>

        <!-- Formulario de registro -->
        <div id="register-form" style="display: none;">
          <form class="box" method="POST" autocomplete="off">
            <p class="has-text-centered">
                <i class="fas fa-user-plus fa-5x"></i>
            </p>
            <h5 class="title is-5 has-text-centered">Crea una cuenta nueva</h5>

            <input type="hidden" name="accion" value="registro">

            <div class="field">
              <label class="label"><i class="fas fa-user"></i> &nbsp; Nombre</label>
              <div class="control">
                <input class="input" type="text" name="nombre" required>
              </div>
            </div>

            <div class="field">
              <label class="label"><i class="fas fa-user"></i> &nbsp; Apellido</label>
              <div class="control">
                <input class="input" type="text" name="apellido" required>
              </div>
            </div>

            <div class="field">
              <label class="label"><i class="fas fa-envelope"></i> &nbsp; Email</label>
              <div class="control">
                <input class="input" type="email" name="email" required>
              </div>
            </div>

            <div class="field">
              <label class="label"><i class="fas fa-user-secret"></i> &nbsp; Usuario</label>
              <div class="control">
                <input class="input" type="text" name="usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
              </div>
            </div>

            <div class="field">
              <label class="label"><i class="fas fa-key"></i> &nbsp; Clave</label>
              <div class="control">
                <input class="input" type="password" name="clave" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
              </div>
            </div>

            <p class="has-text-centered mb-4 mt-3">
              <button type="submit" class="button is-info is-rounded">REGISTRARSE</button>
            </p>

            <!-- Enlace a login -->
            <p class="has-text-centered">
              <a href="javascript:void(0);" id="show-login-form">¿Ya tienes cuenta? Inicia sesión</a>
            </p>
          </form>
        </div>

      </div>
    </div>
  </div>
</section>

<script>
// Mostrar/ocultar formularios de login y registro
document.getElementById('show-register-form').addEventListener('click', function() {
    document.getElementById('login-form').style.display = 'none';
    document.getElementById('register-form').style.display = 'block';
});

document.getElementById('show-login-form').addEventListener('click', function() {
    document.getElementById('login-form').style.display = 'block';
    document.getElementById('register-form').style.display = 'none';
});
</script>
