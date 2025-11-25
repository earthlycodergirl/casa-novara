// Filters UI behavior: search suggestions, dropdowns, types apply/clear, mobile toggle
(function(){
    document.addEventListener('DOMContentLoaded', function(){

        // Get the main filters container
        const root = document.querySelector('.listings-filter');
        if (!root) return; // Exit if no filters container found

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
                    
                    // Reset positioning classes first
                    d.classList.remove('align-right');
                    
                    // small delay to allow class=open to apply layout then measure
                    requestAnimationFrame(() => {
                        const rect = panel.getBoundingClientRect();
                        if(rect.right > window.innerWidth - 12){
                            // align panel to the right edge of the button/container
                            d.classList.add('align-right');
                        }
                        // Left alignment is handled by default CSS, no need to set it explicitly
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
                panel.addEventListener('click', function(e){ 
                    const li = e.target.closest('li'); 
                    if(!li) return; 
                    const val = li.dataset.value || li.textContent.trim(); 
                    btn.textContent = li.textContent; 
                    btn.dataset.value = val; 
                    d.classList.remove('open'); 
                    // Make sure to trigger the filter update
                    setTimeout(() => {
                        document.dispatchEvent(new Event('filtersChanged')); 
                    }, 10);
                });
            }
            if(filter === 'price'){
                panel.addEventListener('click', function(e){
                    const li = e.target.closest('li');
                    if(!li) return;
                    // set both user-facing text and machine-friendly data-range (from data-value or label)
                    btn.textContent = li.textContent;
                    btn.dataset.range = li.dataset.value || li.textContent.trim();
                    d.classList.remove('open');
                    setTimeout(() => {
                        document.dispatchEvent(new Event('filtersChanged'));
                    }, 10);
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
                const typeCheckboxes = Array.from(d.querySelectorAll('.type-checkbox'));
                const subtypeCheckboxes = Array.from(d.querySelectorAll('.subtype-checkbox'));
                const allCheckboxes = [...typeCheckboxes, ...subtypeCheckboxes];
                const clearButton = d.querySelector('.btn-clear-types');
                
                // Function to update button text and trigger filtering
                function updateTypesFilter() {
                    const selectedTypes = typeCheckboxes.filter(i=>i.checked).map(i=> i.closest('.type-item').dataset.value);
                    const selectedSubtypes = subtypeCheckboxes.filter(i=>i.checked).map(i=> i.closest('.subtype-item').dataset.value);
                    const allSelected = [...selectedTypes, ...selectedSubtypes];
                    const totalSelected = allSelected.length;
                    
                    if (totalSelected === 0) {
                        btn.textContent = 'Property Types';
                    } else if (totalSelected <= 2) {
                        btn.textContent = allSelected.join(', ');
                    } else {
                        btn.textContent = `${totalSelected} selected`;
                    }
                    
                    // Use timeout to ensure checkbox state is updated before filtering
                    setTimeout(() => {
                        document.dispatchEvent(new Event('filtersChanged'));
                    }, 10);
                }
                
                // Add click listeners to all checkboxes for automatic filtering
                allCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateTypesFilter();
                    });
                });
                
                // Add clear all functionality
                if(clearButton) {
                    clearButton.addEventListener('click', function() {
                        allCheckboxes.forEach(c=> c.checked=false);
                        btn.textContent = 'Property Types';
                        setTimeout(() => {
                            document.dispatchEvent(new Event('filtersChanged'));
                        }, 10);
                    });
                }
                
                // Add double-click to clear all for the button
                btn.addEventListener('dblclick', function() {
                    allCheckboxes.forEach(c=> c.checked=false);
                    btn.textContent = 'Property Types';
                    setTimeout(() => {
                        document.dispatchEvent(new Event('filtersChanged'));
                    }, 10);
                });
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
                        // Use additional timeout to ensure state is set before filtering
                        setTimeout(() => {
                            document.dispatchEvent(new Event('filtersChanged'));
                        }, 10);
                    },20);
                });
            }
        });

        // Mobile toggle to expand/collapse filters
        const mobileToggle = root.querySelector('[data-action="toggle-filters"]');
        if(mobileToggle){ mobileToggle.addEventListener('click', function(){ root.classList.toggle('expanded'); }); }

        // When search changes via suggestions or typing, notify filters changed
        if(searchInput) searchInput.addEventListener('input', function(){ updateSuggestions(searchInput.value); document.dispatchEvent(new Event('filtersChanged')); });

        // Add a global click handler to catch filter changes that might be missed
        document.addEventListener('click', function(e) {
            if (e.target.closest('.filter-dropdown')) {
                setTimeout(() => {
                    document.dispatchEvent(new Event('filtersChanged'));
                }, 50);
            }
        });

        // Add change event listeners to all inputs in filter dropdowns
        root.addEventListener('change', function(e) {
            if (e.target.matches('input[type="checkbox"], input[type="radio"]')) {
                setTimeout(() => {
                    document.dispatchEvent(new Event('filtersChanged'));
                }, 10);
            }
        });

        // Apply filters: reads UI state and shows/hides .property-card elements
        function applyFilters(){
            // Use the column wrappers so the grid cells collapse/expand correctly
            const cols = Array.from(document.querySelectorAll('.property-col'));
            const searchVal = (searchInput && searchInput.value || '').trim().toLowerCase();

            const saleBtn = root.querySelector('[data-filter="sale"] .btn-filter');
            let saleVal = '';
            if (saleBtn) {
                // Prioritize dataset.value, fallback to parsing button text
                if (saleBtn.dataset.value) {
                    saleVal = saleBtn.dataset.value;
                } else {
                    // Parse button text to determine filter value
                    const btnText = saleBtn.textContent.trim().toLowerCase();
                    if (btnText.includes('all')) saleVal = 'any';
                    else if (btnText.includes('sale')) saleVal = 'sale';
                    else if (btnText.includes('rent')) saleVal = 'rent';
                    else saleVal = 'any';
                }
            }
            
            const priceBtn = root.querySelector('[data-filter="price"] .btn-filter');
            let priceRange = '';
            if (priceBtn) {
                // Prioritize dataset.range, fallback to parsing button text
                if (priceBtn.dataset.range) {
                    priceRange = priceBtn.dataset.range;
                } else {
                    const btnText = priceBtn.textContent.trim().toLowerCase();
                    if (btnText.includes('any')) priceRange = '';
                    else priceRange = btnText; // Let the price parser handle it
                }
            }

            const locationBtn = root.querySelector('[data-filter="location"] .btn-filter');
            const locationVal = locationBtn ? (locationBtn.dataset.value || locationBtn.textContent.trim()).toLowerCase() : '';

            const typesTypeChecked = Array.from(root.querySelectorAll('[data-filter="types"] .type-checkbox:checked'))
                                          .map(i=> i.value); // Get the ID value instead of data-value
            const typesSubtypeChecked = Array.from(root.querySelectorAll('[data-filter="types"] .subtype-checkbox:checked'))
                                              .map(i=> i.value); // Get the ID value instead of data-value
            const typesChecked = [...typesTypeChecked, ...typesSubtypeChecked];

            const bedsChecked = root.querySelector('[data-filter="beds"] input:checked');
            const bedsVal = bedsChecked ? bedsChecked.value : 'any';
            
            const bathsChecked = root.querySelector('[data-filter="baths"] input:checked');
            const bathsVal = bathsChecked ? bathsChecked.value : 'any';

            let visible = 0;
            cols.forEach(col => {
                const card = col.querySelector('.property-card');
                if(!card){ 
                    col.classList.add('hidden');
                    return; 
                }

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
                if(saleVal && saleVal !== 'any' && !/all/i.test(saleVal)){
                    if(card.dataset.listing && card.dataset.listing.toLowerCase() !== saleVal.toLowerCase()) {
                        ok = false;
                    }
                }

                // price
                if(!cardMatchesPrice(card, priceRange)) {
                    ok = false;
                }

                // types (OR match)
                if(typesChecked.length){
                    const cardTypeIds = (card.dataset.typeIds||'').split(',').map(s=>s.trim()).filter(s=>s);
                    // Convert both arrays to strings for proper comparison
                    const typesCheckedStr = typesChecked.map(t => String(t));
                    const cardTypeIdsStr = cardTypeIds.map(t => String(t));
                    if(!typesCheckedStr.some(t => cardTypeIdsStr.includes(t))) {
                        ok = false;
                    }
                }

                // beds
                if(bedsVal && bedsVal !== 'any'){
                    if(bedsVal === '5+'){
                        if(!(parseInt(card.dataset.beds||0) >= 5)) {
                            ok = false;
                        }
                    } else {
                        if(parseInt(card.dataset.beds||0) !== parseInt(bedsVal)) {
                            ok = false;
                        }
                    }
                }

                // baths
                if(bathsVal && bathsVal !== 'any'){
                    if(bathsVal === '5+'){
                        if(!(parseInt(card.dataset.baths||0) >= 5)) {
                            ok = false;
                        }
                    } else {
                        if(parseInt(card.dataset.baths||0) !== parseInt(bathsVal)) {
                            ok = false;
                        }
                    }
                }

                if(ok){
                    col.classList.remove('hidden');
                    visible++;
                } else {
                    col.classList.add('hidden');
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
        document.addEventListener('filtersChanged', function() {
            // Use requestAnimationFrame to prevent rapid-fire events from causing issues
            if (window.filterTimeout) clearTimeout(window.filterTimeout);
            window.filterTimeout = setTimeout(applyFilters, 10);
        });
        
        // Initialize filters with multiple attempts to ensure DOM is fully ready
        setTimeout(() => {
            // Set initial data attributes for buttons that don't have them
            const saleBtn = root.querySelector('[data-filter="sale"] .btn-filter');
            if (saleBtn && !saleBtn.dataset.value) {
                saleBtn.dataset.value = 'any'; // Default to show all
            }
            
            const priceBtn = root.querySelector('[data-filter="price"] .btn-filter');
            if (priceBtn && !priceBtn.dataset.range) {
                priceBtn.dataset.range = ''; // Default to any price
            }
            
            applyFilters();
        }, 50);
        setTimeout(applyFilters, 200);
        
        // Initialize current filters functionality
        initCurrentFilters();

    });
})();

