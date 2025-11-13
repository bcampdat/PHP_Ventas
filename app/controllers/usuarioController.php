<?php
namespace app\controllers;
use app\models\usuarioModel;

class usuarioController extends usuarioModel {
    public function listarUsuarios() {
        $sql = $this->getPDO()->query("SELECT * FROM usuario");
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

}
