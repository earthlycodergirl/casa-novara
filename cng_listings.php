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

	<?php require 'dist/inc/nav-inner.php';?>
<!-- Listings filter ribbon -->
<section class="listings-filter" aria-label="Search and filters">
	<div class="filter-ribbon">
		<div class="container-fluid">
			<div class="filter-row">
				<!-- Search box -->
				<div class="filter-item filter-search">
					<div class="search-input-wrap">
						<svg class="search-icon" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zM10 15a5 5 0 110-10 5 5 0 010 10z"></path></svg>
						<input id="ls-search" class="form-control" type="text" placeholder="Search locations, neighborhoods, or keywords" autocomplete="off">
					</div>

					<div class="search-suggestions" aria-hidden="true">
						<ul></ul>
					</div>
				</div>

				<!-- For Sale / Rent -->
				<div class="filter-item filter-dropdown" data-filter="sale">
					<button class="btn btn-outline-secondary btn-filter" type="button">For sale</button>
					<div class="dropdown-panel">
						<ul>
							<li data-value="sale">For sale</li>
							<li data-value="rent">For rent</li>
						</ul>
					</div>
				</div>

				<!-- Price range -->
				<div class="filter-item filter-dropdown" data-filter="price">
					<button class="btn btn-outline-secondary btn-filter" type="button">Any Price</button>
					<div class="dropdown-panel">
						<ul>
							<li data-value="any">Any Price</li>
							<li data-value="0-100k">Under $100k</li>
							<li data-value="100k-300k">$100k–$300k</li>
							<li data-value="300k-600k">$300k–$600k</li>
							<li data-value="600k+">$600k+</li>
						</ul>
					</div>
				</div>

				<!-- Property types -->
				<div class="filter-item filter-dropdown filter-types" data-filter="types">
					<button class="btn btn-outline-secondary btn-filter" type="button">Property Types</button>
					<div class="dropdown-panel types-panel">
						<div class="types-grid">
							<!-- 9 items 3x3 -->
							<label class="type" data-value="Residential"><input type="checkbox"><span class="type-icon">🏠</span><span class="type-label">Residential</span></label>
							<label class="type" data-value="Townhomes"><input type="checkbox"><span class="type-icon">🏘️</span><span class="type-label">Townhomes</span></label>
							<label class="type" data-value="Co-op"><input type="checkbox"><span class="type-icon">🏢</span><span class="type-label">Co-op</span></label>
							<label class="type" data-value="Multi-family"><input type="checkbox"><span class="type-icon">👥</span><span class="type-label">Multi-family</span></label>
							<label class="type" data-value="Condos"><input type="checkbox"><span class="type-icon">🏬</span><span class="type-label">Condos</span></label>
							<label class="type" data-value="Commercial"><input type="checkbox"><span class="type-icon">🏢</span><span class="type-label">Commercial</span></label>
							<label class="type" data-value="Manufactured"><input type="checkbox"><span class="type-icon">🚚</span><span class="type-label">Manufactured</span></label>
							<label class="type" data-value="Land"><input type="checkbox"><span class="type-icon">🌾</span><span class="type-label">Land</span></label>
							<label class="type" data-value="Other"><input type="checkbox"><span class="type-icon">⋯</span><span class="type-label">Other</span></label>
						</div>
						<div class="types-actions">
							<button class="btn btn-sm btn-primary btn-apply">Apply</button>
							<button class="btn btn-sm btn-link btn-clear">Clear</button>
						</div>
					</div>
				</div>

				<!-- Beds -->
				<div class="filter-item filter-dropdown" data-filter="beds">
					<button class="btn btn-outline-secondary btn-filter" type="button">All Beds</button>
					<div class="dropdown-panel pills-panel">
						<ul>
							<li><label class="pill"><input type="radio" name="beds" value="any" checked>All beds</label></li>
							<li><label class="pill"><input type="radio" name="beds" value="1">1</label></li>
							<li><label class="pill"><input type="radio" name="beds" value="2">2</label></li>
							<li><label class="pill"><input type="radio" name="beds" value="3">3</label></li>
							<li><label class="pill"><input type="radio" name="beds" value="4">4</label></li>
							<li><label class="pill"><input type="radio" name="beds" value="5+">5+</label></li>
						</ul>
					</div>
				</div>

				<!-- Baths -->
				<div class="filter-item filter-dropdown" data-filter="baths">
					<button class="btn btn-outline-secondary btn-filter" type="button">All Baths</button>
					<div class="dropdown-panel pills-panel">
						<ul>
							<li><label class="pill"><input type="radio" name="baths" value="any" checked>All baths</label></li>
							<li><label class="pill"><input type="radio" name="baths" value="1">1</label></li>
							<li><label class="pill"><input type="radio" name="baths" value="2">2</label></li>
							<li><label class="pill"><input type="radio" name="baths" value="3">3</label></li>
							<li><label class="pill"><input type="radio" name="baths" value="4">4</label></li>
							<li><label class="pill"><input type="radio" name="baths" value="5+">5+</label></li>
						</ul>
					</div>
				</div>

				<!-- Mobile toggle -->
				<div class="filter-item filter-toggle d-md-none">
					<button class="btn btn-outline-secondary btn-filter" type="button" data-action="toggle-filters">Filters</button>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Bootstrap + optional JS (matching index.php) -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

