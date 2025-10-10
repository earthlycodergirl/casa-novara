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
    <title>Privacy Policy - Casa Novara</title>
    <meta name="robots" content="index" />
    <meta name="description" content="Privacy policy for Casa Novara real estate services in Puerto Vallarta, Mexico.">

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
                    <li class="breadcrumb-item"><a href="<?= $link_home[$lang] ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Privacy Policy</li>
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
                        <h1>Privacy Policy</h1>
                        <p class="lead">Your privacy is important to us. This privacy policy explains how we collect, use, and protect your information.</p>
                        
                        <h2>1. Information We Collect</h2>
                        <p>We collect information you provide directly to us, such as when you:</p>
                        <ul>
                            <li>Contact us through our website forms</li>
                            <li>Subscribe to our newsletter</li>
                            <li>Request property information</li>
                            <li>Schedule property viewings</li>
                            <li>Create an account on our website</li>
                        </ul>
                        
                        <h2>2. How We Use Your Information</h2>
                        <p>We use the information we collect to:</p>
                        <ul>
                            <li>Provide real estate services and respond to your inquiries</li>
                            <li>Send you property listings and market updates</li>
                            <li>Schedule property viewings and appointments</li>
                            <li>Improve our website and services</li>
                            <li>Comply with legal obligations</li>
                        </ul>
                        
                        <h2>3. Information Sharing</h2>
                        <p>We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:</p>
                        <ul>
                            <li>With your explicit consent</li>
                            <li>To comply with legal requirements</li>
                            <li>To protect our rights and property</li>
                            <li>With trusted service providers who assist in our operations</li>
                        </ul>
                        
                        <h2>4. Data Security</h2>
                        <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet is 100% secure.</p>
                        
                        <h2>5. Cookies and Tracking</h2>
                        <p>Our website uses cookies to enhance your experience. Cookies are small data files stored on your device. You can control cookie settings through your browser preferences.</p>
                        
                        <h2>6. Third-Party Links</h2>
                        <p>Our website may contain links to third-party websites. We are not responsible for the privacy practices of these external sites. We encourage you to review their privacy policies.</p>
                        
                        <h2>7. Your Rights</h2>
                        <p>You have the right to:</p>
                        <ul>
                            <li>Access your personal information</li>
                            <li>Correct inaccurate information</li>
                            <li>Request deletion of your information</li>
                            <li>Opt-out of marketing communications</li>
                            <li>Port your data to another service</li>
                        </ul>
                        
                        <h2>8. Contact Information</h2>
                        <p>If you have any questions about this Privacy Policy or your personal information, please contact us at:</p>
                        <ul>
                            <li>Email: privacy@casanovaragroup.com</li>
                            <li>Phone: +52 322 123-4567</li>
                            <li>Address: Marina Vallarta, Puerto Vallarta, Jalisco, Mexico</li>
                        </ul>
                        
                        <h2>9. Changes to This Policy</h2>
                        <p>We may update this privacy policy from time to time. We will notify you of any changes by posting the new privacy policy on this page and updating the "Last updated" date.</p>
                        
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
