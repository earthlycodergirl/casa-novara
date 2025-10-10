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
    <title>Política de Privacidad - Casa Novara</title>
    <meta name="robots" content="index" />
    <meta name="description" content="Política de privacidad para los servicios de bienes raíces de Casa Novara en Puerto Vallarta, México.">

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
<body class="secure-privacy-page">
    <?php require 'dist/inc/nav-inner.php';?>

    <!-- Breadcrumbs Section -->
    <div class="breadcrumb-section">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: ' > ';" aria-label="breadcrumb" class="breadcrumbs">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= $link_home[$lang] ?>">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Política de Privacidad</li>
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
                        <h1>Política de Privacidad</h1>
                        <p class="lead">Su privacidad es importante para nosotros. Esta política de privacidad explica cómo recopilamos, usamos y protegemos su información.</p>
                        
                        <h2>1. Información que Recopilamos</h2>
                        <p>Recopilamos información que usted nos proporciona directamente, como cuando:</p>
                        <ul>
                            <li>Se contacta con nosotros a través de los formularios de nuestro sitio web</li>
                            <li>Se suscribe a nuestro boletín</li>
                            <li>Solicita información de propiedades</li>
                            <li>Programa visitas a propiedades</li>
                            <li>Crea una cuenta en nuestro sitio web</li>
                        </ul>
                        
                        <h2>2. Cómo Usamos Su Información</h2>
                        <p>Usamos la información que recopilamos para:</p>
                        <ul>
                            <li>Proporcionar servicios inmobiliarios y responder a sus consultas</li>
                            <li>Enviarle listados de propiedades y actualizaciones del mercado</li>
                            <li>Programar visitas y citas de propiedades</li>
                            <li>Mejorar nuestro sitio web y servicios</li>
                            <li>Cumplir con obligaciones legales</li>
                        </ul>
                        
                        <h2>3. Compartir Información</h2>
                        <p>No vendemos, intercambiamos o alquilamos su información personal a terceros. Podemos compartir su información solo en las siguientes circunstancias:</p>
                        <ul>
                            <li>Con su consentimiento explícito</li>
                            <li>Para cumplir con requisitos legales</li>
                            <li>Para proteger nuestros derechos y propiedad</li>
                            <li>Con proveedores de servicios de confianza que nos asisten en nuestras operaciones</li>
                        </ul>
                        
                        <h2>4. Seguridad de Datos</h2>
                        <p>Implementamos medidas de seguridad apropiadas para proteger su información personal contra acceso no autorizado, alteración, divulgación o destrucción. Sin embargo, ningún método de transmisión por internet es 100% seguro.</p>
                        
                        <h2>5. Cookies y Seguimiento</h2>
                        <p>Nuestro sitio web utiliza cookies para mejorar su experiencia. Las cookies son pequeños archivos de datos almacenados en su dispositivo. Puede controlar la configuración de cookies a través de las preferencias de su navegador.</p>
                        
                        <h2>6. Enlaces de Terceros</h2>
                        <p>Nuestro sitio web puede contener enlaces a sitios web de terceros. No somos responsables de las prácticas de privacidad de estos sitios externos. Le recomendamos que revise sus políticas de privacidad.</p>
                        
                        <h2>7. Sus Derechos</h2>
                        <p>Usted tiene derecho a:</p>
                        <ul>
                            <li>Acceder a su información personal</li>
                            <li>Corregir información inexacta</li>
                            <li>Solicitar la eliminación de su información</li>
                            <li>Optar por no recibir comunicaciones de marketing</li>
                            <li>Portar sus datos a otro servicio</li>
                        </ul>
                        
                        <h2>8. Información de Contacto</h2>
                        <p>Si tiene alguna pregunta sobre esta Política de Privacidad o su información personal, por favor contáctenos en:</p>
                        <ul>
                            <li>Email: privacy@casanovaragroup.com</li>
                            <li>Teléfono: +52 322 123-4567</li>
                            <li>Dirección: Marina Vallarta, Puerto Vallarta, Jalisco, México</li>
                        </ul>
                        
                        <h2>9. Cambios a Esta Política</h2>
                        <p>Podemos actualizar esta política de privacidad de vez en cuando. Le notificaremos de cualquier cambio publicando la nueva política de privacidad en esta página y actualizando la fecha de "Última actualización".</p>
                        
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
