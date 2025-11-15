<?php

namespace app\models;

use PDO;

class productoModel extends mainModel
{

    private $uploadPath;

    public function __construct()
    {
        parent::__construct();
        // Ruta absoluta corregida
        $this->uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/POS/app/views/productos/';

        // Crear la carpeta si no existe
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
    }

    public function obtenerProductos()
    {
        $sql = $this->getPDO()->query("SELECT * FROM producto");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProductosPorEmpresa($empresaId)
    {
        $sql = $this->getPDO()->prepare("SELECT * FROM producto WHERE empresa_id = :empresa_id");
        $sql->bindParam(":empresa_id", $empresaId, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProducto($id)
    {
        $sql = $this->getPDO()->prepare("SELECT * FROM producto WHERE producto_id = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function crearProducto($datos)
    {
        // Procesar imagen si viene
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $datos['foto'] = $this->subirImagen($_FILES['foto']);
        } else {
            $datos['foto'] = 'default.png';
        }

        $sql = $this->getPDO()->prepare("
            INSERT INTO producto (
                producto_codigo, producto_nombre, producto_stock_total, producto_tipo_unidad,
                producto_precio_compra, producto_precio_venta, producto_marca, producto_modelo,
                producto_estado, producto_foto, categoria_id, empresa_id
            ) VALUES (
                :codigo, :nombre, :stock, :unidad, :precio_compra, :precio_venta, 
                :marca, :modelo, :estado, :foto, :categoria, :empresa
            )
        ");

        foreach ($datos as $key => $value) {
            $sql->bindValue(":$key", $value);
        }

        return $sql->execute();
    }

    public function actualizarProducto($id, $datos)
    {
        // Obtener producto actual para la imagen
        $productoActual = $this->obtenerProducto($id);

        // Procesar imagen si viene
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $nuevaImagen = $this->subirImagen($_FILES['foto']);
            // Eliminar imagen anterior si no es default.png
            if ($productoActual['producto_foto'] !== 'default.png') {
                $this->eliminarImagen($productoActual['producto_foto']);
            }
            $datos['foto'] = $nuevaImagen;
        } else {
            $datos['foto'] = $productoActual['producto_foto'];
        }

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
                categoria_id = :categoria,
                empresa_id = :empresa
            WHERE producto_id = :id
        ");

        $sql->bindValue(":id", $id);
        foreach ($datos as $key => $value) {
            $sql->bindValue(":$key", $value);
        }

        return $sql->execute();
    }

    public function eliminarProducto($id)
    {
        // Obtener producto para eliminar su imagen
        $producto = $this->obtenerProducto($id);
        if ($producto && $producto['producto_foto'] !== 'default.png') {
            $this->eliminarImagen($producto['producto_foto']);
        }

        $sql = $this->getPDO()->prepare("DELETE FROM producto WHERE producto_id = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        return $sql->execute();
    }

    // Métodos para gestión de imágenes
    private function subirImagen($archivo)
    {
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid() . '.' . $extension;
        $rutaDestino = $this->uploadPath . $nombreArchivo;

        // Validar tipo de archivo
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($extension), $extensionesPermitidas)) {
            return 'default.png';
        }

        // Validar tamaño (máximo 2MB)
        if ($archivo['size'] > 2097152) {
            return 'default.png';
        }

        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            return $nombreArchivo;
        }

        return 'default.png';
    }

    private function eliminarImagen($nombreArchivo)
    {
        $rutaArchivo = $this->uploadPath . $nombreArchivo;
        if (file_exists($rutaArchivo) && $nombreArchivo !== 'default.png') {
            unlink($rutaArchivo);
        }
    }
}
