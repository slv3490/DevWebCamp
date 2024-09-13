<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#007df4">
    <!-- Open Graph -->
    <meta property="og:title" content="DevWebCamp pagina web para desarrolladores">
    <meta property="og:description" content="DevWebCamp es una conferencia para desarrolladores que cuenta con ponentes ofreciendo charlas y workshops. Los asistentes también tendrán la posibilidad de registrarse mediante un pago a través de PayPal.">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://i.postimg.cc/k5cWT6qM/ogimage.png">
    <meta property="og:image:alt" content="Imagen principal de la pagina web de DevWebCamp.">
    <meta property="description" content="DevWebCamp es una conferencia para desarrolladores que cuenta con ponentes ofreciendo charlas y workshops. Los asistentes también tendrán la posibilidad de registrarse mediante un pago a través de PayPal.">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="DevWebCamp pagina web para desarrolladores">
    <meta name="twitter:description" content="DevWebCamp es una conferencia para desarrolladores que cuenta con ponentes ofreciendo charlas y workshops. Los asistentes también tendrán la posibilidad de registrarse mediante un pago a través de PayPal.">
    <meta name="twitter:image" content="https://i.postimg.cc/k5cWT6qM/ogimage.png">

    <title>DevWebCamp - <?php echo $titulo; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="/build/css/app.css">
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin="" defer></script>
</head>
<body>
    <?php 
        include_once __DIR__ .'/templates/header.php';
        echo $contenido;
        include_once __DIR__ .'/templates/footer.php'; 
    ?>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <script src="/build/js/main.min.js" defer></script>
</body>
</html>