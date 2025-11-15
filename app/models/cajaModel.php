<?php
namespace app\models;

use PDO;

class cajaModel extends mainModel {

    // Obtener todas las cajas
    public function obtenerCajas() {
        $sql = $this->getPDO()->query("SELECT * FROM caja");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCajasPorEmpresa($empresaId) {
    $sql = $this->getPDO()->prepare("SELECT * FROM caja WHERE empresa_id = :empresa_id");
    $sql->bindParam(":empresa_id", $empresaId, PDO::PARAM_INT);
    $sql->execute();
    return $sql->fetchAll(PDO::FETCH_ASSOC);
}

    // Obtener una caja por ID
    public function obtenerCaja($id) {
        $sql = $this->getPDO()->prepare("SELECT * FROM caja WHERE caja_id = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    // Crear una nueva caja
    public function crearCaja($datos) {
        $sql = $this->getPDO()->prepare("
            INSERT INTO caja (
                caja_numero, caja_nombre, caja_efectivo
            ) VALUES (
                :numero, :nombre, :efectivo
            )
        ");
        
        // Asociar los parámetros de los datos a las variables
        foreach ($datos as $key => $value) {
            $sql->bindValue(":$key", $value);
        }

        return $sql->execute();
    }

    // Actualizar los datos de una caja
    public function actualizarCaja($id, $datos) {
        $sql = $this->getPDO()->prepare("
            UPDATE caja SET
                caja_numero = :numero,
                caja_nombre = :nombre,
                caja_efectivo = :efectivo
            WHERE caja_id = :id
        ");

        // Asociar los parámetros de los datos a las variables
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        foreach ($datos as $key => $value) {
            $sql->bindValue(":$key", $value);
        }

        return $sql->execute();
    }

    // Eliminar una caja
    public function eliminarCaja($id) {
        $sql = $this->getPDO()->prepare("DELETE FROM caja WHERE caja_id = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        return $sql->execute();
    }
}
