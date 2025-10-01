// Map + Listings integration script
// Uses Leaflet (https://leafletjs.com) — simple, free, and well-documented.
(function(){
    // wait for DOM
    document.addEventListener('DOMContentLoaded', function(){
        const listColumn = document.getElementById('list-column');
        const mapColumn = document.getElementById('map-column');
        const mapEl = document.getElementById('mapid');
        if(!mapEl) return;

        // compute actual nav/filter heights and set CSS variables so offsets match runtime DOM
        function updateLayoutVars(){
            // default fallbacks
            const root = document.documentElement;
            const nav = document.querySelector('.navbar');
            const filter = document.querySelector('.listings-filter');
            const navH = nav ? Math.ceil(nav.getBoundingClientRect().height) : 72;
            const filterH = filter ? Math.ceil(filter.getBoundingClientRect().height) : 64;
            const extra = 18; // padding matching previous behavior
            root.style.setProperty('--cng-nav-height', navH + 'px');
            root.style.setProperty('--cng-filter-height', filterH + 'px');
            root.style.setProperty('--cng-extra-padding', extra + 'px');
            root.style.setProperty('--cng-top-stack', `calc(${navH}px + ${filterH}px + ${extra}px)`);
        }

        // run layout update before map initialization so CSS variables affect initial layout
        updateLayoutVars();
        window.addEventListener('resize', function(){ updateLayoutVars(); setTimeout(function(){ if(window.map) window.map.invalidateSize(); }, 180); });
        window.addEventListener('orientationchange', function(){ setTimeout(function(){ updateLayoutVars(); if(window.map) window.map.invalidateSize(); }, 220); });

        // initialize map centered on Yucatan Peninsula
        const map = L.map('mapid', {scrollWheelZoom:false}).setView([20.8, -88.0], 7);

        // OpenStreetMap tiles (free)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // ensure global reference for resize handlers
        window.map = map;

        // robust resize/invalidate sequence after tiles and layout settle — fixes initial quarter-height rendering
        map.whenReady(function(){
            try{ map.invalidateSize(true); }catch(e){}
            // also run in the next paint
            requestAnimationFrame(function(){ try{ map.invalidateSize(true); }catch(e){} });
            // delayed retries to catch late layout shifts (fonts, images, CSS rules)
            setTimeout(function(){ try{ map.invalidateSize(true); }catch(e){} }, 250);
            setTimeout(function(){ try{ map.invalidateSize(true); }catch(e){} }, 700);
        });

        // Collect property cards
        const cards = Array.from(document.querySelectorAll('.property-card'));
        const markers = [];

        function createPopupContent(card){
            // capture original link if present on the card so we can expose a CTA in the popup
            const originalHref = card.getAttribute('href') || card.dataset.url || (card.querySelector && (card.querySelector('a') ? card.querySelector('a').getAttribute('href') : null)) || null;
            // clone the card element and strip interactive bits except for the explicit CTA we add
            const clone = card.cloneNode(true);
            clone.style.width = '280px';
            clone.classList.add('map-popup-card');
            // small style adjustments
            const img = clone.querySelector('.prop-image');
            if(img){
                img.style.backgroundSize = 'cover';
                img.style.height = '120px';
                img.style.borderRadius = '0 0 0 0';
            }
            // price bolding
            const price = clone.querySelector('.prop-price');
            if(price) price.style.fontWeight = '700';
            // neutralize internal anchors so they don't navigate unexpectedly; we'll add a clear CTA below
            clone.querySelectorAll('a').forEach(a => { a.removeAttribute('href'); a.style.textDecoration = 'none'; a.style.color = '#000'; });

            // if the original card has a link, add a clear CTA that preserves site styling
            if(originalHref){
                try{
                    const ctaWrap = document.createElement('div');
                    ctaWrap.style.padding = '10px';
                    ctaWrap.style.textAlign = 'center';
                    const cta = document.createElement('a');
                    cta.href = originalHref;
                    cta.className = 'btn btn-sm map-popup-cta';
                    cta.textContent = 'View listing';
                    // small inline adjustments to keep popup compact
                    cta.style.display = 'inline-block';
                    cta.style.textDecoration = 'none';
                    ctaWrap.appendChild(cta);
                    clone.appendChild(ctaWrap);
                }catch(e){
                    // nothing critical if adding CTA fails
                }
            }

            return clone.outerHTML;
        }

        // Replace marker creation & popup code with this robust version

        function createMarkerForCard(card) {
          const lat = parseFloat(card.dataset.lat);
          const lng = parseFloat(card.dataset.lng);
          if (isNaN(lat) || isNaN(lng)) return null;

          // Use circleMarker for blue fill and white border
          const marker = L.circleMarker([lat, lng], {
            radius: 8,
            fillColor: '#0d6efd', // bootstrap primary blue
            fillOpacity: 1,
            color: '#ffffff',     // white stroke
            weight: 2,
            pane: 'markerPane'
          });

          // Build popup content by cloning card, but keep a details link
          const clone = card.cloneNode(true);

          // Remove any anchor behavior on clone (so the popup doesn't navigate)
          const anchor = clone.querySelector('a');
          let detailUrl = '#';
          if (anchor) {
            detailUrl = anchor.href;
            // keep visual text but remove href to prevent navigation inside popup clone
            anchor.removeAttribute('href');
            anchor.style.textDecoration = 'none';
            anchor.style.color = '#000';
          }

          // Ensure image height in popup
          const img = clone.querySelector('.prop-image, .card-image');
          if (img) {
            img.style.height = '200px';
            img.style.backgroundSize = 'cover';
            img.style.backgroundPosition = 'center';
          }

          // Force price bold and remove underlines from any links
          const priceEl = clone.querySelector('.card-price, .prop-price');
          if (priceEl) priceEl.style.fontWeight = '700';

          // Create a view details link appended to popup
          const detailsLink = document.createElement('a');
          detailsLink.className = 'view-details';
          detailsLink.href = detailUrl;
          detailsLink.textContent = 'View details';
          detailsLink.style.display = 'inline-block';
          detailsLink.style.marginTop = '8px';
          detailsLink.style.color = '#000';
          detailsLink.style.textDecoration = 'none';
          detailsLink.style.fontWeight = '600';

          // Wrap clone into a container and append link
          const wrapper = document.createElement('div');
          wrapper.className = 'popup-card';
          wrapper.appendChild(clone);
          wrapper.appendChild(detailsLink);

          marker.bindPopup(wrapper, { maxWidth: 320, className: 'cng-map-popup' });

          // store reference to original card element
          marker._cardNode = card;

          return marker;
        }

        // create markers for each card with lat/lng data
        cards.forEach(card => {
            const lat = parseFloat(card.getAttribute('data-lat'));
            const lng = parseFloat(card.getAttribute('data-lng'));
            if(Number.isFinite(lat) && Number.isFinite(lng)){
                const marker = L.marker([lat,lng]).addTo(map);
                marker.propertyCard = card; // link back
                marker.bindPopup(createPopupContent(card), {maxWidth: 300});
                markers.push(marker);

                // when marker clicked, open popup and ensure list item is visible
                marker.on('click', function(){
                    // scroll list to the card
                    const el = marker.propertyCard;
                    if(el && el.parentElement){
                        el.parentElement.scrollIntoView({behavior:'smooth', block:'center'});
                    }
                });
            }
        });

        

        // filtering: reuse existing logic from page but sync marker visibility
        function getFilters(){
            const filters = {};
            const searchInput = document.querySelector('#ls-search');
            filters.search = searchInput ? searchInput.value.trim().toLowerCase() : '';
            const saleDropdown = document.querySelector('.filter-dropdown[data-filter="sale"]');
            filters.listing = saleDropdown ? saleDropdown.querySelector('.btn-filter').textContent.trim().toLowerCase() : '';
            if(filters.listing === 'for sale') filters.listing = 'sale';
            if(filters.listing === 'for rent') filters.listing = 'rent';
            const priceDropdown = document.querySelector('.filter-dropdown[data-filter="price"]');
            filters.price_range = priceDropdown ? priceDropdown.querySelector('.btn-filter').textContent.trim() : '';
            const typesDropdown = document.querySelector('.filter-dropdown[data-filter="types"]');
            filters.types = [];
            if(typesDropdown){ const checks = typesDropdown.querySelectorAll('.types-grid input[type="checkbox"]'); checks.forEach(c=>{ if(c.checked) filters.types.push(c.closest('.type').dataset.value); }); }
            const bedsDropdown = document.querySelector('.filter-dropdown[data-filter="beds"]');
            filters.beds = bedsDropdown ? bedsDropdown.querySelector('input[name="beds"]:checked')?.value || 'any' : 'any';
            const bathsDropdown = document.querySelector('.filter-dropdown[data-filter="baths"]');
            filters.baths = bathsDropdown ? bathsDropdown.querySelector('input[name="baths"]:checked')?.value || 'any' : 'any';
            return filters;
        }

        function matches(card, filters){
            const title = (card.dataset.title || card.querySelector('.prop-location')?.textContent || '').toLowerCase();
            const types = (card.dataset.types || '').toLowerCase();
            const location = (card.dataset.location || '').toLowerCase();
            if(filters.search){ const s = filters.search; if(!(title.includes(s) || types.includes(s) || location.includes(s))) return false; }
            if(filters.listing && ['sale','rent'].includes(filters.listing)){ if(card.dataset.listing !== filters.listing) return false; }
            if(filters.types && filters.types.length){ const cardTypes = (card.dataset.types || '').split(',').map(s=>s.trim()); const has = filters.types.some(t=> cardTypes.includes(t)); if(!has) return false; }
            if(filters.beds && filters.beds !== 'any'){ const cb = Number(card.dataset.beds||0); if(filters.beds === '5+'){ if(cb < 5) return false; } else if(cb !== Number(filters.beds)) return false; }
            if(filters.baths && filters.baths !== 'any'){ const cb = Number(card.dataset.baths||0); if(filters.baths === '5+'){ if(cb < 5) return false; } else if(cb !== Number(filters.baths)) return false; }
            return true;
        }

        function applyFilters(){
            const f = getFilters();
            let visible = 0;
            cards.forEach(c => {
                const show = matches(c, f);
                c.parentElement.style.display = show ? '' : 'none';
                if(show) visible++;
            });
            // sync markers
            markers.forEach(m => {
                const show = matches(m.propertyCard, f);
                if(show && !map.hasLayer(m)) m.addTo(map);
                if(!show && map.hasLayer(m)) map.removeLayer(m);
            });
            const countEl = document.querySelector('.results-number');
            const noEl = document.querySelector('.no-results');
            if(countEl) countEl.textContent = visible;
            if(noEl) noEl.style.display = visible ? 'none' : '';
        }

        // wire filter events
        const searchInput = document.querySelector('#ls-search'); if(searchInput) searchInput.addEventListener('input', function(){ setTimeout(applyFilters,50); });
        const panels = Array.from(document.querySelectorAll('.dropdown-panel')); panels.forEach(p=> p.addEventListener('click', function(){ setTimeout(applyFilters,50); }));
        document.querySelectorAll('.btn-apply').forEach(b=> b.addEventListener('click', function(){ setTimeout(applyFilters,60); }));
        document.querySelectorAll('.btn-clear').forEach(b=> b.addEventListener('click', function(){ setTimeout(applyFilters,60); }));
        document.querySelectorAll('input[name="beds"], input[name="baths"]').forEach(i=> i.addEventListener('change', applyFilters));

    // respond to custom event dispatched by filters JS
    document.addEventListener('filtersChanged', function(){ setTimeout(applyFilters, 40); });

        // view toggle buttons
        document.querySelectorAll('[data-view]').forEach(btn => {
            btn.addEventListener('click', function(){
                const v = btn.getAttribute('data-view');
                if(v === 'map'){
                    mapColumn.style.display = '';
                    listColumn.style.width = '50%';
                    setTimeout(function(){ map.invalidateSize(); }, 220);
                } else {
                    mapColumn.style.display = 'none';
                    listColumn.style.width = '100%';
                    setTimeout(function(){ map.invalidateSize(); }, 220);
                }
            });
        });

    // initial state: show both columns
    mapColumn.style.display = '';
    // ensure map invalidation after un-hiding column
    try{ map.invalidateSize(true); }catch(e){}
    // small delay too
    setTimeout(function(){ try{ map.invalidateSize(true); }catch(e){} }, 220);
    applyFilters();

        // when a property card is clicked, open the associated marker popup
        cards.forEach(c => c.addEventListener('click', function(e){ e.preventDefault(); const lat = parseFloat(c.getAttribute('data-lat')); const lng = parseFloat(c.getAttribute('data-lng')); const m = markers.find(mk => mk.getLatLng && mk.getLatLng().lat === lat && mk.getLatLng().lng === lng); if(m){ m.openPopup(); map.setView([lat,lng], 13); } }));

    });
})();

// Replace or update your view toggle handler so a CSS class is applied to toggle list-mode.
// This ensures CSS can react to the "List" view and show 4/3/1 columns per your request.
document.addEventListener('click', function (e) {
  const btn = e.target.closest('.view-toggle .btn');
  if (!btn) return;
  const view = btn.dataset.view;
  // toggle active state (existing UI code may already do this)
  document.querySelectorAll('.view-toggle .btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  // Add/remove global class for CSS to pick up
  if (view === 'list') {
    document.body.classList.add('list-mode');
    // hide map column if you have that behavior:
    document.querySelectorAll('.map-column').forEach(col => col.classList.remove('hidden'));
    // optional: expand list column to full width if needed by layout JS
    // your existing toggling code can remain; we only ensure the class is present.
  } else if (view === 'map') {
    document.body.classList.remove('list-mode');
  }

  // ensure map redraw if visible
  if (window.map && typeof window.map.invalidateSize === 'function') {
    setTimeout(() => window.map.invalidateSize(), 240);
  }
});
