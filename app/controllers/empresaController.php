<?php
namespace app\controllers;

use app\models\empresaModel;

class empresaController extends empresaModel {

    // Listar todas las empresas
    public function listarEmpresasControlador() {
        return $this->obtenerEmpresas();  
    }

    // Crear una nueva empresa
    public function crearEmpresaControlador() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'foto' => $this->procesarLogo($_FILES['foto'] ?? null), // Procesar logo
            ];

            return $this->crearEmpresa($datos);
        }
    }

    // Actualizar una empresa existente
    public function actualizarEmpresaControlador($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Obtener empresa actual para el logo
            $empresaActual = $this->obtenerEmpresa($id);

            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'foto' => $this->procesarLogo($_FILES['foto'] ?? null, $empresaActual['empresa_foto']), // Procesar logo
            ];

            return $this->actualizarEmpresa($id, $datos);
        }
    }

    // Eliminar una empresa
    public function eliminarEmpresaControlador($id) {
        return $this->eliminarEmpresa($id);
    }

    // Obtener los datos de una empresa por su ID
    public function obtenerEmpresaControlador($id) {
        return $this->obtenerEmpresa($id);
    }
}