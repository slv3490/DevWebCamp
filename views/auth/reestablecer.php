<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>

    <?php require_once __DIR__ . "/../templates/alertas.php"; ?>

    <?php if ($token_valido) { ?>
    <p class="auth__texto">Coloca tu nuevo password.</p>

    <form class="formulario" method="POST">
        <div class="formulario__campo">
            <label for="password" class="formulario__label">Nuevo password</label>
            <input type="password" class="formulario__input" placeholder="Tu nuevo password" id="password" name="password">
        </div>

        <input type="submit" class="formulario__submit" value="Guardar Cambiosf">
    </form>

    <div class="acciones">
        <a href="/login" class="acciones__enlace">¿Ya tienes una cuenta? Iniciar Sesíon.</a>
        <a href="/registro" class="acciones__enlace">¿Aún no tienes cuenta? Obtener una.</a>
    </div>
    <?php } ?>
</main>