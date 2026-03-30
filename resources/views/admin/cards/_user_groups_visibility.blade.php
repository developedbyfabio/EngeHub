@php
    $selectedGroupIds = $selectedGroupIds ?? [];
@endphp
<div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
    <span class="block font-medium text-sm text-gray-700 mb-1">Visibilidade na página Início</span>
    <p class="text-xs text-gray-500 mb-3">
        Marque os <strong>grupos</strong> que podem ver este card no Início. Com <strong>um ou mais</strong> grupos, só eles enxergam o card.
        Com <strong>nenhum</strong> marcado, o card fica no “catálogo geral”: visível para <strong>visitantes</strong> e para usuários <strong>sem grupo</strong> definido — usuários de grupos como Administrativo <strong>não</strong> veem esses cards até você marcar o grupo deles aqui.
        Quem está no grupo <strong>Administradores</strong> (acesso total) continua vendo todos os cards. Abas sem nenhum card visível somem para cada usuário.
    </p>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-44 overflow-y-auto pr-1">
        @forelse($userGroups as $g)
            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                <input type="checkbox" name="user_group_ids[]" value="{{ $g->id }}" class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500"
                    @checked(in_array($g->id, old('user_group_ids', $selectedGroupIds), true))>
                <span>{{ $g->name }}</span>
            </label>
        @empty
            <p class="text-sm text-gray-500 col-span-full">Nenhum grupo cadastrado.</p>
        @endforelse
    </div>
    @error('user_group_ids')
        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
    @enderror
    @error('user_group_ids.*')
        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
    @enderror
</div>
