<p class="text-sm text-gray-400 mb-4">Crie perfis de pesos padrão para usar ao adicionar opções nas perguntas. Ex: Likert 1-5 (Nunca, Raramente, Às vezes...).</p>
@if($form->standardWeightProfiles->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-600 text-sm">
            <thead>
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Nome</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Opções</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-400 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-600">
                @foreach($form->standardWeightProfiles as $profile)
                    <tr>
                        <td class="px-3 py-2 text-gray-200">{{ $profile->name }}</td>
                        <td class="px-3 py-2 text-gray-400 text-xs">
                            @foreach($profile->options as $opt)
                                <span class="inline-block bg-gray-700 px-2 py-0.5 rounded mr-1 mb-1">{{ $opt->option_text }} ({{ $opt->weight }})</span>
                            @endforeach
                        </td>
                        <td class="px-3 py-2 text-right">
                            <form action="{{ route('admin.forms.standard-weight-profiles.destroy', [$form, $profile]) }}" method="POST" class="inline" data-ajax-form="pesos" data-ajax-confirm="Excluir este perfil?">
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
        <i class="fas fa-balance-scale text-3xl mb-3 block"></i>
        <p class="mb-4">Nenhum perfil de pesos padrão. Crie um para usar ao adicionar perguntas.</p>
        <button type="button" onclick="openPesosPadraoCreateModal()" class="page-header-btn-primary text-xs">
            <i class="fas fa-plus mr-1"></i> Criar perfil
        </button>
    </div>
@endif
