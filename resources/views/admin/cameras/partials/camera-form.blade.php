@php
    $isEdit = isset($camera) && $camera;
    $action = $isEdit ? route('admin.cameras.cameras.update', $camera) : route('admin.cameras.cameras.store');
    $preselectedDvrId = $preselectedDvrId ?? null;
@endphp
<form method="POST" action="{{ $action }}" class="space-y-6" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div>
        <label for="dvr_id" class="block font-medium text-sm text-gray-700">DVR</label>
        <select id="dvr_id" name="dvr_id" class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" required>
            <option value="">Selecione o DVR</option>
            @foreach($dvrs ?? [] as $dvr)
                <option value="{{ $dvr->id }}" {{ old('dvr_id', $camera?->dvr_id ?? ($preselectedDvrId ?? null)) == $dvr->id ? 'selected' : '' }}>{{ $dvr->nome }}</option>
            @endforeach
        </select>
        @error('dvr_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="nome" class="block font-medium text-sm text-gray-700">Nome</label>
        <input id="nome" name="nome" type="text" value="{{ old('nome', $camera?->nome ?? '') }}"
               class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" required />
        @error('nome')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="descricao" class="block font-medium text-sm text-gray-700">Descrição (opcional)</label>
        <textarea id="descricao" name="descricao" rows="2"
                  class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm block mt-1 w-full">{{ old('descricao', $camera?->descricao ?? '') }}</textarea>
        @error('descricao')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="canal" class="block font-medium text-sm text-gray-700">Canal (opcional)</label>
        <input id="canal" name="canal" type="text" value="{{ old('canal', $camera?->canal ?? '') }}"
               class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" />
        @error('canal')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="foto" class="block font-medium text-sm text-gray-700">Foto (opcional)</label>
        @if($isEdit && $camera->foto)
            <div class="mt-2 mb-2">
                <img src="{{ asset('storage/' . $camera->foto) }}" alt="Foto atual" class="h-20 w-auto rounded border border-gray-300">
                <p class="text-xs text-gray-500 mt-1">Envie nova imagem para substituir</p>
            </div>
        @endif
        <input id="foto" name="foto" type="file" accept="image/*"
               class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" />
        @error('foto')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="block font-medium text-sm text-gray-700">Status</label>
        <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" required>
            @foreach(\App\Models\Camera::statusOptions() as $value => $label)
                <option value="{{ $value }}" {{ old('status', $camera?->status ?? 'ativo') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200">
        <button type="button" onclick="{{ $isEdit ? 'closeEditCameraModal()' : 'closeCreateCameraModal()' }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
            Cancelar
        </button>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fas fa-save mr-2"></i>
            {{ $isEdit ? 'Atualizar Câmera' : 'Criar Câmera' }}
        </button>
    </div>
</form>
