<?php

    const APP_URL="http://localhost/POS/";
    const APP_NAME="VENTAS";
    const APP_SESSION_NAME="POS";


    const DOCUMENTOS= ["DUI", "DNI", "Cedula", "Licencia", "Pasaporte", "Otro"];
    
    const PRODUCTO_UNIDAD = ["Unidad", "Libra", "Kilogramo", "Caja", "Paquete", "Lata", "litro", "Galon", "Botella", "Tira", "Sobre", "Bolsa", "Saco", "Tarjeta", "Otro"];
    

    const MONEDA_SIMBOLO = "€";  // Símbolo del euro
    const MONEDA_NOMBRE = "EUR"; // Código ISO de la moneda
    const MONEDA_DECIMALES = 2;
    const SEPARADOR_DECIMAL = ",";    // Separador decimal 
    const SEPARADOR_MILLAR = ".";      // Separador de millar

    //const CAMPO_OBLIGATORIO='&nbsp; <i class="fas fa-edit"></i> &nbsp;';

    date_default_timezone_set("Europe/Madrid");