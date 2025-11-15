<?php
namespace app\controllers;
use app\models\usuarioModel;

class loginController extends usuarioModel {

    public function iniciarSesionControlador(){
        if(!isset($_POST['login_usuario']) || !isset($_POST['login_clave'])) return;
        $usuario = $this->limpiarCadena($_POST['login_usuario']);
        $clave   = $this->limpiarCadena($_POST['login_clave']);

        if($usuario=="" || $clave==""){
            echo "<script>alert('Debe ingresar usuario y contraseña');</script>";
            return;
        }

        $consulta = $this->buscarUsuario($usuario);

        if($consulta->rowCount()==1){
            $datos = $consulta->fetch();

            if(password_verify($clave, $datos['usuario_clave'])){
                session_name(APP_SESSION_NAME);
                session_start();
                $_SESSION['id']       = $datos['usuario_id'];
                $_SESSION['nombre']   = $datos['usuario_nombre'];
                $_SESSION['apellido'] = $datos['usuario_apellido'];
                $_SESSION['usuario']  = $datos['usuario_usuario'];
                $_SESSION['cargo']    = $datos['usuario_cargo'];
                $_SESSION['empresa_id'] = $datos['empresa_id'];

                echo "<script>window.location.href='".APP_URL."index.php?views=dashboard';</script>";
                exit;
            }else{
                echo "<script>alert('Contraseña incorrecta');</script>";
            }

        }else{
            echo "<script>alert('Usuario no encontrado');</script>";
        }
    }

    public function registrarUsuarioControlador(){
        $nombre   = $this->limpiarCadena($_POST['nombre'] ?? '');
        $apellido = $this->limpiarCadena($_POST['apellido'] ?? '');
        $email    = $this->limpiarCadena($_POST['email'] ?? '');
        $usuario  = $this->limpiarCadena($_POST['usuario'] ?? '');
        $clave    = password_hash($_POST['clave'] ?? '', PASSWORD_BCRYPT);
        $cargo    = "Empleado";
        $caja     = 1;
        $empresa  = $_POST['empresa'] ?? 1;
        

        $resultado = $this->registrarUsuario([
            "nombre"=>$nombre,
            "apellido"=>$apellido,
            "email"=>$email,
            "usuario"=>$usuario,
            "clave"=>$clave,
            "cargo"=>$cargo,
            "caja"=>$caja,
            "empresa"  => $empresa 
        ]);

        if($resultado){
            echo "<script>alert('Usuario registrado correctamente');</script>";
        }else{
            echo "<script>alert('Error al registrar usuario');</script>";
        }
    }

    public function cerrarSesion(){
        session_name(APP_SESSION_NAME);
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php?views=login');
        exit;
    }
}
