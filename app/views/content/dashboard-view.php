<?php
use app\controllers\clienteController;
use app\controllers\cajaController;
use app\controllers\productoController;
use app\controllers\ventaController;
use app\controllers\usuarioController;
use app\controllers\categoriaController;

// Obtener el empresa_id del usuario logueado desde la sesión
$empresaId = $_SESSION['empresa_id'] ?? null;

if (!$empresaId) {
    $totalClientes = $totalCajas = $totalProductos = $totalVentas = $totalUsuarios = $totalCategorias = 0;
} else {
    // Instanciar controladores
    $clienteController = new clienteController();
    $cajaController = new cajaController();
    $productoController = new productoController();
    $ventaController = new ventaController();
    $usuarioController = new usuarioController();
    $categoriaController = new categoriaController();

    // Obtener contadores reales filtrados por empresa usando los nuevos métodos
    $totalClientes = count($clienteController->listarClientesPorEmpresa($empresaId) ?? []);
    $totalCajas = count($cajaController->listarCajasPorEmpresa($empresaId) ?? []);
    $totalProductos = count($productoController->listarProductosPorEmpresa($empresaId) ?? []);
    $totalVentas = count($ventaController->listarVentasPorEmpresa($empresaId) ?? []);
    $totalUsuarios = count($usuarioController->listarUsuariosPorEmpresa($empresaId) ?? []);
    $totalCategorias = count($categoriaController->listarCategoriasPorEmpresa($empresaId) ?? []);
}
?>

<!-- Content -->
<div class="container is-fluid">
    <h1 class="title">Home</h1>
    <div class="columns is-flex is-justify-content-center">
        <figure class="image is-128x128">
            <img class="is-rounded" src="<?php echo APP_URL; ?>app/views/fotos/default.png">
        </figure>
    </div>
    <div class="columns is-flex is-justify-content-center">
        <h2 class="subtitle">¡Bienvenido <?php echo htmlspecialchars($nombreUsuario); ?>!</h2>
    </div>
</div>

<div class="container pb-6 pt-6">

    <div class="columns pb-6">
        <div class="column">
            <nav class="level is-mobile">

                <div class="level-item has-text-centered">
                    <a href="<?php echo APP_URL; ?>caja?accion=lista">
                        <p class="heading"><i class="fas fa-cash-register fa-fw"></i> &nbsp; Cajas</p>
                        <p class="title is-4"><?= $totalCajas ?></p>
                    </a>
                </div>

                <div class="level-item has-text-centered">
                    <a href="<?php echo APP_URL; ?>nuevoUsuario?accion=lista">
                        <p class="heading"><i class="fas fa-users fa-fw"></i> &nbsp; Usuarios</p>
                        <p class="title is-4"><?= $totalUsuarios ?></p>
                    </a>
                </div>

                <div class="level-item has-text-centered">
                    <a href="<?php echo APP_URL; ?>cliente?accion=lista">
                        <p class="heading"><i class="fas fa-address-book fa-fw"></i> &nbsp; Clientes</p>
                        <p class="title is-4"><?= $totalClientes ?></p>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <div class="columns pt-6">
        <div class="column">
            <nav class="level is-mobile">

                <div class="level-item has-text-centered">
                    <a href="<?php echo APP_URL; ?>categoria?accion=lista">
                        <p class="heading"><i class="fas fa-tags fa-fw"></i> &nbsp; Categorías</p>
                        <p class="title is-4"><?= $totalCategorias ?></p>
                    </a>
                </div>

                <div class="level-item has-text-centered">
                    <a href="<?php echo APP_URL; ?>producto?accion=lista">
                        <p class="heading"><i class="fas fa-cubes fa-fw"></i> &nbsp; Productos</p>
                        <p class="title is-4"><?= $totalProductos ?></p>
                    </a>
                </div>

                <div class="level-item has-text-centered">
                    <a href="<?php echo APP_URL; ?>venta?accion=lista">
                        <p class="heading"><i class="fas fa-shopping-cart fa-fw"></i> &nbsp; Ventas</p>
                        <p class="title is-4"><?= $totalVentas ?></p>
                    </a>
                </div>
            </nav>
        </div>
    </div>
    
</div>
<!-- Fin content -->