// Current filters functionality
function initCurrentFilters() {
    function updateCurrentFilters() {
        const currentFiltersSection = document.querySelector('.current-filters');
        const filterTagsContainer = document.getElementById('current-filter-tags');
        const root = document.querySelector('.listings-filter');
        
        if (!currentFiltersSection || !filterTagsContainer || !root) return;
        
        const activeFilters = [];
        
        // Check search input
        const searchInput = document.getElementById('ls-search');
        if (searchInput && searchInput.value.trim()) {
            activeFilters.push({
                type: 'search',
                label: `Search: "${searchInput.value.trim()}"`,
                value: searchInput.value.trim()
            });
        }
        
        // Check sale/rent filter
        const saleBtn = root.querySelector('[data-filter="sale"] .btn-filter');
        if (saleBtn && saleBtn.textContent.trim() !== 'All Types') {
            activeFilters.push({
                type: 'sale',
                label: saleBtn.textContent.trim(),
                value: saleBtn.dataset.value || saleBtn.textContent.trim()
            });
        }
        
        // Check price filter
        const priceBtn = root.querySelector('[data-filter="price"] .btn-filter');
        if (priceBtn && priceBtn.textContent.trim() !== 'Any Price') {
            activeFilters.push({
                type: 'price',
                label: `Price: ${priceBtn.textContent.trim()}`,
                value: priceBtn.dataset.range || priceBtn.textContent.trim()
            });
        }
        
        // Check property types
        const typesTypeChecked = Array.from(root.querySelectorAll('[data-filter="types"] .type-checkbox:checked'));
        const typesSubtypeChecked = Array.from(root.querySelectorAll('[data-filter="types"] .subtype-checkbox:checked'));
        const allTypesChecked = [...typesTypeChecked, ...typesSubtypeChecked];
        if (allTypesChecked.length > 0) {
            const typeNames = allTypesChecked.map(cb => {
                const item = cb.closest('.type-item') || cb.closest('.subtype-item');
                return item ? item.dataset.value : '';
            }).filter(Boolean);
            activeFilters.push({
                type: 'types',
                label: `Types: ${typeNames.join(', ')}`,
                value: typeNames
            });
        }
        
        // Check beds filter
        const bedsChecked = root.querySelector('[data-filter="beds"] input:checked');
        if (bedsChecked && bedsChecked.value !== 'any') {
            const bedsLabel = bedsChecked.value === '5+' ? '5+ beds' : `${bedsChecked.value} bed${bedsChecked.value === '1' ? '' : 's'}`;
            activeFilters.push({
                type: 'beds',
                label: bedsLabel,
                value: bedsChecked.value
            });
        }
        
        // Check baths filter
        const bathsChecked = root.querySelector('[data-filter="baths"] input:checked');
        if (bathsChecked && bathsChecked.value !== 'any') {
            const bathsLabel = bathsChecked.value === '5+' ? '5+ baths' : `${bathsChecked.value} bath${bathsChecked.value === '1' ? '' : 's'}`;
            activeFilters.push({
                type: 'baths',
                label: bathsLabel,
                value: bathsChecked.value
            });
        }
        
        // Update the display
        if (activeFilters.length === 0) {
            currentFiltersSection.style.display = 'none';
        } else {
            currentFiltersSection.style.display = 'block';
            filterTagsContainer.innerHTML = activeFilters.map(filter => 
                `<span class="filter-tag" data-filter-type="${filter.type}" data-filter-value="${encodeURIComponent(JSON.stringify(filter.value))}">
                    ${filter.label}
                    <button type="button" class="remove-filter" aria-label="Remove ${filter.label}" title="Remove filter">×</button>
                </span>`
            ).join('');
        }
    }

    // Remove individual filter
    function removeFilter(filterType, filterValue) {
        const root = document.querySelector('.listings-filter');
        if (!root) return;
        
        switch(filterType) {
            case 'search':
                const searchInput = document.getElementById('ls-search');
                if (searchInput) {
                    searchInput.value = '';
                    if (typeof updateSuggestions === 'function') updateSuggestions('');
                }
                break;
                
            case 'sale':
                const saleBtn = root.querySelector('[data-filter="sale"] .btn-filter');
                if (saleBtn) {
                    saleBtn.textContent = 'All Types';
                    saleBtn.dataset.value = 'any';
                }
                break;
                
            case 'price':
                const priceBtn = root.querySelector('[data-filter="price"] .btn-filter');
                if (priceBtn) {
                    priceBtn.textContent = 'Any Price';
                    delete priceBtn.dataset.range;
                }
                break;
                
            case 'types':
                const typeCheckboxes = root.querySelectorAll('[data-filter="types"] .type-checkbox, [data-filter="types"] .subtype-checkbox');
                typeCheckboxes.forEach(cb => cb.checked = false);
                const typesBtn = root.querySelector('[data-filter="types"] .btn-filter');
                if (typesBtn) typesBtn.textContent = 'Property Types';
                break;
                
            case 'beds':
                const bedsAny = root.querySelector('[data-filter="beds"] input[value="any"]');
                if (bedsAny) {
                    bedsAny.checked = true;
                    const bedsBtn = root.querySelector('[data-filter="beds"] .btn-filter');
                    if (bedsBtn) bedsBtn.textContent = 'All Beds';
                }
                break;
                
            case 'baths':
                const bathsAny = root.querySelector('[data-filter="baths"] input[value="any"]');
                if (bathsAny) {
                    bathsAny.checked = true;
                    const bathsBtn = root.querySelector('[data-filter="baths"] .btn-filter');
                    if (bathsBtn) bathsBtn.textContent = 'All Baths';
                }
                break;
        }
        
        // Trigger filters changed event
        try {
            document.dispatchEvent(new Event('filtersChanged'));
        } catch(e) {
            // Fallback if event dispatch fails
            setTimeout(function() {
                if (typeof applyFilters === 'function') applyFilters();
            }, 10);
        }
    }

    // Clear all filters
    function clearAllFilters() {
        const root = document.querySelector('.listings-filter');
        if (!root) return;
        
        // Clear search
        const searchInput = document.getElementById('ls-search');
        if (searchInput) {
            searchInput.value = '';
            if (typeof updateSuggestions === 'function') updateSuggestions('');
        }
        
        // Reset sale filter
        const saleBtn = root.querySelector('[data-filter="sale"] .btn-filter');
        if (saleBtn) {
            saleBtn.textContent = 'All Types';
            saleBtn.dataset.value = 'any';
        }
        
        // Reset price filter
        const priceBtn = root.querySelector('[data-filter="price"] .btn-filter');
        if (priceBtn) {
            priceBtn.textContent = 'Any Price';
            delete priceBtn.dataset.range;
        }
        
        // Clear property types
        const typeCheckboxes = root.querySelectorAll('[data-filter="types"] .type-checkbox, [data-filter="types"] .subtype-checkbox');
        typeCheckboxes.forEach(cb => cb.checked = false);
        const typesBtn = root.querySelector('[data-filter="types"] .btn-filter');
        if (typesBtn) typesBtn.textContent = 'Property Types';
        
        // Reset beds
        const bedsAny = root.querySelector('[data-filter="beds"] input[value="any"]');
        if (bedsAny) {
            bedsAny.checked = true;
            const bedsBtn = root.querySelector('[data-filter="beds"] .btn-filter');
            if (bedsBtn) bedsBtn.textContent = 'All Beds';
        }
        
        // Reset baths
        const bathsAny = root.querySelector('[data-filter="baths"] input[value="any"]');
        if (bathsAny) {
            bathsAny.checked = true;
            const bathsBtn = root.querySelector('[data-filter="baths"] .btn-filter');
            if (bathsBtn) bathsBtn.textContent = 'All Baths';
        }
        
        // Trigger filters changed event
        try {
            document.dispatchEvent(new Event('filtersChanged'));
        } catch(e) {
            // Fallback if event dispatch fails
            setTimeout(function() {
                if (typeof applyFilters === 'function') applyFilters();
            }, 10);
        }
    }

    // Event listeners for current filters
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-filter')) {
            e.preventDefault();
            const filterTag = e.target.closest('.filter-tag');
            if (filterTag) {
                const filterType = filterTag.dataset.filterType;
                const filterValue = JSON.parse(decodeURIComponent(filterTag.dataset.filterValue));
                removeFilter(filterType, filterValue);
            }
        }
    });

    const clearAllBtn = document.getElementById('clear-all-filters');
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            clearAllFilters();
        });
    }

    // Update current filters when filters change
    document.addEventListener('filtersChanged', function() {
        // Debounce the updates to prevent excessive DOM manipulation
        if (window.currentFiltersTimeout) clearTimeout(window.currentFiltersTimeout);
        window.currentFiltersTimeout = setTimeout(updateCurrentFilters, 20);
    });

    // Initialize current filters on page load
    setTimeout(updateCurrentFilters, 150); // Small delay to ensure everything is initialized
}

