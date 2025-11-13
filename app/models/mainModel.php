<?php
namespace app\models;
use PDO;
use PDOException;
use app\config\dbConnection;

class mainModel extends dbConnection{

    protected function limpiarCadena($cadena){
        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);
        $cadena = htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8');
        return $cadena;
    }
}
