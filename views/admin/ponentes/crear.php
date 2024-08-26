<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contenedor-boton">
    <a href="/admin/ponentes" class="dashboard__boton">
        <i class="fa-solid fa-circle-arrow-left"></i>
        Volver
    </a>
</div>

<div class="dashboard__formulario">
    <?php include_once __DIR__ . "/../../templates/alertas.php"; ?>

    <form action="/admin/ponentes/crear" enctype="multipart/form-data" class="formulario" method="POST">

        <?php include_once __DIR__ . "/formulario.php"; ?>

        <div>
            <input class="formulario__submit formulario__submit--registrar" type="submit" value="Registrar Ponente">
        </div>
    </form>
</div>