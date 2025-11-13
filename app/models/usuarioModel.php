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
        $sql = $this->getPDO()->prepare("
            INSERT INTO usuario (usuario_nombre, usuario_apellido, usuario_email, usuario_usuario, usuario_clave, usuario_cargo, usuario_foto, caja_id)
            VALUES (:nombre, :apellido, :email, :usuario, :clave, :cargo, '', :caja)
        ");
        $sql->bindParam(":nombre", $datos['nombre']);
        $sql->bindParam(":apellido", $datos['apellido']);
        $sql->bindParam(":email", $datos['email']);
        $sql->bindParam(":usuario", $datos['usuario']);
        $sql->bindParam(":clave", $datos['clave']);
        $sql->bindParam(":cargo", $datos['cargo']);
        $sql->bindParam(":caja", $datos['caja']);
        return $sql->execute();
    }
}