// Helper: parse price strings like "300k", "1.2M", "$450,000", or numeric values
function parsePriceValue(val) {
  if (val == null || val === '') return NaN;
  if (typeof val === 'number') return val;
  
  // Handle raw numeric strings (like "350000")
  const rawNumber = parseFloat(String(val));
  if (!isNaN(rawNumber) && /^\d+(\.\d+)?$/.test(String(val))) {
    return rawNumber;
  }
  
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
  
  // Handle pure numeric ranges like "100000-300000" first
  const numericRange = label.match(/^(\d+)-(\d+)$/);
  if (numericRange) {
    return { 
      min: parseInt(numericRange[1]), 
      max: parseInt(numericRange[2]) 
    };
  }
  
  // Handle special case for "600000-999999999" (the 600k+ case)
  const largeRange = label.match(/^(\d+)-999999999$/);
  if (largeRange) {
    return { 
      min: parseInt(largeRange[1]), 
      max: Infinity 
    };
  }
  
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

  if (card.dataset.price) {
    cardPrice = parsePriceValue(card.dataset.price);
  } else if (card.dataset.priceRange) {
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
  
  // Add some tolerance for edge cases
  if (cardPrice < range.min) return false;
  if (range.max !== Infinity && cardPrice > range.max) return false;
  return true;
}
