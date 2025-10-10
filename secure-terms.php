<?php
require('base.php');
$nav_class = $logo_type = 'dark';
?>
<!doctype html>
<html lang="en">
<head>
    <base href="<?= $base_href ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms and Conditions - Casa Novara</title>
    <meta name="robots" content="index" />
    <meta name="description" content="Terms and conditions for Casa Novara real estate services in Puerto Vallarta, Mexico.">

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
                    <li class="breadcrumb-item"><a href="<?= $link_home[$lang] ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Terms and Conditions</li>
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
                        <h1>Terms and Conditions</h1>
                        <p class="lead">Please read these terms and conditions carefully before using our services.</p>
                        
                        <h2>1. Acceptance of Terms</h2>
                        <p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p>
                        
                        <h2>2. Use License</h2>
                        <p>Permission is granted to temporarily download one copy of the materials on Casa Novara's website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:</p>
                        <ul>
                            <li>modify or copy the materials</li>
                            <li>use the materials for any commercial purpose or for any public display (commercial or non-commercial)</li>
                            <li>attempt to decompile or reverse engineer any software contained on Casa Novara's website</li>
                            <li>remove any copyright or other proprietary notations from the materials</li>
                        </ul>
                        
                        <h2>3. Disclaimer</h2>
                        <p>The materials on Casa Novara's website are provided on an 'as is' basis. Casa Novara makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>
                        <p>Further, Casa Novara does not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the materials on its website or otherwise relating to such materials or on any sites linked to this site.</p>
                        
                        <h2>4. Limitations</h2>
                        <p>In no event shall Casa Novara or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on Casa Novara's website, even if Casa Novara or a Casa Novara authorized representative has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.</p>
                        
                        <h2>5. Privacy Policy</h2>
                        <p>Your privacy is important to us. Please review our Privacy Policy, which also governs your use of the Site, to understand our practices regarding the collection, use, and disclosure of your personal information.</p>
                        
                        <h2>6. Real Estate Services</h2>
                        <p>Casa Novara provides real estate services in Puerto Vallarta, Mexico. All property listings are subject to availability and pricing changes. We strive to provide accurate information, but property details, pricing, and availability are subject to change without notice.</p>
                        
                        <h2>7. Governing Law</h2>
                        <p>These terms and conditions are governed by and construed in accordance with the laws of Mexico and you irrevocably submit to the exclusive jurisdiction of the courts in that State or location.</p>
                        
                        <h2>8. Contact Information</h2>
                        <p>If you have any questions about these Terms and Conditions, please contact us at:</p>
                        <ul>
                            <li>Email: info@casanovaragroup.com</li>
                            <li>Phone: +52 322 123-4567</li>
                            <li>Address: Marina Vallarta, Puerto Vallarta, Jalisco, Mexico</li>
                        </ul>
                        
                        <p class="text-muted mt-4"><small>Last updated: October 2025</small></p>
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
