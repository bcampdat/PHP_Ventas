<?php
namespace app\models;

use PDO;

class categoriaModel extends mainModel {

    // Obtener todas las categorías
    public function obtenerCategorias() {
        $sql = $this->getPDO()->query("SELECT * FROM categoria");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una categoría por ID
    public function obtenerCategoria($id) {
        $sql = $this->getPDO()->prepare("SELECT * FROM categoria WHERE categoria_id = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    // Crear una nueva categoría
    public function crearCategoria($datos) {
        $sql = $this->getPDO()->prepare("
            INSERT INTO categoria (
                categoria_nombre, categoria_ubicacion
            ) VALUES (
                :nombre, :ubicacion
            )
        ");
        
        // Asociar los parámetros de los datos a las variables
        foreach ($datos as $key => $value) {
            $sql->bindValue(":$key", $value);
        }

        return $sql->execute();
    }

    // Actualizar los datos de una categoría
    public function actualizarCategoria($id, $datos) {
        $sql = $this->getPDO()->prepare("
            UPDATE categoria SET
                categoria_nombre = :nombre,
                categoria_ubicacion = :ubicacion
            WHERE categoria_id = :id
        ");

        // Asociar los parámetros de los datos a las variables
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        foreach ($datos as $key => $value) {
            $sql->bindValue(":$key", $value);
        }

        return $sql->execute();
    }

    // Eliminar una categoría
    public function eliminarCategoria($id) {
        $sql = $this->getPDO()->prepare("DELETE FROM categoria WHERE categoria_id = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        return $sql->execute();
    }
}

