<?php
    spl_autoload_register(function($clase) {
        // Reemplaza las barras invertidas del namespace por barras normales
        $ruta = str_replace("\\", "/", $clase);

        // Convierte "App/Controllers/viewsController" en "app/controllers/viewsController.php"
        $ruta = str_replace("App/", "app/", $ruta);

        $archivo = __DIR__ . "/$ruta.php";

        if (is_file($archivo)) {
            require_once $archivo;
        } else {
            // Mostrar error si no existe, para depuración
            echo "<pre>No se encontró: $archivo</pre>";
        }
    });