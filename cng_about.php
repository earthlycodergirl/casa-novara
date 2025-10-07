<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Hero Template</title>

		<!-- Bootstrap CSS (matching index.php) -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

        <!-- Google fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
		<!-- Custom compiled stylesheet (from dist/less/cng.less) -->
		<link href="dist/css/cng_base.css" rel="stylesheet">
		<link href="dist/css/cng.css" rel="stylesheet">
      <link href="dist/css/cng_about.css" rel="stylesheet">
	</head>

	<body>

		<?php require 'dist/inc/nav-inner.php'; ?>

      <main id="about_us">
         <!-- Hero Section -->
         <section class="about-hero">
            <div class="container">
               <div class="hero-content">
                  <h1>About Casa Novara</h1>
                  <p class="hero-subtitle">Your trusted partner in luxury real estate across Puerto Vallarta and Riviera Nayarit</p>
               </div>
            </div>
         </section>

         <!-- Main Content -->
         <section class="about-content">
            <div class="container">
               
               <!-- Our Story with Side Image -->
               <div class="content-section story-section">
                  <div class="story-content">
                     <div class="story-text">
                        <h2>Our Story</h2>
                        <div class="content-text">
                           <p>For over a decade, Casa Novara has been the premier destination for luxury real estate in Mexico's Pacific Coast. We specialize in connecting discerning clients with exceptional properties that define coastal living at its finest.</p>
                           <p>Our deep understanding of the local market, combined with our commitment to personalized service, has made us the trusted choice for buyers and sellers seeking the very best in luxury real estate.</p>
                        </div>
                     </div>
                     <div class="story-image">
                        <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=2075&q=80" alt="Casa Novara Office">
                     </div>
                  </div>
               </div>

               <!-- Our Mission -->
               <div class="content-section">
                  <h2>Our Mission</h2>
                  <div class="content-text">
                     <p>To provide unparalleled real estate services that exceed expectations, connecting our clients with their dream properties while delivering exceptional value and expertise at every step of the journey.</p>
                  </div>
               </div>

               <!-- Our Values -->
               <div class="content-section">
                  <h2>Our Values</h2>
                  <div class="values-grid">
                     <div class="value-item">
                        <h3>Excellence</h3>
                        <p>We maintain the highest standards in everything we do, from property curation to client service.</p>
                     </div>
                     <div class="value-item">
                        <h3>Integrity</h3>
                        <p>Transparent, honest communication forms the foundation of all our client relationships.</p>
                     </div>
                     <div class="value-item">
                        <h3>Expertise</h3>
                        <p>Our deep market knowledge and years of experience guide every recommendation we make.</p>
                     </div>
                  </div>
               </div>

               <!-- Contact CTA -->
               <div class="contact-cta">
                  <h2>Ready to Find Your Dream Property?</h2>
                  <p>Let our experienced team guide you to the perfect luxury property in paradise.</p>
                  <a href="/contact.php" class="cta-button">Contact Us Today</a>
               </div>

            </div>
         </section>

      </main>      

      <?php require 'dist/inc/foot.php' ?>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
   </body>
</html>