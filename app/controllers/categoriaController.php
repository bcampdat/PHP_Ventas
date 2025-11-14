<?php
namespace app\controllers;

use app\models\categoriaModel;

class categoriaController extends categoriaModel {

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
    } */

    // Listar todas las categorías
    public function listarCategoriasControlador() {
        return $this->obtenerCategorias();  
    }

    // Crear una nueva categoría
    public function crearCategoriaControlador() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'ubicacion' => $_POST['ubicacion'] ?? '',
            ];

            // Llamar al modelo para crear la categoría
            return $this->crearCategoria($datos);
        }
    }

    // Actualizar una categoría existente
    public function actualizarCategoriaControlador($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'ubicacion' => $_POST['ubicacion'] ?? '',
            ];

           
            return $this->actualizarCategoria($id, $datos);
        }
    }

    // Eliminar una categoría
    public function eliminarCategoriaControlador($id) {
        return $this->eliminarCategoria($id);
    }

    // Obtener los datos de una categoría por su ID
    public function obtenerCategoriaControlador($id) {
        return $this->obtenerCategoria($id);
    }
}

