// Filters UI behavior: search suggestions, dropdowns, types apply/clear, mobile toggle
(function(){
    document.addEventListener('DOMContentLoaded', function(){

        // ensure we have a reference to the search input and suggestions container
        const searchInput = document.getElementById('ls-search');
        const suggestionsWrap = document.querySelector('.search-suggestions');
        const suggestionsList = suggestionsWrap ? suggestionsWrap.querySelector('ul') : null;

        // Build a unique list of locations from rendered property cards
        const propertyCards = Array.from(document.querySelectorAll('.property-card'));
        const locations = Array.from(new Set(propertyCards.map(c => (c.dataset.location || '').trim()).filter(Boolean)));

        // Helper: escape html for safe insertion
        function escapeHtml(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

        // Update suggestion list (max 8)
        function updateSuggestions(q){
            if(!suggestionsList) return;
            const term = (q||'').trim().toLowerCase();
            const matches = term
                ? locations.filter(l => l.toLowerCase().includes(term)).slice(0,8)
                : locations.slice(0,8);
            suggestionsList.innerHTML = matches.map(m => `<li class="suggest-item" data-val="${escapeHtml(m)}">${escapeHtml(m)}</li>`).join('');
            suggestionsWrap.style.display = matches.length ? 'block' : 'none';
        }

        // Wire input events: filter as you type and show suggestions
        if(searchInput){
            searchInput.addEventListener('input', function(e){
                updateSuggestions(e.target.value);
                // notify filtersChanged for live filtering
                document.dispatchEvent(new Event('filtersChanged'));
            });
            // show suggestions on focus
            searchInput.addEventListener('focus', function(){ updateSuggestions(searchInput.value); });
        }

        // Clicking a suggestion sets the input and triggers filtering
        if(suggestionsList){
            suggestionsList.addEventListener('click', function(e){
                const li = e.target.closest('.suggest-item');
                if(!li) return;
                const val = li.dataset.val;
                searchInput.value = val;
                suggestionsWrap.style.display = 'none';
                // trigger filters update
                document.dispatchEvent(new Event('filtersChanged'));
            });
        }

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e){
            if(!suggestionsWrap) return;
            if(e.target === searchInput || suggestionsWrap.contains(e.target)) return;
            suggestionsWrap.style.display = 'none';
        });

        // Generic dropdown behavior
        const dropdowns = Array.from(root.querySelectorAll('.filter-dropdown'));
        function closeAllDropdowns(){ dropdowns.forEach(d=> d.classList.remove('open')); }

        dropdowns.forEach(d => {
            const btn = d.querySelector('.btn-filter');
            const panel = d.querySelector('.dropdown-panel');
            btn.addEventListener('click', function(e){
                const isOpen = d.classList.toggle('open');
                dropdowns.filter(x=>x!==d).forEach(x=>x.classList.remove('open'));

                // Ensure panel never overflows the viewport: set max-width and align
                if(isOpen && panel){
                    panel.style.boxSizing = 'border-box';
                    panel.style.maxWidth = (Math.max(240, window.innerWidth - 48)) + 'px';
                    // small delay to allow class=open to apply layout then measure
                    requestAnimationFrame(() => {
                        const rect = panel.getBoundingClientRect();
                        if(rect.right > window.innerWidth - 12){
                            // align panel to the right edge of the button/container
                            panel.style.left = 'auto';
                            panel.style.right = '0';
                        } else {
                            panel.style.right = 'auto';
                            panel.style.left = '0';
                        }
                    });
                } else if(panel) {
                    // reset inline positioning when closed
                    panel.style.left = '';
                    panel.style.right = '';
                    panel.style.maxWidth = '';
                }
            });

            const filter = d.dataset.filter;
            if(filter === 'sale'){
                panel.addEventListener('click', function(e){ const li = e.target.closest('li'); if(!li) return; const val = li.dataset.value || li.textContent.trim(); btn.textContent = li.textContent; btn.dataset.value = val; d.classList.remove('open'); document.dispatchEvent(new Event('filtersChanged')); });
            }
            if(filter === 'price'){
                panel.addEventListener('click', function(e){
                    const li = e.target.closest('li');
                    if(!li) return;
                    // set both user-facing text and machine-friendly data-range (from data-value or label)
                    btn.textContent = li.textContent;
                    btn.dataset.range = li.dataset.value || li.textContent.trim();
                    d.classList.remove('open');
                    document.dispatchEvent(new Event('filtersChanged'));
                });
            }

            // NEW: location dropdown support (makes dropdown location selections filter the list)
            if(filter === 'location'){
                panel.addEventListener('click', function(e){
                    const li = e.target.closest('li');
                    if(!li) return;
                    const val = li.dataset.value || li.textContent.trim();
                    btn.textContent = li.textContent;
                    btn.dataset.value = val;
                    d.classList.remove('open');
                    // populate search input as well so suggestions/UX stay consistent
                    if(searchInput) { searchInput.value = val; }
                    document.dispatchEvent(new Event('filtersChanged'));
                });
            }

            if(filter === 'types'){
                const apply = d.querySelector('.btn-apply');
                const clear = d.querySelector('.btn-clear');
                const checkboxes = Array.from(d.querySelectorAll('.types-grid input[type="checkbox"]'));
                apply.addEventListener('click', function(){ const selected = checkboxes.filter(i=>i.checked).map(i=> i.closest('.type').dataset.value); btn.textContent = selected.length ? `${selected.length} types` : 'Property Types'; d.classList.remove('open'); document.dispatchEvent(new Event('filtersChanged')); });
                clear.addEventListener('click', function(){ checkboxes.forEach(c=> c.checked=false); btn.textContent = 'Property Types'; document.dispatchEvent(new Event('filtersChanged')); });
            }
            if(filter === 'beds' || filter === 'baths'){
                // helper to refresh visual selected state on labels
                function refreshLabelSelected(){
                    const labels = Array.from(panel.querySelectorAll('label'));
                    labels.forEach(l=>{
                        const inp = l.querySelector('input');
                        l.classList.toggle('selected', !!(inp && inp.checked));
                    });
                }

                // init selected state on load
                refreshLabelSelected();

                panel.addEventListener('click', function(e){
                    const li = e.target.closest('label');
                    if(!li) return;
                    const input = li.querySelector('input');
                    if(!input) return;
                    // small timeout to allow native radio to toggle, then update UI
                    setTimeout(()=>{
                        const val = input.value;
                        btn.textContent = (filter==='beds' ? (val==='any' ? 'All Beds' : val+' bed'+(val==='1'?'':'s')) : (val==='any' ? 'All Baths' : val+' bath'+(val==='1'?'':'s')));
                        d.classList.remove('open');
                        refreshLabelSelected();
                        document.dispatchEvent(new Event('filtersChanged'));
                    },20);
                });
            }
        });

        // Mobile toggle to expand/collapse filters
        const mobileToggle = root.querySelector('[data-action="toggle-filters"]');
        if(mobileToggle){ mobileToggle.addEventListener('click', function(){ root.classList.toggle('expanded'); }); }

        // When search changes via suggestions or typing, notify filters changed
        if(searchInput) searchInput.addEventListener('input', function(){ updateSuggestions(searchInput.value); document.dispatchEvent(new Event('filtersChanged')); });

        // Apply filters: reads UI state and shows/hides .property-card elements
        function applyFilters(){
            // Use the column wrappers so the grid cells collapse/expand correctly
            const cols = Array.from(document.querySelectorAll('.property-col'));
            const searchVal = (searchInput && searchInput.value || '').trim().toLowerCase();

            const saleBtn = root.querySelector('[data-filter="sale"] .btn-filter');
            const saleVal = saleBtn ? (saleBtn.dataset.value || saleBtn.textContent.trim().toLowerCase()) : '';

            const priceBtn = root.querySelector('[data-filter="price"] .btn-filter');
            const priceRange = priceBtn ? (priceBtn.dataset.range || priceBtn.textContent.trim()) : '';

            const locationBtn = root.querySelector('[data-filter="location"] .btn-filter');
            const locationVal = locationBtn ? (locationBtn.dataset.value || locationBtn.textContent.trim()).toLowerCase() : '';

            const typesChecked = Array.from(root.querySelectorAll('[data-filter="types"] .types-grid input[type="checkbox"]:checked'))
                                      .map(i=> i.closest('.type').dataset.value);

            const bedsChecked = root.querySelector('[data-filter="beds"] input:checked');
            const bedsVal = bedsChecked ? bedsChecked.value : 'any';
            const bathsChecked = root.querySelector('[data-filter="baths"] input:checked');
            const bathsVal = bathsChecked ? bathsChecked.value : 'any';

            let visible = 0;
            cols.forEach(col => {
                const card = col.querySelector('.property-card');
                if(!card){ col.style.display = 'none'; return; }

                let ok = true;

                // Location or free-text search
                if(locationVal){
                    const cardLoc = (card.dataset.location || '').toLowerCase();
                    if(!cardLoc.includes(locationVal)) ok = false;
                } else if(searchVal){
                    const hay = ((card.dataset.location||'') + ' ' + (card.dataset.title||'') + ' ' + (card.dataset.type||'')).toLowerCase();
                    if(!hay.includes(searchVal)) ok = false;
                }

                // sale / rent
                if(saleVal && !/any/i.test(saleVal)){
                    const norm = saleVal.replace(/\s+/g,'').replace(/^for/i,'').trim();
                    if(card.dataset.listing && !card.dataset.listing.toLowerCase().includes(norm)) ok = false;
                }

                // price
                if(!cardMatchesPrice(card, priceRange)) ok = false;

                // types (OR match)
                if(typesChecked.length){
                    const cardTypes = (card.dataset.types||'').split(',').map(s=>s.trim());
                    if(!typesChecked.some(t => cardTypes.includes(t))) ok = false;
                }

                // beds
                if(bedsVal && bedsVal !== 'any'){
                    if(bedsVal === '5+'){
                        if(!(parseInt(card.dataset.beds||0) >= 5)) ok = false;
                    } else {
                        if(parseInt(card.dataset.beds||0) !== parseInt(bedsVal)) ok = false;
                    }
                }

                // baths
                if(bathsVal && bathsVal !== 'any'){
                    if(bathsVal === '5+'){
                        if(!(parseInt(card.dataset.baths||0) >= 5)) ok = false;
                    } else {
                        if(parseInt(card.dataset.baths||0) !== parseInt(bathsVal)) ok = false;
                    }
                }

                if(ok){
                    col.style.display = '';
                    visible++;
                } else {
                    col.style.display = 'none';
                }
            });

            const resultsNumEl = document.querySelector('.results-number');
            if(resultsNumEl) resultsNumEl.textContent = visible;
            const noEl = document.querySelector('.no-results');
            if(noEl) noEl.style.display = (visible ? 'none' : 'block');

            // Trigger reflow / grid realign and notify map
            requestAnimationFrame(()=>{ window.dispatchEvent(new Event('resize')); });
            document.dispatchEvent(new CustomEvent('filtersApplied',{ detail: { visible } }));
        }

        // Run filters whenever UI dispatches filtersChanged (already fired in handlers)
        document.addEventListener('filtersChanged', applyFilters);
        // Also run once on load to set initial state
        applyFilters();

    });
})();

