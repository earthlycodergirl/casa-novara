<?php
require('base.php');
$nav_class = $logo_type = 'dark';
$lang = 'es';
?>
<!doctype html>
<html lang="es">
<head>
    <base href="<?= $base_href ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Términos y Condiciones - Casa Novara</title>
    <meta name="robots" content="index" />
    <meta name="description" content="Términos y condiciones para los servicios de bienes raíces de Casa Novara en Puerto Vallarta, México.">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="dist/css/cng_base.css" rel="stylesheet">
    <link href="dist/css/cng.css" rel="stylesheet">
    
    <style>
        .secure-content {
            padding: 2rem 0 4rem;
        }
        .content-wrapper {
            background: white;
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(61, 48, 38, 0.08);
        }
        .content-wrapper h1 {
            color: #3d3026;
            margin-bottom: 1rem;
        }
        .content-wrapper h2 {
            color: #3d3026;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        .content-wrapper p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        .content-wrapper ul {
            color: #666;
            line-height: 1.6;
        }
        .lead {
            font-size: 1.1rem;
            color: #8a7968;
        }
    </style>
</head>
<body class="secure-terms-page">
    <?php require 'dist/inc/nav-inner.php';?>

    <!-- Breadcrumbs Section -->
    <div class="breadcrumb-section">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: ' > ';" aria-label="breadcrumb" class="breadcrumbs">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $link_home[$lang] ?>">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Términos y Condiciones</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content Section -->
    <section class="secure-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="content-wrapper">
                        <h1>Términos y Condiciones</h1>
                        <p class="lead">Por favor lea estos términos y condiciones cuidadosamente antes de usar nuestros servicios.</p>
                        
                        <h2>1. Aceptación de Términos</h2>
                        <p>Al acceder y usar este sitio web, usted acepta y está de acuerdo en estar sujeto a los términos y disposiciones de este acuerdo.</p>
                        
                        <h2>2. Licencia de Uso</h2>
                        <p>Se otorga permiso para descargar temporalmente una copia de los materiales en el sitio web de Casa Novara para uso personal y no comercial únicamente. Esta es la concesión de una licencia, no una transferencia de título, y bajo esta licencia usted no puede:</p>
                        <ul>
                            <li>modificar o copiar los materiales</li>
                            <li>usar los materiales para cualquier propósito comercial o para exhibición pública</li>
                            <li>intentar descompilar o realizar ingeniería inversa de cualquier software contenido en el sitio web de Casa Novara</li>
                            <li>eliminar cualquier derecho de autor u otras anotaciones propietarias de los materiales</li>
                        </ul>
                        
                        <h2>3. Descargo de Responsabilidad</h2>
                        <p>Los materiales en el sitio web de Casa Novara se proporcionan "tal como están". Casa Novara no hace garantías, expresas o implícitas, y por este medio niega y anula todas las demás garantías, incluyendo sin limitación, garantías implícitas o condiciones de comerciabilidad, idoneidad para un propósito particular, o no violación de propiedad intelectual u otra violación de derechos.</p>
                        
                        <h2>4. Limitaciones</h2>
                        <p>En ningún caso Casa Novara o sus proveedores serán responsables por daños (incluyendo, sin limitación, daños por pérdida de datos o beneficios, o debido a la interrupción del negocio) que surjan del uso o la incapacidad de usar los materiales en el sitio web de Casa Novara.</p>
                        
                        <h2>5. Política de Privacidad</h2>
                        <p>Su privacidad es importante para nosotros. Por favor revise nuestra Política de Privacidad, que también rige su uso del Sitio, para entender nuestras prácticas.</p>
                        
                        <h2>6. Servicios Inmobiliarios</h2>
                        <p>Casa Novara proporciona servicios inmobiliarios en Puerto Vallarta, México. Todas las propiedades están sujetas a disponibilidad y cambios de precio. Nos esforzamos por proporcionar información precisa, pero los detalles, precios y disponibilidad de las propiedades están sujetos a cambios sin previo aviso.</p>
                        
                        <h2>7. Ley Aplicable</h2>
                        <p>Estos términos y condiciones se rigen e interpretan de acuerdo con las leyes de México y usted se somete irrevocablemente a la jurisdicción exclusiva de los tribunales en ese Estado o ubicación.</p>
                        
                        <h2>8. Información de Contacto</h2>
                        <p>Si tiene alguna pregunta sobre estos Términos y Condiciones, por favor contáctenos en:</p>
                        <ul>
                            <li>Email: info@casanovaragroup.com</li>
                            <li>Teléfono: +52 322 123-4567</li>
                            <li>Dirección: Marina Vallarta, Puerto Vallarta, Jalisco, México</li>
                        </ul>
                        
                        <p class="text-muted mt-4"><small>Última actualización: Octubre 2025</small></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require 'dist/inc/foot.php'; ?>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
