<?php
namespace app\controllers;
use app\models\productoModel;

class productoController extends productoModel {

    public function listarProductos() {
        return $this->obtenerProductos();
    }

    public function listarProductosPorEmpresa() {
        return $this->obtenerProductos();  
    }

    public function crearProductoControlador($datos) {
        return $this->crearProducto($datos);
    }

    public function actualizarProductoControlador($id, $datos) {
        return $this->actualizarProducto($id, $datos);
    }

    public function eliminarProductoControlador($id) {
        return $this->eliminarProducto($id);
    }

    public function obtenerProductoControlador($id) {
        return $this->obtenerProducto($id);
    }
}