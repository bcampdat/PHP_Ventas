<?php

namespace app\models;

use PDO;
use app\config\dbConnection;

class UsuarioLogin
{
    private $pdo;

    public function __construct()
    {
        // Suponiendo que dbConnection ya devuelve una instancia de PDO
        $dbConnection = new dbConnection();
        $this->pdo = $dbConnection->getPDO(); 
    }

    // Método para verificar si el usuario existe y la contraseña es correcta
    public function login($username, $password)
    {
        try {
            $sql = "SELECT * FROM usuario WHERE usuario_usuario = :username LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificar la contraseña (usando password_verify)
                if (password_verify($password, $user['usuario_clave'])) {
                    return $user;  
                } else {
                    throw new \Exception("Contraseña incorrecta.");
                }
            } else {
                throw new \Exception("Usuario no encontrado.");
            }
        } catch (\PDOException $e) {
            return "Error en la base de datos: " . $e->getMessage();
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Método para registrar un nuevo usuario
    public function registrar($nombre, $apellido, $email, $usuario, $clave, $cargo, $foto, $caja_id, $empresa)
    {
        try {
            // Encriptar la contraseña con bcrypt
            $clave_encriptada = password_hash($clave, PASSWORD_BCRYPT);

            // SQL para insertar el nuevo usuario en la base de datos
            $sql = "INSERT INTO usuario (usuario_nombre, usuario_apellido, usuario_email, usuario_usuario, usuario_clave, usuario_cargo, usuario_foto, caja_id, empresa_id)
                    VALUES (:nombre, :apellido, :email, :usuario, :clave, :cargo, :foto, :caja_id, :empresa)";

            $stmt = $this->pdo->prepare($sql);

            // Vinculamos los valores de la consulta SQL con los parámetros de entrada
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->bindParam(':clave', $clave_encriptada);  
            $stmt->bindParam(':cargo', $cargo);
            $stmt->bindParam(':foto', $foto);
            $stmt->bindParam(':caja_id', $caja_id);
            $stmt->bindParam(':empresa', $empresa);


            if ($stmt->execute()) {
                return true;  
            } else {
                throw new \Exception("Error al registrar el usuario.");
            }
        } catch (\PDOException $e) {
            return "Error en la base de datos: " . $e->getMessage();
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
?>
