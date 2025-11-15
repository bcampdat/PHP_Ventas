<?php
namespace app\models;
use PDO;

class empresaModel extends mainModel {

    private $uploadPath;

    public function __construct() {
        parent::__construct();
        // Ruta para logos de empresa
        $this->uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/POS/app/views/img/';
        
        // Crear la carpeta si no existe
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
    }

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

        $sql->bindValue(":id", $id);
        foreach ($datos as $key => $value) {
            $sql->bindValue(":$key", $value);
        }

        return $sql->execute();
    }

    // Eliminar una empresa
    public function eliminarEmpresa($id) {
        // Obtener empresa para eliminar su logo
        $empresa = $this->obtenerEmpresa($id);
        if($empresa && $empresa['empresa_foto'] !== 'default.png') {
            $this->eliminarImagen($empresa['empresa_foto']);
        }

        $sql = $this->getPDO()->prepare("DELETE FROM empresa WHERE empresa_id = :id");
        $sql->bindParam(":id", $id, PDO::PARAM_INT);
        return $sql->execute();
    }

    // Métodos para gestión de imágenes 
    private function subirImagen($archivo) {
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid() . '.' . $extension;
        $rutaDestino = $this->uploadPath . $nombreArchivo;

        // Validar tipo de archivo
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        if(!in_array(strtolower($extension), $extensionesPermitidas)) {
            return 'default.png';
        }

        // Validar tamaño (máximo 2MB)
        if($archivo['size'] > 2097152) {
            return 'default.png';
        }

        if(move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            return $nombreArchivo;
        }

        return 'default.png';
    }

    private function eliminarImagen($nombreArchivo) {
        $rutaArchivo = $this->uploadPath . $nombreArchivo;
        if(file_exists($rutaArchivo) && $nombreArchivo !== 'default.png') {
            unlink($rutaArchivo);
        }
    }

    // Método público para procesar la subida de logo
    public function procesarLogo($archivo, $logoActual = null) {
        // Si se sube nueva imagen
        if(isset($archivo) && $archivo['error'] === 0) {
            $nuevoLogo = $this->subirImagen($archivo);
            if($logoActual && $logoActual !== 'default.png') {
                $this->eliminarImagen($logoActual);
            }
            return $nuevoLogo;
        }
        return $logoActual ?: 'default.png';
    }
}