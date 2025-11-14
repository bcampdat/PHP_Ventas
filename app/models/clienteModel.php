<?php
namespace app\models;
use PDO;

class clienteModel extends mainModel {

    // Obtener todos los clientes
    public function obtenerClientes() {
        $sql = $this->getPDO()->query("SELECT * FROM cliente");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un cliente por su ID
    public function obtenerCliente($id) {
        $sql = $this->getPDO()->prepare("SELECT * FROM cliente WHERE cliente_id = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo cliente
    public function crearCliente($datos) {
        $sql = $this->getPDO()->prepare("
            INSERT INTO cliente (
                cliente_tipo_documento, cliente_numero_documento, cliente_nombre, 
                cliente_apellido, cliente_provincia, cliente_ciudad, cliente_direccion, 
                cliente_telefono, cliente_email
            ) VALUES (
                :tipo_documento, :numero_documento, :nombre, :apellido, :provincia, 
                :ciudad, :direccion, :telefono, :email
            )
        ");

        foreach ($datos as $key => $value) {
            $sql->bindValue(":$key", $value);
        }

        return $sql->execute();
    }

    // Actualizar 
    public function actualizarCliente($id, $datos) {
        $sql = $this->getPDO()->prepare("
            UPDATE cliente SET
                cliente_tipo_documento = :tipo_documento,
                cliente_numero_documento = :numero_documento,
                cliente_nombre = :nombre,
                cliente_apellido = :apellido,
                cliente_provincia = :provincia,
                cliente_ciudad = :ciudad,
                cliente_direccion = :direccion,
                cliente_telefono = :telefono,
                cliente_email = :email
            WHERE cliente_id = :id
        ");

        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        foreach ($datos as $key => $value) {
            $sql->bindValue(":$key", $value);
        }

        return $sql->execute();
    }

    // Eliminar un cliente
    public function eliminarCliente($id) {
        $sql = $this->getPDO()->prepare("DELETE FROM cliente WHERE cliente_id = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        return $sql->execute();
    }
}
