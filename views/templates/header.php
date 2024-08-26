<header class="header">
    <div class="header__contenedor">
        <nav class="header__navegacion">
            <?php if(isAuth()) { ?>
                <a href="<?php echo isAdmin() ? "/admin/dashboard" : "/finalizar-registro" ?>" class="header__enlace">Administrar</a>

                <form method="POST" action="/logout" class="header__form">
                    <input type="submit" class="header__submit" value="Cerrar Sesión">
                </form>
            <?php } else { ?> 
                <a href="/registro" class="header__enlace">Registro</a>
                <a href="/login" class="header__enlace">Iniciar Sesíon</a>
            <?php }; ?>
        </nav>
    </div>
    <div class="header__contenido">
        <a href="/">
            <h1 class="header__logo">
                &#60;DevWebCamp/>
            </h1>
        </a>
        <p class="header__texto">Octubre 5-6 - 2023</p>
        <p class="header__texto header__texto--modalidad">En línea - Presencial</p>

        <a href="/registro" class="header__boton">Comprar Pase</a>
    </div>
</header>
<div class="barra">
    <div class="barra__contenido">
        <a href="/">
            <h2 class="barra__logo">
                &#60;DevWebCamp/>
            </h2>
        </a>
        <nav class="navegacion">
            <a href="/devwebcamp" class="navegacion__enlace <?php echo pagina_actual("/devwebcamp") ? "navegacion__enlace--actual" : "" ?>">Eventos</a>
            <a href="/paquetes" class="navegacion__enlace <?php echo pagina_actual("/paquetes") ? "navegacion__enlace--actual" : "" ?>">Paquetes</a>
            <a href="/workshop-conferencias" class="navegacion__enlace <?php echo pagina_actual("/workshop-conferencias") ? "navegacion__enlace--actual" : "" ?>">Workshop / Conferencias</a>
            <a href="/registro" class="navegacion__enlace <?php echo pagina_actual("/registro") ? "navegacion__enlace--actual" : "" ?>">Comprar Pase</a>
        </nav>
    </div>
</div>