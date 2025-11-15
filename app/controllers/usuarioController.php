<?php

namespace app\controllers;

use app\models\usuarioModel;

class usuarioController extends usuarioModel
{

    public function listarUsuarios()
    {
        $sql = $this->getPDO()->query("SELECT * FROM usuario");
        $usuarios = $sql->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($usuarios as &$usuario) {
            $empresa_id = $usuario['empresa_id'];

            $sql_empresa = $this->getPDO()->prepare("SELECT empresa_nombre FROM empresa WHERE empresa_id = :empresa_id");
            $sql_empresa->bindParam(':empresa_id', $empresa_id);
            $sql_empresa->execute();
            $empresa = $sql_empresa->fetch(\PDO::FETCH_ASSOC);

            $usuario['empresa_nombre'] = $empresa ? $empresa['empresa_nombre'] : 'Desconocida';
        }

        return $usuarios;
    }

    public function listarUsuariosPorEmpresa($empresaId)
    {
        return $this->obtenerUsuariosPorEmpresa($empresaId);
    }

    public function registrarUsuarioControlador($datos)
    {
        return $this->registrarUsuario($datos);
    }

    public function actualizarUsuarioControlador($id, $datos)
    {
        return $this->actualizarUsuario($id, $datos);
    }

    public function eliminarUsuarioControlador($id)
    {
        return $this->eliminarUsuario($id);
    }

    public function obtenerUsuarioControlador($id)
    {
        return $this->obtenerUsuario($id);
    }
}
