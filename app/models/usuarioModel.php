<?php
namespace app\models;
use PDO;
use PDOException;

class usuarioModel extends mainModel {

    public function buscarUsuario($usuario){
        $sql = $this->getPDO()->prepare("SELECT * FROM usuario WHERE usuario_usuario = :usuario");
        $sql->bindParam(":usuario", $usuario);
        $sql->execute();
        return $sql;
    }

    public function registrarUsuario($datos){
    $empresa_id = $datos['empresa'] ?? 1; // Por defecto empresa 1

    $sql = $this->getPDO()->prepare("
        INSERT INTO usuario 
        (usuario_nombre, usuario_apellido, usuario_email, usuario_usuario, usuario_clave, usuario_cargo, usuario_foto, caja_id, empresa_id)
        VALUES 
        (:nombre, :apellido, :email, :usuario, :clave, :cargo, '', :caja, :empresa)
    ");
    $sql->bindParam(":nombre", $datos['nombre']);
    $sql->bindParam(":apellido", $datos['apellido']);
    $sql->bindParam(":email", $datos['email']);
    $sql->bindParam(":usuario", $datos['usuario']);
    $sql->bindParam(":clave", $datos['clave']);
    $sql->bindParam(":cargo", $datos['cargo']);
    $sql->bindParam(":caja", $datos['caja']);
    $sql->bindParam(":empresa", $empresa_id);

    return $sql->execute();
    }

    // Listar todos los usuarios
    public function listarUsuarios() {
        $sql = $this->getPDO()->query("SELECT * FROM usuario");
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Obtener un usuario por ID
    public function obtenerUsuario($id) {
        $stmt = $this->getPDO()->prepare("SELECT * FROM usuario WHERE usuario_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Actualizar usuario
    public function actualizarUsuario($id, $datos) {
        $sql = $this->getPDO()->prepare("
            UPDATE usuario SET
                usuario_nombre = :nombre,
                usuario_apellido = :apellido,
                usuario_email = :email,
                usuario_usuario = :usuario,
                usuario_cargo = :cargo,
                caja_id = :caja,
                empresa_id= :empresa
            WHERE usuario_id = :id
        ");
        $sql->bindParam(':nombre', $datos['nombre']);
        $sql->bindParam(':apellido', $datos['apellido']);
        $sql->bindParam(':email', $datos['email']);
        $sql->bindParam(':usuario', $datos['usuario']);
        $sql->bindParam(':cargo', $datos['cargo']);
        $sql->bindParam(':caja', $datos['caja']);
        $sql->bindParam(':empresa', $datos['empresa']);
        $sql->bindParam(':id', $id);
        return $sql->execute();
    }

    // Eliminar usuario
    public function eliminarUsuario($id) {
        $sql = $this->getPDO()->prepare("DELETE FROM usuario WHERE usuario_id = :id");
        $sql->bindParam(':id', $id);
        return $sql->execute();
    }
}

