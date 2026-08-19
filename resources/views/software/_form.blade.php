<div>
    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nombre del Producto *</label>
    <input type="text" name="nombre" required maxlength="255"
           placeholder="Ej: Adobe Creative Cloud 2025 (ETLA)"
           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:outline-none focus:border-indigo-400">
</div>
<div>
    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Subproducto (opcional)</label>
    <input type="text" name="subproducto" maxlength="255"
           placeholder="Ej: Photoshop, Illustrator..."
           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:outline-none focus:border-indigo-400">
</div>
<div>
    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Tipo de Licencia *</label>
    <select name="tipo" required
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:outline-none focus:border-indigo-400">
        <option value="">— Seleccionar —</option>
        <option value="licenciado">Licenciado (Comercial SENA)</option>
        <option value="libre">Libre / Open Source</option>
        <option value="no_autorizado">No Autorizado (Lista Negra)</option>
    </select>
</div>
<div>
    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Vigencia Hasta (opcional)</label>
    <input type="date" name="vigencia_hasta"
           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:outline-none focus:border-indigo-400">
</div>