<!-- Interactive behaviour for the listings filter -->
<script>
(function(){
	const root = document.querySelector('.listings-filter');
	if(!root) return;

	// Search suggestions
	const suggestions = ["Playa del Carmen","Tulum","Cancún","Puerto Morelos","Valladolid","Merida","Chichen Itza"];
	const searchInput = root.querySelector('#ls-search');
	const suggBox = root.querySelector('.search-suggestions');
	const suggList = suggBox.querySelector('ul');

	function renderSuggestions(filter){
		const items = suggestions.filter(s => s.toLowerCase().includes(filter.toLowerCase()));
		suggList.innerHTML = items.map(i=>`<li class="sugg-item" role="option">${i}</li>`).join('') || '<li class="sugg-empty">No results</li>';
	}

	searchInput.addEventListener('input', function(e){ renderSuggestions(e.target.value); suggBox.setAttribute('aria-hidden','false'); suggBox.style.display='block'; });
	searchInput.addEventListener('focus', function(){ renderSuggestions(searchInput.value||''); suggBox.setAttribute('aria-hidden','false'); suggBox.style.display='block'; });
	document.addEventListener('click', function(e){ if(!root.contains(e.target)) { suggBox.style.display='none'; closeAllDropdowns(); } });

	suggList.addEventListener('click', function(e){ const li = e.target.closest('li'); if(!li) return; if(li.classList.contains('sugg-empty')) return; searchInput.value = li.textContent; suggBox.style.display='none'; });

	// Generic dropdown behavior
	const dropdowns = Array.from(root.querySelectorAll('.filter-dropdown'));
	function closeAllDropdowns(){ dropdowns.forEach(d=> d.classList.remove('open')); }

	dropdowns.forEach(d => {
		const btn = d.querySelector('.btn-filter');
		const panel = d.querySelector('.dropdown-panel');
		btn.addEventListener('click', function(e){ const isOpen = d.classList.toggle('open'); dropdowns.filter(x=>x!==d).forEach(x=>x.classList.remove('open')); });

		// specific behaviors
		const filter = d.dataset.filter;
		if(filter === 'sale'){
			panel.addEventListener('click', function(e){ const li = e.target.closest('li'); if(!li) return; const val = li.dataset.value; btn.textContent = li.textContent; d.classList.remove('open'); });
		}
		if(filter === 'price'){
			panel.addEventListener('click', function(e){ const li = e.target.closest('li'); if(!li) return; btn.textContent = li.textContent; d.classList.remove('open'); });
		}
		if(filter === 'types'){
			const apply = d.querySelector('.btn-apply');
			const clear = d.querySelector('.btn-clear');
			const checkboxes = Array.from(d.querySelectorAll('.types-grid input[type="checkbox"]'));
			apply.addEventListener('click', function(){ const selected = checkboxes.filter(i=>i.checked).map(i=> i.closest('.type').dataset.value); btn.textContent = selected.length ? `${selected.length} types` : 'Property Types'; d.classList.remove('open'); });
			clear.addEventListener('click', function(){ checkboxes.forEach(c=> c.checked=false); btn.textContent = 'Property Types'; });
		}
		if(filter === 'beds' || filter === 'baths'){
			panel.addEventListener('click', function(e){ const li = e.target.closest('label'); if(!li) return; const input = li.querySelector('input'); if(!input) return; setTimeout(()=>{ const val = input.value; btn.textContent = (filter==='beds' ? (val==='any' ? 'All Beds' : val+' bed'+(val==='1'?'':'s')) : (val==='any' ? 'All Baths' : val+' bath'+(val==='1'?'':'s'))); d.classList.remove('open'); },20); });
		}
	});

	// Mobile toggle to expand/collapse filters
	const mobileToggle = root.querySelector('[data-action="toggle-filters"]');
	if(mobileToggle){ mobileToggle.addEventListener('click', function(){ root.classList.toggle('expanded'); }); }

})();
</script>

</body>
</html>