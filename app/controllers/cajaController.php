<?php
namespace app\controllers;

use app\models\cajaModel;

class cajaController extends cajaModel {

    // Listar todas las cajas
    public function listarCajasControlador() {
        return $this->obtenerCajas();  
    }

    // Crear una nueva caja
    public function crearCajaControlador() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Recibir los datos del formulario
            $datos = [
                'numero' => $_POST['numero'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'efectivo' => $_POST['efectivo'] ?? 0.00,
            ];

            return $this->crearCaja($datos);
        }
    }

    // Actualizar una caja existente
    public function actualizarCajaControlador($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Recibir los datos del formulario
            $datos = [
                'numero' => $_POST['numero'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'efectivo' => $_POST['efectivo'] ?? 0.00,
            ];

            return $this->actualizarCaja($id, $datos);
        }
    }

    // Eliminar una caja
    public function eliminarCajaControlador($id) {
        return $this->eliminarCaja($id);
    }

    // Obtener los datos de una caja por su ID
    public function obtenerCajaControlador($id) {
        return $this->obtenerCaja($id);
    }
}
