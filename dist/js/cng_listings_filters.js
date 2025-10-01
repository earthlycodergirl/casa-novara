// Filters UI behavior: search suggestions, dropdowns, types apply/clear, mobile toggle
(function(){
    document.addEventListener('DOMContentLoaded', function(){
        const root = document.querySelector('.listings-filter');
        if(!root) return;

        // Search suggestions
        const suggestions = ["Playa del Carmen","Tulum","Cancún","Puerto Morelos","Valladolid","Merida","Chichen Itza"];
        const searchInput = root.querySelector('#ls-search');
        const suggBox = root.querySelector('.search-suggestions');
        const suggList = suggBox && suggBox.querySelector('ul');

        function renderSuggestions(filter){
            const items = suggestions.filter(s => s.toLowerCase().includes(filter.toLowerCase()));
            if(!suggList) return;
            suggList.innerHTML = items.map(i=>`<li class="sugg-item" role="option">${i}</li>`).join('') || '<li class="sugg-empty">No results</li>';
        }

        if(searchInput){
            searchInput.addEventListener('input', function(e){ renderSuggestions(e.target.value); suggBox.setAttribute('aria-hidden','false'); suggBox.style.display='block'; });
            searchInput.addEventListener('focus', function(){ renderSuggestions(searchInput.value||''); suggBox.setAttribute('aria-hidden','false'); suggBox.style.display='block'; });
        }

        document.addEventListener('click', function(e){ if(!root.contains(e.target)) { if(suggBox) suggBox.style.display='none'; closeAllDropdowns(); } });

        if(suggList){
            suggList.addEventListener('click', function(e){ const li = e.target.closest('li'); if(!li) return; if(li.classList.contains('sugg-empty')) return; if(searchInput) searchInput.value = li.textContent; suggBox.style.display='none'; });
        }

        // Generic dropdown behavior
        const dropdowns = Array.from(root.querySelectorAll('.filter-dropdown'));
        function closeAllDropdowns(){ dropdowns.forEach(d=> d.classList.remove('open')); }

        dropdowns.forEach(d => {
            const btn = d.querySelector('.btn-filter');
            const panel = d.querySelector('.dropdown-panel');
            btn.addEventListener('click', function(e){ const isOpen = d.classList.toggle('open'); dropdowns.filter(x=>x!==d).forEach(x=>x.classList.remove('open')); });

            const filter = d.dataset.filter;
            if(filter === 'sale'){
                panel.addEventListener('click', function(e){ const li = e.target.closest('li'); if(!li) return; const val = li.dataset.value; btn.textContent = li.textContent; d.classList.remove('open'); document.dispatchEvent(new Event('filtersChanged')); });
            }
            if(filter === 'price'){
                panel.addEventListener('click', function(e){ const li = e.target.closest('li'); if(!li) return; btn.textContent = li.textContent; d.classList.remove('open'); document.dispatchEvent(new Event('filtersChanged')); });
            }
            if(filter === 'types'){
                const apply = d.querySelector('.btn-apply');
                const clear = d.querySelector('.btn-clear');
                const checkboxes = Array.from(d.querySelectorAll('.types-grid input[type="checkbox"]'));
                apply.addEventListener('click', function(){ const selected = checkboxes.filter(i=>i.checked).map(i=> i.closest('.type').dataset.value); btn.textContent = selected.length ? `${selected.length} types` : 'Property Types'; d.classList.remove('open'); document.dispatchEvent(new Event('filtersChanged')); });
                clear.addEventListener('click', function(){ checkboxes.forEach(c=> c.checked=false); btn.textContent = 'Property Types'; document.dispatchEvent(new Event('filtersChanged')); });
            }
            if(filter === 'beds' || filter === 'baths'){
                panel.addEventListener('click', function(e){ const li = e.target.closest('label'); if(!li) return; const input = li.querySelector('input'); if(!input) return; setTimeout(()=>{ const val = input.value; btn.textContent = (filter==='beds' ? (val==='any' ? 'All Beds' : val+' bed'+(val==='1'?'':'s')) : (val==='any' ? 'All Baths' : val+' bath'+(val==='1'?'':'s'))); d.classList.remove('open'); document.dispatchEvent(new Event('filtersChanged')); },20); });
            }
        });

        // Mobile toggle to expand/collapse filters
        const mobileToggle = root.querySelector('[data-action="toggle-filters"]');
        if(mobileToggle){ mobileToggle.addEventListener('click', function(){ root.classList.toggle('expanded'); }); }

        // When search changes via suggestions or typing, notify filters changed
        if(searchInput) searchInput.addEventListener('input', function(){ document.dispatchEvent(new Event('filtersChanged')); });
    });
})();
