<script>
if (typeof _swPickers === 'undefined') { var _swPickers = {}; }

function swPickerInit(prefix) {
    if (_swPickers[prefix]) return;
    _swPickers[prefix] = { items: [], timer: null };

    var dropdown = document.getElementById(prefix + '-dropdown');
    if (dropdown) {
        dropdown.addEventListener('mousedown', function(e) {
            var row = e.target.closest('[data-id]');
            if (!row) return;
            e.preventDefault();
            var nombre = row.dataset.nombre + (row.dataset.sub ? ' — ' + row.dataset.sub : '');
            swAddItem(prefix, row.dataset.id, nombre);
            var search = document.getElementById(prefix + '-search');
            if (search) search.value = '';
            dropdown.classList.add('hidden');
        });
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#' + prefix + '-search') &&
            !e.target.closest('#' + prefix + '-dropdown')) {
            var d = document.getElementById(prefix + '-dropdown');
            if (d) d.classList.add('hidden');
        }
    });
}

function swSearch(prefix, q) {
    swPickerInit(prefix);
    var p = _swPickers[prefix];
    clearTimeout(p.timer);
    var dropdown = document.getElementById(prefix + '-dropdown');
    if (q.length < 2) { if (dropdown) dropdown.classList.add('hidden'); return; }
    p.timer = setTimeout(function() {
        fetch('/software/catalogo?q=' + encodeURIComponent(q) + '&tipo=licenciado,libre')
            .then(function(r) { return r.json(); })
            .then(function(items) { swRenderDropdown(prefix, items); });
    }, 300);
}

function swRenderDropdown(prefix, items) {
    var d = document.getElementById(prefix + '-dropdown');
    if (!d) return;
    if (!items.length) {
        d.innerHTML = '<div class="px-4 py-3 text-xs text-gray-400 italic">Sin resultados en el catálogo</div>';
        d.classList.remove('hidden'); return;
    }
    d.innerHTML = items.map(function(item) {
        var badge = item.tipo === 'licenciado'
            ? '<span class="bg-blue-100 text-blue-700 text-[9px] font-black px-2 py-0.5 rounded-full flex-shrink-0 pointer-events-none">Licenciado</span>'
            : '<span class="bg-green-100 text-green-700 text-[9px] font-black px-2 py-0.5 rounded-full flex-shrink-0 pointer-events-none">Libre</span>';
        var escNombre = (item.nombre || '').replace(/&/g,'&amp;').replace(/"/g,'&quot;');
        var escSub    = (item.subproducto || '').replace(/&/g,'&amp;').replace(/"/g,'&quot;');
        var sub = item.subproducto
            ? '<span class="text-gray-400 pointer-events-none"> — ' + item.subproducto + '</span>' : '';
        return '<div class="px-4 py-2.5 hover:bg-indigo-50 cursor-pointer flex justify-between items-center gap-3"'
             + ' data-id="' + item.id + '" data-nombre="' + escNombre + '" data-sub="' + escSub + '">'
             + '<span class="text-xs font-bold text-gray-800 leading-tight pointer-events-none">'
             + item.nombre + sub + '</span>'
             + badge + '</div>';
    }).join('');
    d.classList.remove('hidden');
}

function swAddItem(prefix, id, nombre) {
    swPickerInit(prefix);
    var p = _swPickers[prefix];
    id = String(id);
    if (p.items.some(function(i) { return i.id === id; })) return;
    p.items.push({ id: id, nombre: nombre });
    swRenderList(prefix);
}

function swRemoveItem(prefix, id) {
    var p = _swPickers[prefix];
    if (!p) return;
    id = String(id);
    p.items = p.items.filter(function(i) { return i.id !== id; });
    swRenderList(prefix);
}

function swRenderList(prefix) {
    var p       = _swPickers[prefix];
    var list    = document.getElementById(prefix + '-list');
    var itemsEl = document.getElementById(prefix + '-items');
    if (!list || !itemsEl) return;
    var btn = document.getElementById(prefix + '-submit');
    if (!p || !p.items.length) {
        list.classList.add('hidden');
        if (btn) btn.disabled = true;
        return;
    }
    list.classList.remove('hidden');
    itemsEl.innerHTML = p.items.map(function(item) {
        return '<div class="flex items-center justify-between bg-indigo-50 border border-indigo-100 rounded-lg px-3 py-2">'
             + '<span class="text-xs font-bold text-indigo-800 truncate pr-2">' + item.nombre + '</span>'
             + '<button type="button"'
             + ' onclick="swRemoveItem(\'' + prefix + '\', \'' + item.id + '\')"'
             + ' class="text-red-400 hover:text-red-600 transition flex-shrink-0">'
             + '<i class="fas fa-times-circle text-sm"></i></button></div>';
    }).join('');
    if (btn) btn.disabled = false;
}

function swInjectInputs(prefix, formEl) {
    formEl.querySelectorAll('input.sw-injected[data-picker="' + prefix + '"]').forEach(function(el) { el.remove(); });
    var items = (_swPickers[prefix] || {}).items || [];
    items.forEach(function(item) {
        var inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'software_ids[]';
        inp.value = item.id;
        inp.className = 'sw-injected';
        inp.setAttribute('data-picker', prefix);
        formEl.appendChild(inp);
    });
}

function swResetPicker(prefix) {
    if (_swPickers[prefix]) _swPickers[prefix].items = [];
    var s = document.getElementById(prefix + '-search');
    if (s) s.value = '';
    var d = document.getElementById(prefix + '-dropdown');
    if (d) d.classList.add('hidden');
    swRenderList(prefix);
}
</script>
