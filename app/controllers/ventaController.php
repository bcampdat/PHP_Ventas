<?php

namespace app\controllers;

use app\models\ventaModel;

class ventaController extends ventaModel
{

    // Listar todas las ventas
    public function listarVentasControlador()
    {
        return $this->obtenerVentas();
    }

    public function listarVentasPorEmpresa($empresaId)
    {
        return $this->obtenerVentasPorEmpresa($empresaId);
    }

    // Crear una nueva venta
    public function crearVentaControlador($datos)
    {
        return $this->crearVenta($datos);
    }

    // Actualizar una venta existente
    public function actualizarVentaControlador($id, $datos)
    {
        return $this->actualizarVenta($id, $datos);
    }

    // Eliminar una venta
    public function eliminarVentaControlador($id)
    {
        return $this->eliminarVenta($id);
    }

    // Obtener los datos de una venta por su ID
    public function obtenerVentaControlador($id)
    {
        return $this->obtenerVenta($id);
    }
}
