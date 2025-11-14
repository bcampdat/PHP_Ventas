<?php
namespace app\models;

use PDO;

class ventaModel extends mainModel {

    // Obtener todas las ventas
    public function obtenerVentas() {
        $sql = $this->getPDO()->query("SELECT * FROM venta");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una venta por ID
    public function obtenerVenta($id) {
        $sql = $this->getPDO()->prepare("SELECT * FROM venta WHERE venta_id = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    // Crear una nueva venta
    public function crearVenta($datos) {
        $sql = $this->getPDO()->prepare("
            INSERT INTO venta (
                venta_codigo, venta_fecha, venta_hora, venta_total, venta_pagado, venta_cambio,
                usuario_id, cliente_id, caja_id, empresa_id
            ) VALUES (
                :codigo, :fecha, :hora, :total, :pagado, :cambio, 
                :usuario_id, :cliente_id, :caja_id, :empresa_id
            )
        ");

        // Asociar los parámetros de los datos a las variables
        foreach ($datos as $key => $value) {
            $sql->bindValue(":$key", $value);
        }

        return $sql->execute();
    }

    // Actualizar una venta existente
    public function actualizarVenta($id, $datos) {
        $sql = $this->getPDO()->prepare("
            UPDATE venta SET
                venta_codigo = :codigo,
                venta_fecha = :fecha,
                venta_hora = :hora,
                venta_total = :total,
                venta_pagado = :pagado,
                venta_cambio = :cambio,
                usuario_id = :usuario_id,
                cliente_id = :cliente_id,
                caja_id = :caja_id,
                empresa_id = :empresa_id
            WHERE venta_id = :id
        ");

        // Asociar los parámetros de los datos a las variables
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        foreach ($datos as $key => $value) {
            $sql->bindValue(":$key", $value);
        }

        return $sql->execute();
    }

    // Eliminar una venta
    public function eliminarVenta($id) {
        $sql = $this->getPDO()->prepare("DELETE FROM venta WHERE venta_id = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        return $sql->execute();
    }
}
