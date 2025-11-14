<?php
namespace app\controllers;

use app\models\clienteModel;

class clienteController extends clienteModel {

    /* // Constructor para verificar si el usuario está autenticado
    public function __construct() {
        // Verificar si el usuario está autenticado
        session_name(APP_SESSION_NAME);
        session_start();

        if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
            // Si no está autenticado, redirigir a la página de login
            echo "<script>window.location.href='".APP_URL."index.php?views=login';</script>";
            exit;
        }
    }
 */
    // Listar todos los clientes
    public function listarClientesControlador() {
        return $this->obtenerClientes();  
    }

    // Crear un nuevo cliente
    public function crearClienteControlador() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $datos = [
                'tipo_documento' => $_POST['tipo_documento'] ?? '',
                'numero_documento' => $_POST['numero_documento'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'apellido' => $_POST['apellido'] ?? '',
                'provincia' => $_POST['provincia'] ?? '',
                'ciudad' => $_POST['ciudad'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
            ];

            return $this->crearCliente($datos);
        }
    }

    // Actualizar 
    public function actualizarClienteControlador($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Recibir los datos del formulario y asignar valores por defecto si no están presentes
            $datos = [
                'tipo_documento' => $_POST['tipo_documento'] ?? '',
                'numero_documento' => $_POST['numero_documento'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'apellido' => $_POST['apellido'] ?? '',
                'provincia' => $_POST['provincia'] ?? '',
                'ciudad' => $_POST['ciudad'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
            ];

            return $this->actualizarCliente($id, $datos);
        }
    }

    // Eliminar un cliente
    public function eliminarClienteControlador($id) {
        return $this->eliminarCliente($id);
    }

    // Obtener los datos de un cliente por su ID
    public function obtenerClienteControlador($id) {
        return $this->obtenerCliente($id);
    }
}
