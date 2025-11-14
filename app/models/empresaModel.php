<?php
namespace app\models;

use PDO;

class empresaModel extends mainModel {

    // Obtener todas las empresas
    public function obtenerEmpresas() {
        $sql = $this->getPDO()->query("SELECT * FROM empresa");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una empresa por ID
    public function obtenerEmpresa($id) {
        $sql = $this->getPDO()->prepare("SELECT * FROM empresa WHERE empresa_id = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    // Crear una nueva empresa
    public function crearEmpresa($datos) {
        $sql = $this->getPDO()->prepare("
            INSERT INTO empresa (
                empresa_nombre, empresa_telefono, empresa_email, 
                empresa_direccion, empresa_foto
            ) VALUES (
                :nombre, :telefono, :email, :direccion, :foto
            )
        ");
        
        // Asociar los parámetros de los datos a las variables
        foreach ($datos as $key => $value) {
            $sql->bindValue(":$key", $value);
        }

        return $sql->execute();
    }

    // Actualizar los datos de una empresa
    public function actualizarEmpresa($id, $datos) {
        $sql = $this->getPDO()->prepare("
            UPDATE empresa SET
                empresa_nombre = :nombre,
                empresa_telefono = :telefono,
                empresa_email = :email,
                empresa_direccion = :direccion,
                empresa_foto = :foto
            WHERE empresa_id = :id
        ");

        // Asociar los parámetros de los datos a las variables
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        foreach ($datos as $key => $value) {
            $sql->bindValue(":$key", $value);
        }

        return $sql->execute();
    }

    // Eliminar una empresa
    public function eliminarEmpresa($id) {
        $sql = $this->getPDO()->prepare("DELETE FROM empresa WHERE empresa_id = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        return $sql->execute();
    }
}

