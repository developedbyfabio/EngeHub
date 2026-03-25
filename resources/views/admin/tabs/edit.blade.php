<form method="POST" action="{{ route('admin.tabs.update', $tab) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div>
        <label for="name" class="block font-medium text-sm text-gray-700">
            Nome da Aba
        </label>
        <input id="name" class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" type="text" name="name" value="{{ old('name', $tab->name) }}" required autofocus />
        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block font-medium text-sm text-gray-700">
            Descrição (opcional)
        </label>
        <textarea id="description" name="description" rows="3" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm block mt-1 w-full">{{ old('description', $tab->description) }}</textarea>
        @error('description')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="color" class="block font-medium text-sm text-gray-700">
            Cor da Aba
        </label>
        <div class="mt-1 flex items-center space-x-4">
            <input type="color" id="color" name="color" value="{{ old('color', $tab->color) }}" class="h-10 w-20 border border-gray-300 rounded-md">
            <input type="text" id="color_text" value="{{ old('color', $tab->color) }}" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm block w-32" readonly>
        </div>
        @error('color')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="order" class="block font-medium text-sm text-gray-700">
            Ordem de Exibição
        </label>
        <input id="order" class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" type="number" name="order" value="{{ old('order', $tab->order) }}" min="0" required />
        <p class="text-sm text-gray-500 mt-1">Defina a ordem em que esta aba aparecerá (0 = primeiro)</p>
        @error('order')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200">
        <button type="button" onclick="if (typeof closeTabEditModal === 'function') { closeTabEditModal(); } else { window.location.href = '{{ route('admin.cards.index') }}'; }" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
            Cancelar
        </button>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fas fa-save mr-2"></i>
            Atualizar Aba
        </button>
    </div>
</form>

<script>
    document.getElementById('color').addEventListener('input', function(e) {
        document.getElementById('color_text').value = e.target.value;
    });
</script> 