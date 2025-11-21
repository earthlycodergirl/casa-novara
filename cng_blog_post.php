<?php
require('base.php');

// Get blog post ID from URL parameter
$blog_id = isset($_GET['bid']) ? (int)$_GET['bid'] : 0;

if($blog_id <= 0) {
    header('Location: blog.php');
    exit;
}

// Get the blog post from database
$getBlogPost = new SqlIt("SELECT * FROM site_blog WHERE blog_id = ? AND lang = ?", "select", array($blog_id, $lang));

if($getBlogPost->NumResults == 0) {
    header('Location: blog.php');
    exit;
}

$post = $getBlogPost->Response[0];

// Set page metadata
$page_title = $post->title . ' - Casa Novara Group Blog';
$page_description = substr(strip_tags($post->content), 0, 160);
$page_image = !empty($post->main_img) ? 'adm/uploads/blog/main/' . $post->main_img : 'dist/img/social.jpg';

?>
<!doctype html>
<html lang="<?= $lang ?>">
	<head>
      <base href="<?= $base_href ?>">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?= htmlspecialchars($page_title) ?></title>
		<meta name="description" content="<?= htmlspecialchars($page_description) ?>" />
		<meta name="robots" content="index, follow" />

		<!-- Open Graph / Facebook -->
		<meta property="og:type" content="article">
		<meta property="og:title" content="<?= htmlspecialchars($post->title) ?>">
		<meta property="og:description" content="<?= htmlspecialchars($page_description) ?>">
		<meta property="og:image" content="<?= $base_href . $page_image ?>">
		<meta property="og:url" content="<?= $base_href ?>real-estate-news/<?= $blog_id ?>">
		<meta property="og:site_name" content="Casa Novara Group">

		<!-- Twitter -->
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:title" content="<?= htmlspecialchars($post->title) ?>">
		<meta name="twitter:description" content="<?= htmlspecialchars($page_description) ?>">
		<meta name="twitter:image" content="<?= $base_href . $page_image ?>">

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
			/* Blog Post Specific Styles */
			.blog-hero {
				position: relative;
				background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
				padding: 60px 0 40px;
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
			
			.blog-hero h1,
			.blog-hero .breadcrumb {
				color: white;
				text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
			}
			
			.blog-hero .breadcrumb a {
				color: rgba(255, 255, 255, 0.8);
			}
			
			.blog-hero .breadcrumb a:hover {
				color: white;
			}
			
			.blog-hero .breadcrumb-item.active {
				color: rgba(255, 255, 255, 0.9);
			}
			
			.blog-content {
				max-width: 800px;
				margin: 0 auto;
				padding: 40px 20px;
			}
			
			.blog-meta {
				color: #6c757d;
				font-size: 0.9rem;
				margin-bottom: 0px;
				padding-bottom: 0px;
			}
			
			.blog-meta .author {
				font-weight: 500;
				color: #495057;
			}
			
			.blog-image {
				width: 100%;
				height: 400px;
				object-fit: cover;
				border-radius: 8px;
				box-shadow: 0 4px 20px rgba(0,0,0,0.1);
				margin: 30px 0;
			}
			
			.blog-text {
				font-size: 1.1rem;
				line-height: 1.7;
				color: #333;
			}
			
			.blog-text h1, .blog-text h2, .blog-text h3, .blog-text h4, .blog-text h5, .blog-text h6 {
				margin-top: 2rem;
				margin-bottom: 1rem;
				color: #212529;
			}
			
			.blog-text p {
				margin-bottom: 1.5rem;
			}
			
			.blog-text img {
				max-width: 100%;
				height: auto;
				border-radius: 4px;
				margin: 20px 0;
			}
			
			.article-navigation {
				margin-top: 50px;
				padding-top: 40px;
				border-top: 2px solid #e9ecef;
			}
			
			.nav-article {
				display: block;
				padding: 25px;
				background: #f8f9fa;
				border-radius: 10px;
				text-decoration: none;
				color: inherit;
				transition: all 0.3s ease;
				border: 1px solid #e9ecef;
				height: 100%;
				min-height: 140px;
			}
			
			.nav-article:hover {
				background: #ffffff;
				text-decoration: none;
				color: inherit;
				transform: translateY(-2px);
				box-shadow: 0 5px 20px rgba(0,0,0,0.1);
			}
			
			.nav-article-direction {
				font-size: 0.85rem;
				color: #6c757d;
				margin-bottom: 8px;
				font-weight: 500;
				text-transform: uppercase;
				letter-spacing: 0.5px;
			}
			
			.nav-article-title {
				font-size: 1.2rem;
				font-weight: 600;
				color: #212529;
				margin-bottom: 10px;
				line-height: 1.3;
			}
			
			.nav-article-excerpt {
				font-size: 0.9rem;
				color: #6c757d;
				line-height: 1.4;
			}
			
			.nav-article--prev .nav-article-direction {
				text-align: left;
			}
			
			.nav-article--next .nav-article-direction {
				text-align: right;
			}
			
			.nav-article--next .nav-article-title {
				text-align: right;
			}
			
			.nav-article--next .nav-article-excerpt {
				text-align: right;
			}
			
			.blog-nav {
				background: #f8f9fa;
				padding: 30px 0;
				margin-top: 50px;
				border-top: 1px solid #e9ecef;
			}
			
			.blog-nav .btn {
				padding: 12px 30px;
				font-weight: 500;
			}
			
			.related-posts {
				background: #222;
				padding: 50px 0;
			}
			
			.related-posts h3 {
				text-align: center;
				margin-bottom: 40px;
				color: #ececec;
            text-transform: uppercase;
            font-size: 18px;
            letter-spacing: 2px;
			}
			
			.post-card {
				background: white;
				border-radius: 8px;
				overflow: hidden;
				box-shadow: 0 2px 10px rgba(0,0,0,0.1);
				transition: transform 0.2s ease;
				text-decoration: none;
				color: inherit;
			}
			
			.post-card:hover {
				transform: translateY(-2px);
				text-decoration: none;
				color: inherit;
			}
			
			.post-card img {
				width: 100%;
				height: 200px;
				object-fit: cover;
			}
			
			.post-card-body {
				padding: 20px;
			}
			
			.post-card-title {
				font-size: 1.1rem;
				font-weight: 600;
				margin-bottom: 10px;
				color: #212529;
			}
			
			.post-card-excerpt {
				color: #6c757d;
				font-size: 0.9rem;
				line-height: 1.5;
			}
			
			@media (max-width: 768px) {
				.blog-hero {
					padding: 40px 0 30px;
				}
				
				.blog-content {
					padding: 30px 15px;
				}
				
				.blog-image {
					height: 250px;
				}
				
				.blog-text {
					font-size: 1rem;
				}
				
				.article-navigation {
					margin-top: 30px;
					padding-top: 30px;
				}
				
				.nav-article {
					margin-bottom: 20px;
					min-height: auto;
				}
				
				.nav-article--next .nav-article-direction,
				.nav-article--next .nav-article-title,
				.nav-article--next .nav-article-excerpt {
					text-align: left;
				}
			}
		</style>
	</head>

	<body>
		<?php require 'dist/inc/nav-inner.php'; ?>

		<main>
			<!-- Blog Hero Section -->
			<section class="blog-hero"<?php if(!empty($post->main_img)): ?> style="background-image: none;"<?php endif; ?>>
				<?php if(!empty($post->main_img)): ?>
				<div class="blog-hero-bg" style="background-image: url('adm/uploads/blog/main/<?= htmlspecialchars($post->main_img) ?>');"></div>
				<?php endif; ?>
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-lg-8">
							<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?= $link_prefix ?>/">Home</a></li>
								<li class="breadcrumb-item"><a href="<?= $link_prefix ?>/real-estate-news/">Blog</a></li>
								<li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($post->title) ?></li>
							</ol>
							</nav>
							<h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($post->title) ?></h1>
						</div>
					</div>
				</div>
			</section>

			<!-- Blog Content -->
			<section>
				<div class="container">
					<div class="blog-content">
						<!-- Blog Meta -->
						<div class="blog-meta">
							<div class="d-flex justify-content-between flex-wrap">
								<div>
									<span class="author">By <?= htmlspecialchars($post->author) ?></span>
								</div>
								<div>
									<time datetime="<?= date('Y-m-d', strtotime($post->posted)) ?>">
										<?= date('F j, Y', strtotime($post->posted)) ?>
									</time>
								</div>
							</div>
						</div>

						<!-- Featured Image -->
						<?php if(!empty($post->main_img)): ?>
						<img src="images/blog/main/<?= htmlspecialchars($post->main_img) ?>" 
							 alt="<?= htmlspecialchars($post->title) ?>" 
							 class="blog-image">
						<?php endif; ?>

						<!-- Blog Content -->
						<div class="blog-text">
							<?= $post->content ?>
						</div>

						<?php
						// Get next and previous articles
						$getPrevious = new SqlIt("SELECT * FROM site_blog WHERE lang = ? AND posted < ? ORDER BY posted DESC LIMIT 1", "select", array($lang, $post->posted));
						$getNext = new SqlIt("SELECT * FROM site_blog WHERE lang = ? AND posted > ? ORDER BY posted ASC LIMIT 1", "select", array($lang, $post->posted));
						
						$hasPrevious = $getPrevious->NumResults > 0;
						$hasNext = $getNext->NumResults > 0;
						
						if($hasPrevious || $hasNext):
						?>
						<!-- Next/Previous Article Navigation -->
						<div class="article-navigation">
							<div class="row">
								<?php if($hasPrevious): 
									$prevPost = $getPrevious->Response[0];
								?>
								<div class="col-md-6">
									<a href="real-estate-news/<?= $prevPost->blog_id ?>" class="nav-article nav-article--prev">
										<div class="nav-article-direction">← Previous Article</div>
										<div class="nav-article-title"><?= htmlspecialchars($prevPost->title) ?></div>
										<div class="nav-article-excerpt"><?= htmlspecialchars(substr(strip_tags($prevPost->content), 0, 100)) ?>...</div>
									</a>
								</div>
								<?php endif; ?>
								
								<?php if($hasNext): 
									$nextPost = $getNext->Response[0];
								?>
								<div class="col-md-6<?= !$hasPrevious ? ' offset-md-6' : '' ?>">
									<a href="real-estate-news/<?= $nextPost->blog_id ?>" class="nav-article nav-article--next">
										<div class="nav-article-direction">Next Article →</div>
										<div class="nav-article-title"><?= htmlspecialchars($nextPost->title) ?></div>
										<div class="nav-article-excerpt"><?= htmlspecialchars(substr(strip_tags($nextPost->content), 0, 100)) ?>...</div>
									</a>
								</div>
								<?php endif; ?>
							</div>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</section>

			<!-- Blog Navigation -->
			<section class="blog-nav">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<a href="blog.php" class="btn btn-outline-primary">
								← Back to Blog
							</a>
						</div>
						<div class="col-md-6 text-md-end mt-3 mt-md-0">
							<div class="d-flex justify-content-md-end gap-2">
								<button class="btn btn-outline-secondary btn-sm" onclick="shareFacebook()">
									Share on Facebook
								</button>
								<button class="btn btn-outline-secondary btn-sm" onclick="shareTwitter()">
									Share on X
								</button>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Related Posts -->
			<?php
			// Get related posts (other posts in same language, excluding current)
			$getRelated = new SqlIt("SELECT * FROM site_blog WHERE lang = ? AND blog_id != ? ORDER BY posted DESC LIMIT 3", "select", array($lang, $blog_id));
			if($getRelated->NumResults > 0):
			?>
			<section class="related-posts">
				<div class="container">
					<h3>Related Articles</h3>
					<div class="row">
						<?php foreach($getRelated->Response as $related): ?>
						<div class="col-md-4 mb-4">
							<a href="real-estate-news/<?= $related->blog_id ?>" class="post-card d-block">
								<?php if(!empty($related->main_img)): ?>
								<img src="images/blog/main/<?= htmlspecialchars($related->main_img) ?>" 
									 alt="<?= htmlspecialchars($related->title) ?>">
								<?php else: ?>
								<div style="height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #6c757d;">
									No Image
								</div>
								<?php endif; ?>
								<div class="post-card-body">
									<h5 class="post-card-title"><?= htmlspecialchars($related->title) ?></h5>
									<p class="post-card-excerpt"><?= htmlspecialchars(substr(strip_tags($related->content), 0, 120)) ?>...</p>
									<small class="text-muted"><?= date('M j, Y', strtotime($related->posted)) ?></small>
								</div>
							</a>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
			<?php endif; ?>
		</main>

		<?php require 'dist/inc/foot.php' ?>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
		
		<script>
			// Social sharing functions
			function shareFacebook() {
				const url = encodeURIComponent(window.location.href);
				window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
			}
			
			function shareTwitter() {
				const url = encodeURIComponent(window.location.href);
				const text = encodeURIComponent('<?= addslashes($post->title) ?>');
				window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
			}
		</script>
	</body>
</html>