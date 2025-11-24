<?php
require('base.php');
$nav_class = $logo_type = 'dark';

$site = new Site();
$site->getBlog(0);

if($lang == 'en'){
  $current_link['es'] = $link_blog['es'];
}else{
  $current_link['en'] = $link_blog['en'];
}

?>

<!doctype html>
<html lang="<?= $lang ?>">
	<head>
    <base href="<?= $base_href ?>">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Casa Novara Group - Real Estate News</title>
		<meta name="description" content="<?= $meta['blog']['desc'] ?>" />
		<meta name="robots" content="index" />
		<link rel="canonical" href="https://casanovaragroup.com/<?= $link_blog[$lang] ?>">

		<!-- Open Graph / Facebook -->
		<meta property="og:type" content="website">
		<meta property="og:title" content="Casa Novara Group - Real Estate News">
		<meta property="og:description" content="<?= $meta['blog']['desc'] ?>">
		<meta property="og:image" content="https://casanovaragroup.com/dist/img/social.jpg">
		<meta property="og:url" content="https://casanovaragroup.com/<?= $link_blog[$lang] ?>">
		<meta property="og:site_name" content="Casa Novara Group">

		<!-- Twitter -->
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:title" content="Casa Novara Group - Real Estate News">
		<meta name="twitter:description" content="<?= $meta['blog']['desc'] ?>">
		<meta name="twitter:image" content="https://casanovaragroup.com/dist/img/social.jpg">

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
			/* Blog Listing Specific Styles */
			.blog-hero {
				position: relative;
				background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
				padding: 80px 0 60px;
				overflow: hidden;
			}
			
			.blog-hero::before {
				content: '';
				position: absolute;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				background-color: rgba(0, 0, 0, 0.6);
				z-index: 1;
			}
			
			.blog-hero-bg {
				position: absolute;
				top: -20px;
				left: -20px;
				right: -20px;
				bottom: -20px;
				background-image: url('dist/img/bg-tulum-min.jpeg');
				background-size: cover;
				background-position: center;
				background-repeat: no-repeat;
				filter: blur(8px);
				z-index: 0;
			}
			
			.blog-hero .container {
				position: relative;
				z-index: 2;
			}
			
			.blog-hero h1 {
				font-size: 3rem;
				font-weight: 700;
				color: white;
				margin-bottom: 1rem;
				text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
			}
			
			.blog-hero h2 {
				font-size: 1.25rem;
				color: rgba(255, 255, 255, 0.9);
				font-weight: 400;
				margin-bottom: 0;
				text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
			}
			
			.blog-grid {
				padding: 60px 0;
			}
			
			.blog-card {
				background: white;
				overflow: hidden;
				box-shadow: 0 4px 20px rgba(0,0,0,0.08);
				transition: all 0.3s ease;
				text-decoration: none;
				color: inherit;
				display: flex;
				flex-direction: column;
				height: 100%;
				margin-bottom: 30px;
			}
			
			.blog-card:hover {
				transform: translateY(-5px);
				box-shadow: 0 8px 30px rgba(0,0,0,0.15);
				text-decoration: none;
				color: inherit;
			}
			
			.blog-card-image {
				width: 100%;
				height: 220px;
				object-fit: cover;
				background: #f8f9fa;
			}
			
			.blog-card-placeholder {
				width: 100%;
				height: 250px;
				background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
				display: flex;
				align-items: center;
				justify-content: center;
				color: #6c757d;
				font-size: 0.9rem;
			}
			
			.blog-card-body {
				padding: 30px;
				display: flex;
				flex-direction: column;
				position: relative;
				flex-grow: 1;
			}
			
			.blog-card-title {
				font-size: 1.4rem;
				font-weight: 600;
				color: #212529;
				margin-bottom: 15px;
				line-height: 1.3;
			}
			
			.blog-card-excerpt {
				color: #6c757d;
				font-size: 1rem;
				line-height: 1.6;
				margin-bottom: 20px;
				flex-grow: 1;
			}
			
			.blog-card-read-more {
				color: #000;
				font-weight: 500;
				font-size: 0.95rem;
				display: flex;
				align-items: center;
				justify-content: space-between;
			}
			
			.blog-card-read-more::after {
				content: '→';
				font-size: 1.1rem;
				margin-left: 8px;
			}
			
			.blog-card:hover .blog-card-read-more {
				color: #333;
			}
			
			.no-results {
				text-align: center;
				padding: 80px 0;
				color: #6c757d;
			}
			
			.no-results h5 {
				font-size: 1.5rem;
				margin-bottom: 15px;
				color: #495057;
			}
			
			.no-results p {
				font-size: 1.1rem;
				margin-bottom: 0;
			}
			
			@media (max-width: 768px) {
				.blog-hero {
					padding: 60px 0 40px;
				}
				
				.blog-hero h1 {
					font-size: 2.5rem;
				}
				
				.blog-hero h2 {
					font-size: 1.1rem;
				}
				
				.blog-grid {
					padding: 40px 0;
				}
				
				.blog-card-body {
					padding: 25px;
				}
				
				.blog-card-title {
					font-size: 1.25rem;
				}
				
				.blog-card-image,
				.blog-card-placeholder {
					height: 200px;
				}
			}
		</style>

		<?php include 'dist/inc/favicon.php'; ?>
	</head>

	<body>
		<?php require 'dist/inc/nav-inner.php'; ?>

		<main>
			<!-- Blog Hero Section -->
			<section class="blog-hero">
				<div class="blog-hero-bg"></div>
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-lg-8 text-center">
							<h1><?= $lan['bl']['h1'] ?></h1>
							<h2><?= $lan['bl']['h2'] ?></h2>
						</div>
					</div>
				</div>
			</section>

			<!-- Blog Grid Section -->
			<section class="blog-grid">
				<div class="container">
					<?php if(!empty($site->Blog)): ?>
					<div class="row">
						<?php foreach($site->Blog as $bl): ?>
						<div class="col-lg-4 col-md-6 mb-4">
							<a href="cng_blog_post.php?bid=<?= $bl->blog_id ?>" class="blog-card">
								<?php if($bl->main_img != ''): ?>
								<img src="adm/uploads/blog/main/<?= $bl->main_img ?>" 
									 alt="<?= htmlspecialchars($bl->title) ?>" 
									 class="blog-card-image">
								<?php else: ?>
								<div class="blog-card-placeholder">
									<span>No Image Available</span>
								</div>
								<?php endif; ?>
								
								<div class="blog-card-body">
									<h3 class="blog-card-title"><?= htmlspecialchars($bl->title) ?></h3>
									<p class="blog-card-excerpt">
										<?php
										$excerpt = strip_tags($bl->content);
										echo htmlspecialchars(strlen($excerpt) > 150 ? substr($excerpt, 0, 150) . '...' : $excerpt);
										?>
									</p>
									<span class="blog-card-read-more"><?= $lan['bl']['read'] ?></span>
								</div>
							</a>
						</div>
						<?php endforeach; ?>
					</div>
					<?php else: ?>
					<div class="no-results">
						<h5><?= $lan['bl']['h5'] ?></h5>
						<p><?= $lan['bl']['p'] ?></p>
					</div>
					<?php endif; ?>
				</div>
			</section>
		</main>

		<?php require 'dist/inc/foot.php'; ?>


		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
	</body>
</html>