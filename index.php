
<?php

   /*  este bloque permite ver mas concretos los errores
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */

    require_once "./app/config/app.php";
    require_once "./autoload.php";

    // variable de session  cokiee  POS
    require_once "./app/views/inc/session_start.php";

    /// Para crear las rutas por URL a las vistas
    if (isset($_GET['views'])) {
        $url = explode("/", $_GET['views']); // Divide la cadena en partes usando "/"
    } else {
        $url = ["login"]; // Si no hay parámetro, se carga la vista por defecto "login"
    }
    

    // Para probar qué valor tiene la primera parte de la URL
    // echo $url[0];

?>
<!DOCTYPE html>
<html lang="es">
<head>
   <?php require_once "./app/views/inc/head.php"; ?>
</head>
<body>

    <!-- el autoload lo gestiona -->
    <?php 
        use app\controllers\viewsController;

         // ahi que instanciar el controllador
        $viewsController=new viewsController();
        $vista= $viewsController->obtenerVistasControlador($url[0]);

        if($vista == "login" || $vista == "404"){
            // aqui se redirige a la vista  en el content  404-view.php o login-view.php ....
            require_once "./app/views/content/".$vista."-view.php";
        }else {
            
    ?>

    <!-- Main container -->
    <main class="page-container">

        <?php require_once "./app/views/inc/navlateral.php"; ?>

        <!-- Page content -->
        <section class="full-width pageContent scroll" id="pageContent">

            <?php 

                require_once "./app/views/inc/navbar.php"; 
                require_once $vista;            
            ?>   


        </section> <!-- Fin page content -->

    </main> <!-- Fin main container -->
    <?php
         }
        require_once "./app/views/inc/script.php"; 
    ?>
    
</body>
</html>