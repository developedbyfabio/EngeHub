{{-- Apenas modais de EDIÇÃO (mapa de rede). Visualização usa device-side-panel. --}}
@php
    $canEdit = $canEditDevicesEffective ?? true;
@endphp

@if($canEdit)
<div id="deviceSeatEditModal" class="device-modal fixed inset-0 z-[99999] items-center justify-center p-4" aria-modal="true" style="display: none;">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeDeviceSeatEditModal()" aria-hidden="true"></div>
    <div class="relative bg-white rounded-xl shadow-2xl flex flex-col w-full mx-auto my-auto" style="max-width: 42rem; max-height: 85vh;" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-gray-200 shrink-0">
            <h3 class="text-lg font-semibold text-gray-900">Editar mesa — <span id="deviceSeatEditFullCode"></span></h3>
        </div>
        <div class="overflow-y-auto flex-1 min-h-0">
            <form id="deviceSeatEditForm" class="p-6 space-y-4" enctype="multipart/form-data">
                <input type="hidden" id="deviceSeatEditType" value="SEAT">
                <input type="hidden" id="deviceSeatEditCodeRaw">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Código (trecho após SEAT-)</label>
                    <input type="text" id="deviceSeatEditCodeReadonly" readonly class="w-full rounded border-gray-300 bg-gray-100 text-gray-700">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome do colaborador (exibido no mapa como “Nomes”)</label>
                    <input type="text" name="metadata[collaborator_name]" id="deviceSeatEditCollaboratorName" class="w-full rounded border-gray-300" maxlength="255">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="deviceSeatEditComputerKind">Tipo de Estação</label>
                    <select name="metadata[computer_kind]" id="deviceSeatEditComputerKind" class="w-full rounded border-gray-300 text-sm">
                        <option value="">Não informado</option>
                        <option value="desktop">Desktop</option>
                        <option value="notebook">Notebook</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome do PC</label>
                    <input type="text" name="metadata[computer_name]" id="deviceSeatEditComputerName" class="w-full rounded border-gray-300" maxlength="100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">IP do computador</label>
                    <input type="text" name="metadata[computer_ip]" id="deviceSeatEditComputerIp" class="w-full rounded border-gray-300" maxlength="45" autocomplete="off">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                    <textarea name="observacoes" id="deviceSeatEditObservacoes" class="w-full rounded border-gray-300" rows="3" maxlength="500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto da estação de trabalho</label>
                    <p class="text-xs text-gray-500 mb-2">Imagem opcional (JPEG, PNG, WebP; máx. 5&nbsp;MB).</p>
                    <div id="deviceSeatEditPhotoPreviewWrap" class="hidden mb-2">
                        <img id="deviceSeatEditPhotoPreview" src="" alt="" class="max-h-40 rounded-lg border border-gray-200 object-cover">
                    </div>
                    <input type="file" name="workstation_photo" id="deviceSeatEditWorkstationPhoto" accept="image/jpeg,image/png,image/webp,image/gif" class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100">
                    <label class="mt-2 inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="remove_workstation_photo" id="deviceSeatEditRemovePhoto" value="1" class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                        <span>Remover foto atual</span>
                    </label>
                </div>
            </form>
        </div>
        <div class="p-6 border-t border-gray-200 flex justify-end gap-2 shrink-0">
            <button type="button" onclick="closeDeviceSeatEditModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded font-medium hover:bg-gray-300">Cancelar</button>
            <button type="submit" form="deviceSeatEditForm" class="px-4 py-2 btn-engehub-yellow rounded font-medium">Atualizar</button>
        </div>
    </div>
</div>
@endif

