@php
    $allBranches = \App\Models\Branch::orderBy('name')->get();
    $linkedBranchIds = $form->links->pluck('branch_id');
    $availableBranches = $allBranches->filter(fn($b) => !$linkedBranchIds->contains($b->id));
@endphp
<div class="flex flex-wrap gap-2 mb-4">
    @if($availableBranches->isNotEmpty())
        <form action="{{ route('admin.forms.links.store', $form) }}" method="POST" class="flex gap-2 flex-wrap items-center" data-ajax-form="links">
            @csrf
            <select name="branch_id" required class="rounded-md border-gray-600 bg-gray-700 text-white text-sm px-2 py-1.5">
                <option value="">Selecione a filial</option>
                @foreach($availableBranches as $b)
                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-primary-600 text-white text-sm rounded hover:bg-primary-700">
                <i class="fas fa-plus mr-1"></i> Criar link
            </button>
        </form>
        @if($availableBranches->count() >= 1)
            <form action="{{ route('admin.forms.links.store-all', $form) }}" method="POST" class="inline" data-ajax-form="links" data-ajax-confirm="Criar links para as {{ $availableBranches->count() }} filial(is) que ainda não possuem?">
                @csrf
                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                    <i class="fas fa-link mr-1"></i> Criar link para todas as filiais
                </button>
            </form>
        @endif
    @elseif($allBranches->isEmpty())
        <span class="text-sm text-amber-400">Cadastre <a href="{{ route('admin.branches.index') }}" class="underline hover:text-amber-300">filiais</a> primeiro.</span>
    @else
        <span class="text-sm text-gray-400">Todas as filiais já possuem link.</span>
    @endif
</div>
@if($form->links->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-600 text-sm">
            <thead>
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Filial</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Link</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Status</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-600">
                @foreach($form->links as $link)
                    <tr>
                        <td class="px-3 py-2 text-gray-200">{{ $link->branch->name ?? '-' }}</td>
                        <td class="px-3 py-2 min-w-0">
                            <div class="flex items-center gap-2">
                                <code class="text-xs bg-gray-700 px-2 py-1 rounded break-all flex-1 min-w-0 text-gray-300">{{ $link->url }}</code>
                                <button type="button" data-copy-url="{{ e($link->url) }}" onclick="copyFormLink(this)" class="shrink-0 px-2 py-1 text-primary-400 hover:text-primary-300 hover:bg-gray-700 rounded flex items-center gap-1" title="Copiar link">
                                    <i class="fas fa-copy"></i>
                                    <span class="text-xs">Copiar</span>
                                </button>
                            </div>
                        </td>
                        <td class="px-3 py-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $link->is_active ? 'bg-green-900/50 text-green-400' : 'bg-gray-700 text-gray-400' }}">
                                {{ $link->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-3 py-2">
                            <form action="{{ route('admin.forms.links.toggle', [$form, $link]) }}" method="POST" class="inline" data-ajax-form="links">
                                @csrf
                                <button type="submit" class="text-amber-400 hover:text-amber-300 mr-2" title="{{ $link->is_active ? 'Desativar' : 'Ativar' }}">
                                    <i class="fas fa-{{ $link->is_active ? 'pause' : 'play' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.forms.links.destroy', [$form, $link]) }}" method="POST" class="inline" data-ajax-form="links" data-ajax-confirm="Excluir este link?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-8 text-gray-400">
        <i class="fas fa-link text-3xl mb-3 block"></i>
        <p class="mb-4">Nenhum link criado. Cadastre filiais em <a href="{{ route('admin.branches.index') }}" class="text-primary-400 hover:underline">Filiais</a> e crie links acima.</p>
    </div>
@endif
