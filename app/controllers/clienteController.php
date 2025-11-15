<?php
namespace app\controllers;

use app\models\clienteModel;

class clienteController extends clienteModel {

    // Listar todos los clientes
    public function listarClientesControlador() {
        return $this->obtenerClientes();  
    }

    public function listarClientesPorEmpresa($empresaId) {
    return $this->obtenerClientesPorEmpresa($empresaId);
}

    // Crear un nuevo cliente
    public function crearClienteControlador($datos) {
        return $this->crearCliente($datos);
    }

    // Actualizar cliente
    public function actualizarClienteControlador($id, $datos) {
        return $this->actualizarCliente($id, $datos);
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