@foreach([
    'Printer' => ['PRINTER', 'Impressora', 'printer'],
    'Tv' => ['TV', 'TV', 'tv'],
    'Scan' => ['SCAN', 'Scanner', 'scan'],
    'Phone' => ['PHONE', 'Telefone', 'phone'],
    'Ap' => ['AP', 'Access Point', 'ap'],
] as $suffix => $meta)
@php [$constType, $label, $key] = $meta; @endphp
@if($canEdit)
<div id="device{{ $suffix }}EditModal" class="device-modal fixed inset-0 z-[99999] items-center justify-center p-4" aria-modal="true" style="display: none;">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeDevice{{ $suffix }}EditModal()" aria-hidden="true"></div>
    <div class="relative bg-white rounded-xl shadow-2xl flex flex-col w-full mx-auto my-auto" style="max-width: 28rem;" onclick="event.stopPropagation()">
        <div class="p-5 border-b border-gray-200"><h3 class="text-lg font-semibold text-gray-900">Editar {{ strtolower($label) }} — <span id="device{{ $suffix }}EditFullCode"></span></h3></div>
        <form id="device{{ $suffix }}EditForm" class="p-5 space-y-3" enctype="multipart/form-data">
            <input type="hidden" class="device-edit-type" value="{{ $constType }}">
            <input type="hidden" class="device-edit-code">
            @if($key === 'printer')
                <div><label class="block text-sm font-medium text-gray-700">IP</label><input type="text" name="metadata[ip]" id="devicePrinterEditIp" class="w-full rounded border-gray-300"></div>
                <div><label class="block text-sm font-medium text-gray-700">Modelo</label><input type="text" name="metadata[model]" id="devicePrinterEditModel" class="w-full rounded border-gray-300"></div>
            @elseif($key === 'tv')
                <div><label class="block text-sm font-medium text-gray-700">Localização</label><input type="text" name="metadata[location]" id="deviceTvEditLocation" class="w-full rounded border-gray-300"></div>
            @elseif($key === 'scan')
                <div><label class="block text-sm font-medium text-gray-700">Setor</label><input type="text" name="metadata[sector]" id="deviceScanEditSector" class="w-full rounded border-gray-300"></div>
            @elseif($key === 'phone')
                <div><label class="block text-sm font-medium text-gray-700">Ramal</label><input type="text" name="metadata[extension]" id="devicePhoneEditExtension" class="w-full rounded border-gray-300"></div>
            @elseif($key === 'ap')
                <div><label class="block text-sm font-medium text-gray-700">SSID</label><input type="text" name="metadata[ssid]" id="deviceApEditSsid" class="w-full rounded border-gray-300"></div>
                <div><label class="block text-sm font-medium text-gray-700">Localização</label><input type="text" name="metadata[location]" id="deviceApEditLocation" class="w-full rounded border-gray-300"></div>
            @endif
            <div><label class="block text-sm font-medium text-gray-700">Observações (opcional)</label><textarea name="observacoes" class="device-edit-observacoes w-full rounded border-gray-300 text-sm" rows="2"></textarea></div>
            <div class="border-t border-gray-100 pt-3 mt-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto do dispositivo</label>
                <p class="text-xs text-gray-500 mb-2">Opcional — JPEG, PNG, WebP ou GIF; máx. 5&nbsp;MB.</p>
                <div id="device{{ $suffix }}EditPhotoPreviewWrap" class="hidden mb-2">
                    <img id="device{{ $suffix }}EditPhotoPreview" src="" alt="" class="max-h-36 w-full rounded-lg border border-gray-200 object-cover">
                </div>
                <input type="file" name="device_photo" id="device{{ $suffix }}EditDevicePhoto" accept="image/jpeg,image/png,image/webp,image/gif" class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100">
                <label class="mt-2 inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="remove_device_photo" id="device{{ $suffix }}EditRemovePhoto" value="1" class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                    <span>Remover foto atual</span>
                </label>
            </div>
        </form>
        <div class="p-5 border-t border-gray-200 flex justify-end gap-2">
            <button type="button" onclick="closeDevice{{ $suffix }}EditModal()" class="px-4 py-2 bg-gray-200 rounded font-medium">Cancelar</button>
            <button type="submit" form="device{{ $suffix }}EditForm" class="px-4 py-2 btn-engehub-yellow rounded font-medium">Salvar</button>
        </div>
    </div>
</div>
@endif
@endforeach

@if($canEdit)
<div id="deviceOutletEditModal" class="device-modal fixed inset-0 z-[99999] items-center justify-center p-4" aria-modal="true" style="display: none;">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeDeviceOutletEditModal()" aria-hidden="true"></div>
    <div class="relative bg-white rounded-xl shadow-2xl flex flex-col w-full mx-auto my-auto" style="max-width: 28rem;" onclick="event.stopPropagation()">
        <div class="p-5 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Editar ponto — <span id="deviceOutletEditPointCode"></span></h3>
        </div>
        <form id="deviceOutletEditForm" class="p-5 space-y-3" enctype="multipart/form-data">
            <input type="hidden" class="device-edit-code" id="deviceOutletEditCodeHidden">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="deviceOutletEditOutletType">Tipo do ponto</label>
                <select id="deviceOutletEditOutletType" name="metadata[outlet_type]" class="w-full rounded border-gray-300 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    <option value="">— Não definido —</option>
                    <option value="network">Rede</option>
                    <option value="phone">Telefone</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="deviceOutletEditObservacoes">Observações (opcional)</label>
                <textarea id="deviceOutletEditObservacoes" name="observacoes" class="device-edit-observacoes w-full rounded border-gray-300 text-sm" rows="2" maxlength="500"></textarea>
            </div>
            <div class="border-t border-gray-100 pt-3 mt-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto do dispositivo</label>
                <p class="text-xs text-gray-500 mb-2">Opcional — JPEG, PNG, WebP ou GIF; máx. 5&nbsp;MB.</p>
                <div id="deviceOutletEditPhotoPreviewWrap" class="hidden mb-2">
                    <img id="deviceOutletEditPhotoPreview" src="" alt="" class="max-h-36 w-full rounded-lg border border-gray-200 object-cover">
                </div>
                <input type="file" name="device_photo" id="deviceOutletEditDevicePhoto" accept="image/jpeg,image/png,image/webp,image/gif" class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100">
                <label class="mt-2 inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="remove_device_photo" id="deviceOutletEditRemovePhoto" value="1" class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                    <span>Remover foto atual</span>
                </label>
            </div>
        </form>
        <div class="p-5 border-t border-gray-200 flex justify-end gap-2">
            <button type="button" onclick="closeDeviceOutletEditModal()" class="px-4 py-2 bg-gray-200 rounded font-medium">Cancelar</button>
            <button type="submit" form="deviceOutletEditForm" class="px-4 py-2 btn-engehub-yellow rounded font-medium">Salvar</button>
        </div>
    </div>
</div>
@endif
