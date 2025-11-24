<?php
require('base.php');
$nav_class = $logo_type = 'dark';

?>

<!doctype html>
<html lang="<?= $lang ?>">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Page Not Found - Casa Novara Group</title>
		<meta name="description" content="Uh oh! This page is not available on our website. Please visit our homepage or contact us today to find what you are looking for.">
		<meta name="robots" content="noindex" />

		<!-- Bootstrap CSS -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

		<!-- Google fonts -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
		
		<!-- Custom stylesheets -->
		<link href="<?= $assets_prefix ?>/dist/css/cng_base.css" rel="stylesheet">
		<link href="<?= $assets_prefix ?>/dist/css/cng.css" rel="stylesheet">

		<style>
			/* 404 Page Specific Styles */
			.error-hero {
				background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
				padding: 100px 0 80px;
				min-height: 70vh;
				display: flex;
				align-items: center;
			}
			
			.error-content {
				text-align: center;
				max-width: 600px;
				margin: 0 auto;
			}
			
			.error-number {
				font-size: 8rem;
				font-weight: 900;
				color: #e9ecef;
				line-height: 1;
				margin-bottom: 20px;
				text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
			}
			
			.error-title {
				font-size: 2.5rem;
				font-weight: 700;
				color: #212529;
				margin-bottom: 20px;
			}
			
			.error-subtitle {
				font-size: 1.2rem;
				color: #6c757d;
				margin-bottom: 40px;
				line-height: 1.6;
			}
			
			.error-buttons {
				display: flex;
				gap: 15px;
				justify-content: center;
				flex-wrap: wrap;
				margin-top: 40px;
			}
			
			.error-btn {
				padding: 15px 30px;
				font-weight: 500;
				border-radius: 8px;
				text-decoration: none;
				transition: all 0.3s ease;
				display: inline-flex;
				align-items: center;
				gap: 8px;
			}
			
			.error-btn-primary {
				background: #212529;
				color: white;
				border: 2px solid #212529;
			}
			
			.error-btn-primary:hover {
				background: #495057;
				border-color: #495057;
				color: white;
				text-decoration: none;
				transform: translateY(-2px);
			}
			
			.error-btn-outline {
				background: transparent;
				color: #212529;
				border: 2px solid #212529;
			}
			
			.error-btn-outline:hover {
				background: #212529;
				color: white;
				text-decoration: none;
				transform: translateY(-2px);
			}
			
			.error-icon {
				width: 120px;
				height: 120px;
				margin: 0 auto 30px;
				background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				font-size: 3rem;
				color: #6c757d;
				border: 3px solid #e9ecef;
			}
			
			@media (max-width: 768px) {
				.error-hero {
					padding: 80px 0 60px;
					min-height: 60vh;
				}
				
				.error-number {
					font-size: 6rem;
				}
				
				.error-title {
					font-size: 2rem;
				}
				
				.error-subtitle {
					font-size: 1.1rem;
				}
				
				.error-buttons {
					flex-direction: column;
					align-items: center;
				}
				
				.error-btn {
					width: 250px;
					justify-content: center;
				}
			}
		</style>

		<?php include 'dist/inc/favicon.php'; ?>
	</head>

	<body>
		<?php require 'dist/inc/nav-inner.php'; ?>


		<main>
			<!-- 404 Error Section -->
			<section class="error-hero">
				<div class="container">
					<div class="error-content">
						<div class="error-number">404</div>
						<h1 class="error-title">Oops! We Can't Find That Page</h1>
						<p class="error-subtitle">
							It looks like the page you're looking for has moved to a new address, or perhaps it never existed. 
							Don't worry though – our luxury properties are all exactly where they should be!
						</p>
						
						<div class="error-buttons">
							<a href="/" class="error-btn error-btn-primary">
								Return Home
							</a>
							<a href="cng_listings.php" class="error-btn error-btn-outline">
								View Properties
							</a>
							<a href="contact.php" class="error-btn error-btn-outline">
								Contact Us
							</a>
						</div>
					</div>
				</div>
			</section>
		</main>

		<?php require 'dist/inc/foot.php'; ?>


		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
	</body>
</html>

