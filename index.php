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
	</head>

	<body>
        <!-- HERO IMAGE -->
		<header class="hero">
			<!-- Overlay navigation -->
			<nav class="overlay-nav">
				<?php require 'dist/inc/nav.php'; ?>
			</nav>


			<div class="container hero-inner">
				<div class="row">
					<div class="col-12">
						<h1 class="hero-title">Live your life in Paradise</h1>
						<p class="hero-text">Exceptional properties for those who live exceptionally.</p>

						<div class="d-flex justify-content-center gap-3 hero-buttons">
							<a href="#" class="btn btn-dark btn-lg">Find Properties</a>
							<a href="#" class="btn btn-outline-light btn-lg">Contact Expert</a>
						</div>
					</div>
				</div>
			</div>

			<!-- bottom three columns -->
			<div class="hero-bottom">
				<div class="container">
					<div class="row align-items-end">
						<div class="col-12 col-md-3 col-xl-2">
							<div class="big-text text-white">Elevating Lifestyles with Exceptional Living.</div>
						</div>

						<div class="col-12 col-md-5 col-xl-6">
							<div class="body-text text-white">Experience luxury and sophistication with properties crafted for unparallel comfort and exclusivity.</div>
						</div>

						<div class="col-12 col-md-4">
							<div class="d-flex justify-content-end">
								<!-- Inline SVG arrow that bounces -->
								<svg class="arrow bounce" fill="#ffffff" height="105px" width="105px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M256,0C114.88,0,0,114.88,0,256s114.88,256,256,256s256-114.88,256-256S397.12,0,256,0z M256,490.667 c-129.387,0-234.667-105.28-234.667-234.667S126.613,21.333,256,21.333S490.667,126.613,490.667,256S385.387,490.667,256,490.667 z"></path> <path d="M365.76,280.533l-99.093,99.093V107.093c0-5.333-3.84-10.133-9.067-10.88c-6.613-0.96-12.267,4.16-12.267,10.56v272.96 l-99.093-99.2c-4.267-4.053-10.987-3.947-15.04,0.213c-3.947,4.16-3.947,10.667,0,14.827l117.333,117.333 c4.16,4.16,10.88,4.16,15.04,0l117.333-117.333c4.053-4.267,3.947-10.987-0.213-15.04 C376.533,276.587,369.92,276.587,365.76,280.533z"></path> </g> </g> </g> </g></svg>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>

        <!-- MAIN CONTENT -->
        <div class="container-fluid intro-text">
            <div class="row">
                <div class="col-md-7">
                    <div class="info-sec">
                    <h4>About us</h4>
                    <h2>Welcome to CNG where your home in paradise is just a click away.</h2>
                    <div class="row">
                        <div class="col-md-3">
                            <p>Your Life.<br>Your dream.</p>
                        </div>
                        <div class="col-md-9">
                            <p>Casa Novara is a leading property management company that specializes in providing exceptional homes and experiences for our clients. We are committed to creating a safe, secure, and enjoyable living environment for our clients, and we strive to make every property a destination.</p>
                            <img src="dist/img/side-6.jpeg" alt="About Us" class="img-sm">
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="right-side">
                        <img src="dist/img/side-5.jpeg" alt="About Us" class="img-side">
                    </div>
                </div>
            </div>
        </div>

        <!-- Properties Slider-->
        <section class="properties-slider" aria-label="Featured properties">
        <div class="slider-header"> 
            <div class="row">
                <div class="col-md-4">
                    <h4 class="text-black">Find your next home</h4>
                    <h2>Hand Selected Properties</h2>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <p>Discover the top spots we have on register! This is just some holder text for the real thing when my mind can think way faster.</p>
                    <a href="listings" class="btn btn-outline-secondary btn-lg btn-round">View All Properties</a>
                </div>
            </div>
        </div>   
        <div class="slides">

        <?php for($i=1; $i<=10; $i++):?>
            <div class="slide">
            <a href="/property/123">
                <div class="card">
                <div class="card-image" style="background-image:url('dist/img/side-3.jpeg');"></div>
                <div class="card-body">
                    <p class="card-location">Playa del Carmen, MX</p>
                    <h3 class="card-title">Cozy Apartment</h3>

                    <div class="card-price">2 bed, 2 bath</div>
                </div>
                </div>
            </a>
            </div>
            <?php endfor;?>

            

            <!-- add more .slide blocks as required -->
        </div>
        
		<!-- controls: arrows and pager (generated/updated by JS) -->
		<div class="controls">
			<button class="arrow-btn arrow-prev" aria-label="Previous">&#9664;</button>
			<button class="arrow-btn arrow-next" aria-label="Next">&#9654;</button>
		</div>
		</section>

        <!-- Testimonials carousel -->
		<section class="testimonials" aria-label="Testimonials">
		  <div class="container">
		    <div class="testimonials-header d-flex align-items-center justify-content-between">
		      <div class="testimonials-heading">
		        <h4>What people say</h4>
		        <h2>Testimonials</h2>
		      </div>
		      <div class="testimonials-controls">
		        <button class="t-arrow t-prev" aria-label="Previous testimonial">‹</button>
		        <button class="t-arrow t-next" aria-label="Next testimonial">›</button>
		      </div>
		    </div>

		    <div class="testimonials-track">
		      <div class="t-slide">
		        <div class="testimonial-card">
		          <p class="testimonial-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent vitae eros eget tellus tristique bibendum.</p>
		          <div class="testimonial-footer">
		            <div class="reviewer-col reviewer-col--img"></div>
		            <div class="reviewer-col reviewer-col--info">
		              <div class="reviewer-name">Jane Doe</div>
		              <div class="stars"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
		            </div>
		          </div>
		        </div>
		      </div>

		      <div class="t-slide">
		        <div class="testimonial-card">
		          <p class="testimonial-text">"Excellent service and beautiful properties. Highly recommended for anyone relocating."</p>
		          <div class="testimonial-footer">
		            <div class="reviewer-col reviewer-col--img"></div>
		            <div class="reviewer-col reviewer-col--info">
		              <div class="reviewer-name">Carlos M.</div>
		              <div class="stars"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
		            </div>
		          </div>
		        </div>
		      </div>

		      <div class="t-slide">
		        <div class="testimonial-card">
		          <p class="testimonial-text">"A smooth experience from start to finish — professional and attentive."</p>
		          <div class="testimonial-footer">
		            <div class="reviewer-col reviewer-col--img"></div>
		            <div class="reviewer-col reviewer-col--info">
		              <div class="reviewer-name">Anna L.</div>
		              <div class="stars"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
		            </div>
		          </div>
		        </div>
		      </div>

		      <div class="t-slide">
		        <div class="testimonial-card">
		          <p class="testimonial-text">"Very helpful team — they understood our needs and found the perfect place."</p>
		          <div class="testimonial-footer">
		            <div class="reviewer-col reviewer-col--img"></div>
		            <div class="reviewer-col reviewer-col--info">
		              <div class="reviewer-name">Miguel R.</div>
		              <div class="stars"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
		            </div>
		          </div>
		        </div>
		      </div>
		    </div>
		  </div>
		</section>

		<!-- Split feature section: full-width edge-to-edge -->
		<section class="split-section" aria-label="Feature split">
		  <div class="split-wrap">
		    <div class="split-left">
		      <img src="dist/img/side-4.jpeg" alt="Feature image">
		    </div>
		    <div class="split-right">
		      <div class="split-content container">
		        <div class="split-header">
		          <h4>Our Approach</h4>
		          <h2>Effortless Property Ownership</h2>
		        </div>

		        <div class="features">
		          <div class="feature-row">
		            <div class="feature-num">1</div>
		            <div class="feature-body">
		              <h3>Personalized search</h3>
		              <p class="small">We match listings to your lifestyle and priorities for a precise fit. Use this website to find our recent listings or <a href="contact">contact one our experts</a> today.</p>
		            </div>
		          </div>

		          <div class="feature-row">
		            <div class="feature-num">2</div>
		            <div class="feature-body">
		              <h3>Verified listings</h3>
		              <p class="small">Every property is vetted to ensure accurate information and quality standards. Feel relaxed as you navigate through paradise.</p>
		            </div>
		          </div>

		          <div class="feature-row">
		            <div class="feature-num">3</div>
		            <div class="feature-body">
		              <h3>Dedicated support</h3>
		              <p class="small">From viewing to move-in, our team provides attentive support every step of the way.</p>
		            </div>
		          </div>
		        </div>
		      </div>
		    </div>
		  </div>
		</section>

		<section class="logo-footer">
            <img src="dist/img/logo-black.png" class="img-fluid logo-footer-img" alt="Logo" />
        </section>
      <?php require 'dist/inc/foot.php' ?>
		


		<!-- Bootstrap + optional JS (matching index.php) -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

		<!-- Properties slider script: transform-based track (no native horizontal scroll, no dots) -->
		<script>
		(function(){
		  const slider = document.querySelector('.properties-slider');
		  if(!slider) return;
		  const track = slider.querySelector('.slides');
		  const slides = Array.from(slider.querySelectorAll('.slide'));
		  const prevBtn = slider.querySelector('.arrow-prev');
		  const nextBtn = slider.querySelector('.arrow-next');
		  let currentIndex = 0;
		  const autoplayDelay = 4000;
		  let autoplayId = null;
		  let slideWidth = 0;
		  let visibleCount = 1;

		  // compute responsive slide size & visible count
		  function recalc(){
		    const containerW = slider.clientWidth;
		    if(window.matchMedia('(min-width:1200px)').matches){ visibleCount = 5; }
		    else if(window.matchMedia('(min-width:600px)').matches){ visibleCount = 4; }
		    else { visibleCount = 1.5; }
		    slideWidth = Math.floor(containerW / visibleCount);
		    slides.forEach(s=>{ s.style.minWidth = slideWidth + 'px'; });
		  }

		  // scroll to slide index (uses native smooth scroll)
		  function scrollToIndex(index){
		    index = Math.max(0, Math.min(index, slides.length - Math.ceil(visibleCount)));
		    currentIndex = index;
		    const left = index * slideWidth;
		    track.scrollTo({ left: left, behavior: 'smooth' });
		  }

		  function next(){ scrollToIndex(Math.min(currentIndex + 1, slides.length - Math.ceil(visibleCount))); }
		  function prev(){ scrollToIndex(Math.max(currentIndex - 1, 0)); }

		  if(nextBtn) nextBtn.addEventListener('click', ()=>{ stopAutoplay(); next(); });
		  if(prevBtn) prevBtn.addEventListener('click', ()=>{ stopAutoplay(); prev(); });

		  // pause on pointer interactions
		  ['pointerenter','pointerdown','touchstart'].forEach(evt=> slider.addEventListener(evt, stopAutoplay, {passive:true}));

		  function startAutoplay(){ stopAutoplay(); autoplayId = setInterval(()=>{ next(); }, autoplayDelay); }
		  function stopAutoplay(){ if(autoplayId){ clearInterval(autoplayId); autoplayId = null; } }

		  // keyboard nav
		  slider.addEventListener('keydown', function(e){ if(e.key === 'ArrowLeft') prev(); if(e.key === 'ArrowRight') next(); });

		  // make anchors focusable for accessibility
		  slides.forEach(s=>{ const a = s.querySelector('a'); if(a) a.setAttribute('tabindex','0'); });

		  // update currentIndex based on scroll position
		  let scrollTimer = null;
		  track.addEventListener('scroll', function(){
		    if(scrollTimer) clearTimeout(scrollTimer);
		    scrollTimer = setTimeout(()=>{
		      const left = track.scrollLeft;
		      const idx = Math.round(left / slideWidth);
		      currentIndex = Math.max(0, Math.min(idx, slides.length - 1));
		    }, 80);
		  }, { passive: true });

		  // recompute on resize
		  let resizeTimer = null;
		  window.addEventListener('resize', function(){ clearTimeout(resizeTimer); resizeTimer = setTimeout(recalc, 120); });

		  // initial layout
		  recalc();
		  startAutoplay();
		})();
		</script>

		<script>
		// Testimonials simple controls (native scroll for touch)
		(function(){
		  const track = document.querySelector('.testimonials-track');
		  if(!track) return;
		  const slides = Array.from(track.querySelectorAll('.t-slide'));
		  const prev = document.querySelector('.t-prev');
		  const next = document.querySelector('.t-next');
		  let slideW = slides[0] ? slides[0].getBoundingClientRect().width : track.clientWidth;
		  function recalc(){ slideW = slides[0] ? slides[0].getBoundingClientRect().width : track.clientWidth; }
		  window.addEventListener('resize', function(){ setTimeout(recalc, 120); });
		  if(next) next.addEventListener('click', ()=>{ track.scrollBy({ left: Math.round(slideW), behavior:'smooth' }); });
		  if(prev) prev.addEventListener('click', ()=>{ track.scrollBy({ left: -Math.round(slideW), behavior:'smooth' }); });
		})();
		</script>

	</body>
</html>

