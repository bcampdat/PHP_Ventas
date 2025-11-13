<?php
    
    namespace app\config;

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    use PDO; // Necesitamos PHP DATA OBJECT para trabajar con la base de datos

    class dbConnection
    {
        private $pdo;

        public function __construct()
        {
            // Conexión a la base de datos utilizando los parámetros de server.php
            require_once __DIR__ . '/server.php'; 

            try {
                $this->pdo = new PDO(
                    "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME,
                    DB_USER,
                    DB_PASS
                );
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "Conexión a la base de datos exitosa";
            } catch (\PDOException $e) {
                // En caso de error de conexión, muestra un mensaje
                die("Error al conectar con la base de datos: " . $e->getMessage());
            }
        }

        // Método para obtener la instancia PDO y usarla en otros modelos
        public function getPDO()
        {
            return $this->pdo;
        }
    }
