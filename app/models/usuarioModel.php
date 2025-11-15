<?php
namespace app\models;
use PDO;
use PDOException;

class usuarioModel extends mainModel {

    private $uploadPath;

    public function __construct() {
        parent::__construct();
        // Ruta para fotos de usuario
        $this->uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/POS/app/views/fotos/';
        
        // Crear la carpeta si no existe
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
    }

    public function buscarUsuario($usuario){
        $sql = $this->getPDO()->prepare("SELECT * FROM usuario WHERE usuario_usuario = :usuario");
        $sql->bindParam(":usuario", $usuario);
        $sql->execute();
        return $sql;
    }

    public function obtenerUsuariosPorEmpresa($empresaId)
    {
        $sql = $this->getPDO()->prepare("SELECT * FROM usuario WHERE empresa_id = :empresa_id");
        $sql->bindParam(":empresa_id", $empresaId, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarUsuario($datos){
        $empresa_id = $datos['empresa'] ?? 1;
        $foto = $this->procesarFoto($_FILES['foto'] ?? null); // Procesar foto

        $sql = $this->getPDO()->prepare("
            INSERT INTO usuario 
            (usuario_nombre, usuario_apellido, usuario_email, usuario_usuario, usuario_clave, usuario_cargo, usuario_foto, caja_id, empresa_id)
            VALUES 
            (:nombre, :apellido, :email, :usuario, :clave, :cargo, :foto, :caja, :empresa)
        ");
        $sql->bindParam(":nombre", $datos['nombre']);
        $sql->bindParam(":apellido", $datos['apellido']);
        $sql->bindParam(":email", $datos['email']);
        $sql->bindParam(":usuario", $datos['usuario']);
        $sql->bindParam(":clave", $datos['clave']);
        $sql->bindParam(":cargo", $datos['cargo']);
        $sql->bindParam(":foto", $foto);
        $sql->bindParam(":caja", $datos['caja']);
        $sql->bindParam(":empresa", $empresa_id);

        return $sql->execute();
    }

    // Listar todos los usuarios
    public function listarUsuarios() {
        $sql = $this->getPDO()->query("SELECT * FROM usuario");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un usuario por ID
    public function obtenerUsuario($id) {
        $stmt = $this->getPDO()->prepare("SELECT * FROM usuario WHERE usuario_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar usuario
    public function actualizarUsuario($id, $datos) {
        // Obtener usuario actual para la foto
        $usuarioActual = $this->obtenerUsuario($id);
        
        // Procesar foto si viene
        $foto = $this->procesarFoto($_FILES['foto'] ?? null, $usuarioActual['usuario_foto']);
        
        // Si se solicita eliminar foto
        if (isset($_POST['eliminar_foto']) && $_POST['eliminar_foto'] == '1') {
            $foto = 'default.png';
            if($usuarioActual['usuario_foto'] !== 'default.png') {
                $this->eliminarImagen($usuarioActual['usuario_foto']);
            }
        }

        $sql = $this->getPDO()->prepare("
            UPDATE usuario SET
                usuario_nombre = :nombre,
                usuario_apellido = :apellido,
                usuario_email = :email,
                usuario_usuario = :usuario,
                usuario_cargo = :cargo,
                usuario_foto = :foto,
                caja_id = :caja,
                empresa_id = :empresa
            WHERE usuario_id = :id
        ");
        $sql->bindParam(':nombre', $datos['nombre']);
        $sql->bindParam(':apellido', $datos['apellido']);
        $sql->bindParam(':email', $datos['email']);
        $sql->bindParam(':usuario', $datos['usuario']);
        $sql->bindParam(':cargo', $datos['cargo']);
        $sql->bindParam(':foto', $foto);
        $sql->bindParam(':caja', $datos['caja']);
        $sql->bindParam(':empresa', $datos['empresa']);
        $sql->bindParam(':id', $id);
        return $sql->execute();
    }

    // Eliminar usuario
    public function eliminarUsuario($id) {
        // Obtener usuario para eliminar su foto
        $usuario = $this->obtenerUsuario($id);
        if($usuario && $usuario['usuario_foto'] !== 'default.png') {
            $this->eliminarImagen($usuario['usuario_foto']);
        }

        $sql = $this->getPDO()->prepare("DELETE FROM usuario WHERE usuario_id = :id");
        $sql->bindParam(':id', $id);
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

    // Método público para procesar la subida de foto
    public function procesarFoto($archivo, $fotoActual = null) {
        // Si se sube nueva imagen
        if(isset($archivo) && $archivo['error'] === 0) {
            $nuevaFoto = $this->subirImagen($archivo);
            if($fotoActual && $fotoActual !== 'default.png') {
                $this->eliminarImagen($fotoActual);
            }
            return $nuevaFoto;
        }
        return $fotoActual ?: 'default.png';
    }
}