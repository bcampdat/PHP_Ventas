<?php
namespace app\controllers;

use app\models\ventaModel;

class ventaController extends ventaModel {

  /*   // Constructor para verificar si el usuario está autenticado
    public function __construct() {
        session_name(APP_SESSION_NAME);
        session_start();

        if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
          
            echo "<script>window.location.href='".APP_URL."index.php?views=login';</script>";
            exit;
        }
    }
 */
    // Listar todas las ventas
    public function listarVentasControlador() {
        return $this->obtenerVentas();  
    }

    // Crear una nueva venta
    public function crearVentaControlador() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $datos = [
                'codigo' => $_POST['codigo'] ?? '',
                'fecha' => $_POST['fecha'] ?? date('Y-m-d'),
                'hora' => $_POST['hora'] ?? date('H:i'),
                'total' => $_POST['total'] ?? 0,
                'pagado' => $_POST['pagado'] ?? 0,
                'cambio' => $_POST['cambio'] ?? 0,
                'usuario_id' => $_SESSION['id'], 
                'cliente_id' => $_POST['cliente_id'] ?? 0,
                'caja_id' => $_POST['caja_id'] ?? 0,
                'empresa_id' => $_POST['empresa_id'] ?? 0
            ];

            return $this->crearVenta($datos);
        }
    }

    // Actualizar una venta existente
    public function actualizarVentaControlador($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Recibir los datos del formulario
            $datos = [
                'codigo' => $_POST['codigo'] ?? '',
                'fecha' => $_POST['fecha'] ?? date('Y-m-d'),
                'hora' => $_POST['hora'] ?? date('H:i'),
                'total' => $_POST['total'] ?? 0,
                'pagado' => $_POST['pagado'] ?? 0,
                'cambio' => $_POST['cambio'] ?? 0,
                'usuario_id' => $_SESSION['id'], 
                'cliente_id' => $_POST['cliente_id'] ?? 0,
                'caja_id' => $_POST['caja_id'] ?? 0,
                'empresa_id' => $_POST['empresa_id'] ?? 0
            ];

            return $this->actualizarVenta($id, $datos);
        }
    }

    // Eliminar una venta
    public function eliminarVentaControlador($id) {
        return $this->eliminarVenta($id); 
    }

    // Obtener los datos de una venta por su ID
    public function obtenerVentaControlador($id) {
        return $this->obtenerVenta($id);  
    }
}
