<form action="{{ route('admin.forms.standard-weight-profiles.store', $form) }}" method="POST" data-ajax-form="pesos-create">
    @csrf
    <div class="space-y-4">
        <div>
            <label class="block text-sm text-gray-300 mb-1">Nome do perfil *</label>
            <input type="text" name="name" required placeholder="Ex: Likert 1-5 (Nunca a Sempre)" class="w-full rounded-lg border-gray-600 bg-gray-700 text-white px-3 py-2">
        </div>
        <div>
            <label class="block text-sm text-gray-300 mb-2">Opções (resposta e peso)</label>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400">
                        <th class="pb-1">Resposta</th>
                        <th class="pb-1 w-20">Peso</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="pesos-options-tbody">
                    <tr>
                        <td class="px-2 py-1"><input type="text" name="options[0][option_text]" value="Nunca" required class="w-full rounded border-gray-600 bg-gray-700 text-white px-2 py-1 text-sm"></td>
                        <td class="px-2 py-1"><input type="number" name="options[0][weight]" value="1" required class="w-16 rounded border-gray-600 bg-gray-700 text-white px-2 py-1 text-sm"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="px-2 py-1"><input type="text" name="options[1][option_text]" value="Raramente" required class="w-full rounded border-gray-600 bg-gray-700 text-white px-2 py-1 text-sm"></td>
                        <td class="px-2 py-1"><input type="number" name="options[1][weight]" value="2" required class="w-16 rounded border-gray-600 bg-gray-700 text-white px-2 py-1 text-sm"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="px-2 py-1"><input type="text" name="options[2][option_text]" value="Às vezes" required class="w-full rounded border-gray-600 bg-gray-700 text-white px-2 py-1 text-sm"></td>
                        <td class="px-2 py-1"><input type="number" name="options[2][weight]" value="3" required class="w-16 rounded border-gray-600 bg-gray-700 text-white px-2 py-1 text-sm"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="px-2 py-1"><input type="text" name="options[3][option_text]" value="Frequentemente" required class="w-full rounded border-gray-600 bg-gray-700 text-white px-2 py-1 text-sm"></td>
                        <td class="px-2 py-1"><input type="number" name="options[3][weight]" value="4" required class="w-16 rounded border-gray-600 bg-gray-700 text-white px-2 py-1 text-sm"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="px-2 py-1"><input type="text" name="options[4][option_text]" value="Sempre" required class="w-full rounded border-gray-600 bg-gray-700 text-white px-2 py-1 text-sm"></td>
                        <td class="px-2 py-1"><input type="number" name="options[4][weight]" value="5" required class="w-16 rounded border-gray-600 bg-gray-700 text-white px-2 py-1 text-sm"></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" onclick="addPesoOptionRow()" class="mt-2 text-sm text-primary-400 hover:text-primary-300">
                <i class="fas fa-plus mr-1"></i> Adicionar opção
            </button>
        </div>
    </div>
    <div class="mt-6 flex gap-3 justify-end">
        <button type="button" onclick="closePesosPadraoCreateModal()" class="px-4 py-2 text-gray-400 hover:text-white">Cancelar</button>
        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Criar</button>
    </div>
</form>
