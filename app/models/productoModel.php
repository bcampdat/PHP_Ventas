<?php
namespace app\models;
use PDO;

class productoModel extends mainModel {

    public function obtenerProductos() {
        $sql = $this->getPDO()->query("SELECT * FROM producto");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProducto($id) {
        $sql = $this->getPDO()->prepare("SELECT * FROM producto WHERE producto_id = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function crearProducto($datos) {
        $sql = $this->getPDO()->prepare("
            INSERT INTO producto (
                producto_codigo, producto_nombre, producto_stock_total, producto_tipo_unidad,
                producto_precio_compra, producto_precio_venta, producto_marca, producto_modelo,
                producto_estado, producto_foto, categoria_id
            ) VALUES (
                :codigo, :nombre, :stock, :unidad, :precio_compra, :precio_venta, 
                :marca, :modelo, :estado, :foto, :categoria
            )
        ");

        foreach ($datos as $key => $value) {
            $sql->bindValue(":$key", $value);
        }

        return $sql->execute();
    }

    public function actualizarProducto($datos) {
        $sql = $this->getPDO()->prepare("
            UPDATE producto SET
                producto_codigo = :codigo,
                producto_nombre = :nombre,
                producto_stock_total = :stock,
                producto_tipo_unidad = :unidad,
                producto_precio_compra = :precio_compra,
                producto_precio_venta = :precio_venta,
                producto_marca = :marca,
                producto_modelo = :modelo,
                producto_estado = :estado,
                producto_foto = :foto,
                categoria_id = :categoria
            WHERE producto_id = :id
        ");

        foreach ($datos as $key => $value) {
            $sql->bindValue(":$key", $value);
        }

        return $sql->execute();
    }

    public function eliminarProducto($id) {
        $sql = $this->getPDO()->prepare("DELETE FROM producto WHERE producto_id = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        return $sql->execute();
    }
}
