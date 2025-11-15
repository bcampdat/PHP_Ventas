<?php
namespace app\controllers;

use app\models\categoriaModel;

class categoriaController extends categoriaModel {

    // Listar todas las categorías
    public function listarCategoriasControlador() {
        return $this->obtenerCategorias();  
    }

    public function listarCategoriasPorEmpresa() {
        return $this->obtenerCategorias();  
    }

    // Crear una nueva categoría
    public function crearCategoriaControlador($datos) {
        return $this->crearCategoria($datos);
    }

    // Actualizar una categoría existente
    public function actualizarCategoriaControlador($id, $datos) {
        return $this->actualizarCategoria($id, $datos);
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