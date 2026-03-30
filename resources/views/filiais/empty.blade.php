@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-map-marked-alt mr-2" style="color: #E9B32C;"></i>
            Filiais
        </h2>
    </div>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center py-12">
                    <i class="fas fa-map text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">Não há mapas de rede ativos no momento.</p>
                    @if(auth()->guard('web')->check() && auth()->guard('web')->user()->canAccessNav(\App\Support\NavPermission::ADMIN_NETWORK_MAPS))
                        <p class="text-sm text-gray-500 mt-2">
                            <a href="{{ route('admin.network-maps.index') }}" class="text-amber-700 font-medium hover:underline">Gerenciar Mapas de Rede</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
