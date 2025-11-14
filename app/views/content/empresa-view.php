<?php
use app\controllers\empresaController;

$empresaController = new empresaController();

if ($empresa_id) {
    $empresa = $empresaController->obtenerEmpresaControlador($empresa_id);
} else {
    $empresa = null;
}
?>

<div class="container">
    <div class="columns">
        <div class="column is-8 is-offset-2">
            <div class="box">
                <h3 class="title is-4 has-text-centered">Datos de la Empresa</h3>

                <?php if($empresa): ?>
                    <p><strong>Nombre:</strong> <?= htmlspecialchars($empresa['empresa_nombre']); ?></p>
                    <p><strong>Teléfono:</strong> <?= htmlspecialchars($empresa['empresa_telefono']); ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($empresa['empresa_email']); ?></p>
                    <p><strong>Dirección:</strong> <?= htmlspecialchars($empresa['empresa_direccion']); ?></p>
                    <figure class="image is-128x128 mt-3">
                        <img src="<?= APP_URL ?>app/views/fotos/<?= htmlspecialchars($empresa['empresa_foto'] ?: 'default.png'); ?>" alt="Logo empresa">
                    </figure>
                <?php else: ?>
                    <p class="has-text-centered">No se encontró la empresa asociada a este usuario.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
