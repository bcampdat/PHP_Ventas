<?php
namespace app\controllers;
use app\models\productoModel;

class productoController extends productoModel {

    public function listarProductos() {
        return $this->obtenerProductos();
    }

    public function crearProductoControlador() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $datos = [
                'codigo' => $_POST['codigo'] ?? uniqid('P-'),
                'nombre' => $_POST['nombre'] ?? '',
                'stock' => $_POST['stock'] ?? 0,
                'unidad' => $_POST['unidad'] ?? 'Unidad',
                'precio_compra' => $_POST['precio_compra'] ?? 0,
                'precio_venta' => $_POST['precio_venta'] ?? 0,
                'marca' => $_POST['marca'] ?? 'Sin marca',
                'modelo' => $_POST['modelo'] ?? 'N/A',
                'estado' => $_POST['estado'] ?? 'Activo',
                'foto' => '',
                'categoria' => $_POST['categoria'] ?? 1
            ];

            return $this->crearProducto($datos);
        }
    }

    public function actualizarProductoControlador() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $datos = [
                'id' => $_POST['id'] ?? 0,
                'codigo' => $_POST['codigo'] ?? uniqid('P-'),
                'nombre' => $_POST['nombre'] ?? '',
                'stock' => $_POST['stock'] ?? 0,
                'unidad' => $_POST['unidad'] ?? 'Unidad',
                'precio_compra' => $_POST['precio_compra'] ?? 0,
                'precio_venta' => $_POST['precio_venta'] ?? 0,
                'marca' => $_POST['marca'] ?? 'Sin marca',
                'modelo' => $_POST['modelo'] ?? 'N/A',
                'estado' => $_POST['estado'] ?? 'Activo',
                'foto' => '',
                'categoria' => $_POST['categoria'] ?? 1
            ];

            return $this->actualizarProducto($datos);
        }
    }

    public function eliminarProductoControlador($id) {
        return $this->eliminarProducto($id);
    }

    public function obtenerProductoControlador($id) {
        return $this->obtenerProducto($id);
    }
}
