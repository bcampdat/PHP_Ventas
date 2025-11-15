<?php
namespace app\controllers;

use app\models\cajaModel;

class cajaController extends cajaModel {

    // Listar todas las cajas
    public function listarCajasControlador() {
        return $this->obtenerCajas();  
    }

    public function listarCajasPorEmpresa($empresaId) {
        return $this->obtenerCajasPorEmpresa($empresaId);
    }

    // Crear una nueva caja
    public function crearCajaControlador($datos) {
        return $this->crearCaja($datos);
    }

    // Actualizar una caja existente
    public function actualizarCajaControlador($id, $datos) {
        return $this->actualizarCaja($id, $datos);
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