@extends('layouts.app')

@section('header')
    <x-page-header title="Gerenciar Mapas de Rede" icon="fas fa-map-marked-alt">
        <x-slot name="actions">
            <a href="{{ route('admin.network-maps.create') }}" class="page-header-btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Novo Mapa
            </a>
        </x-slot>
    </x-page-header>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($maps->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arquivo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesas</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado em</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($maps as $map)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center mr-3">
                                                        <i class="fas fa-map text-amber-600"></i>
                                                    </div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $map->name }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $map->file_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($map->is_active)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativo</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inativo</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $map->seats_count }} mesas</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $map->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <a href="{{ route('admin.network-maps.show', $map) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded bg-amber-100 text-amber-800 hover:bg-amber-200" title="Abrir mapa e editar mesas (A01, B01, etc.)"><i class="fas fa-eye"></i> <span class="hidden sm:inline">Ver mapa</span></a>
                                                    <a href="{{ route('admin.network-maps.edit', $map) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800" title="Alterar nome do mapa ou arquivo SVG"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('admin.network-maps.toggle-status', $map) }}" method="POST" class="inline">@csrf<button type="submit" class="text-gray-600 hover:text-gray-800" title="{{ $map->is_active ? 'Desativar' : 'Ativar' }}"><i class="fas fa-{{ $map->is_active ? 'toggle-on' : 'toggle-off' }}"></i></button></form>
                                                    <form action="{{ route('admin.network-maps.destroy', $map) }}" method="POST" class="inline" onsubmit="return confirm('Excluir este mapa?');">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-800" title="Excluir"><i class="fas fa-trash"></i></button></form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $maps->links() }}</div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-map-marked-alt text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum mapa cadastrado</h3>
                            <p class="text-gray-500 mb-6 max-w-md mx-auto">Cadastre um mapa de rede em SVG para exibir na área do colaborador.</p>
                            <a href="{{ route('admin.network-maps.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest transition" style="background-color: #E9B32C;" onmouseover="this.style.backgroundColor='#d19d20'" onmouseout="this.style.backgroundColor='#E9B32C'">
                                <i class="fas fa-plus mr-2"></i>
                                Cadastrar primeiro mapa
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