// Helper: parse price strings like "300k", "1.2M", "$450,000", or numeric values
function parsePriceValue(val) {
  if (val == null || val === '') return NaN;
  if (typeof val === 'number') return val;
  // strip currency and commas, lowercase
  let s = String(val).replace(/\$/g,'').replace(/,/g,'').trim().toLowerCase();
  // support k / m suffix
  const mK = s.match(/^([\d.]+)k$/);
  if (mK) return Math.round(parseFloat(mK[1]) * 1000);
  const mM = s.match(/^([\d.]+)m$/);
  if (mM) return Math.round(parseFloat(mM[1]) * 1000000);
  // plain numeric
  const n = parseFloat(s);
  return isNaN(n) ? NaN : n;
}

// Parse range label like "Any Price" or "300k-600k" or "Over 1M" or "Up to 500k"
function parseRangeLabel(label) {
  if (!label || /any price/i.test(label)) return null;
  // normalize common dashes/en-dashes and synonyms like "Under" -> "Up to"
  label = String(label)
           .replace(/[\u2013\u2014–—]/g, '-')       // normalize en/em dashes to hyphen
           .replace(/\s*under\s+/i, ' up to ')     // accept "Under $100k"
           .replace(/\s*upto\s+/i, ' up to ')
           .trim()
           .toLowerCase();

  // hyphen range (e.g. "100k-300k")
  const hy = label.match(/([\d.,km]+)\s*-\s*([\d.,km]+)/);
  if (hy) {
    return { min: parsePriceValue(hy[1]), max: parsePriceValue(hy[2]) };
  }
  // "up to X" or "under X"
  const up = label.match(/(up to|under)\s*([\d.,km]+)/);
  if (up) return { min: 0, max: parsePriceValue(up[2]) };
  // "over X" or "more than X" or "from X"
  const over = label.match(/(over|more than|from)\s*([\d.,km]+)/);
  if (over) return { min: parsePriceValue(over[2]), max: Infinity };
  // + suffix like "600k+"
  const plus = label.match(/([\d.,km]+)\s*\+/);
  if (plus) return { min: parsePriceValue(plus[1]), max: Infinity };
  // single value fallback
  const v = parsePriceValue(label);
  if (!isNaN(v)) return { min: v, max: v };
  return null;
}

// cardMatchesPrice unchanged
function cardMatchesPrice(card, selectedRangeLabelOrValue) {
  // allow passing either the button text or the machine-friendly data-range value (like "300k-600k")
  const label = selectedRangeLabelOrValue || '';
  if (!label || /any price/i.test(label)) return true;
  const range = parseRangeLabel(label);
  if (!range) return true;

  // prefer numeric data attribute data-price (in full numeric form). fallback to data-price-range text parse.
  let cardPrice = NaN;

  if (card.dataset.price) cardPrice = parsePriceValue(card.dataset.price);
  else if (card.dataset.priceRange) {
    const pr = card.dataset.priceRange;
    const hy = pr.match(/([\d.,km]+)\s*-\s*([\d.,km]+)/);
    if (hy) {
      const min = parsePriceValue(hy[1]);
      const max = parsePriceValue(hy[2]);
      if (!isNaN(min) && !isNaN(max)) cardPrice = Math.round((min + max) / 2);
    } else {
      cardPrice = parsePriceValue(pr);
    }
  }

  if (isNaN(cardPrice)) {
    // if no numeric info, consider it match
    return true;
  }
  if (cardPrice < range.min) return false;
  if (cardPrice > range.max) return false;
  return true;
}
