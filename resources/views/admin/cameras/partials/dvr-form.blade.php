@php
    $isEdit = isset($dvr) && $dvr;
    $action = $isEdit ? route('admin.cameras.dvrs.update', $dvr) : route('admin.cameras.dvrs.store');
@endphp
<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div>
        <label for="nome" class="block font-medium text-sm text-gray-700">Nome</label>
        <input id="nome" name="nome" type="text" value="{{ old('nome', $dvr?->nome ?? '') }}"
               class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" required />
        @error('nome')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="descricao" class="block font-medium text-sm text-gray-700">Descrição (opcional)</label>
        <textarea id="descricao" name="descricao" rows="3"
                  class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm block mt-1 w-full">{{ old('descricao', $dvr?->descricao ?? '') }}</textarea>
        @error('descricao')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="localizacao" class="block font-medium text-sm text-gray-700">Localização (opcional)</label>
        <input id="localizacao" name="localizacao" type="text" value="{{ old('localizacao', $dvr?->localizacao ?? '') }}"
               class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" />
        @error('localizacao')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="acesso_web" class="block font-medium text-sm text-gray-700">Acesso Web (opcional)</label>
        <input id="acesso_web" name="acesso_web" type="text" placeholder="Ex: http://192.168.0.11:80/"
               value="{{ old('acesso_web', $dvr?->acesso_web ?? '') }}"
               class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" />
        <p class="text-xs text-gray-500 mt-1">Link para acessar o DVR pela web (ex.: http://192.168.0.11:80/)</p>
        @error('acesso_web')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="block font-medium text-sm text-gray-700">Status</label>
        <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" required>
            @foreach(\App\Models\Dvr::statusOptions() as $value => $label)
                <option value="{{ $value }}" {{ old('status', $dvr?->status ?? 'ativo') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200">
        <button type="button" onclick="{{ $isEdit ? 'closeEditDvrModal()' : 'closeCreateDvrModal()' }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
            Cancelar
        </button>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fas fa-save mr-2"></i>
            {{ $isEdit ? 'Atualizar DVR' : 'Criar DVR' }}
        </button>
    </div>
</form>
