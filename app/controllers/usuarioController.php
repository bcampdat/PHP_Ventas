<?php
namespace app\controllers;
use app\models\usuarioModel;

class usuarioController extends usuarioModel {
    public function listarUsuarios() {
 
    $sql = $this->getPDO()->query("SELECT * FROM usuario");
    $usuarios = $sql->fetchAll(\PDO::FETCH_ASSOC);
    
    
    foreach ($usuarios as &$usuario) {
        $empresa_id = $usuario['empresa_id'];
        
        // Hacemos la consulta para obtener el nombre de la empresa
        $sql_empresa = $this->getPDO()->prepare("SELECT empresa_nombre FROM empresa WHERE empresa_id = :empresa_id");
        $sql_empresa->bindParam(':empresa_id', $empresa_id);
        $sql_empresa->execute();
        $empresa = $sql_empresa->fetch(\PDO::FETCH_ASSOC);

        $usuario['empresa_nombre'] = $empresa ? $empresa['empresa_nombre'] : 'Desconocida';  // Por si el ID no existe
    }

    return $usuarios;
}


}
