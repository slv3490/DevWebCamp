<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__texto">Cuenta Confirmada Correctamente.</p>

    <?php require_once __DIR__ . "/../templates/alertas.php"; ?>

    <?php if(isset($alertas["exito"])) { ?>

    <div class="acciones--centrar">
        <a href="/login" class="acciones__enlace">Iniciar Sesíon.</a>
    </div>

    <?php } ?>
</main>