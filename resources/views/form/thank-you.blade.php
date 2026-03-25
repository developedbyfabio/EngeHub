@extends('layouts.form')

@section('title', 'Obrigado - ' . config('app.name'))

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
            <i class="fas fa-check text-2xl text-green-600"></i>
        </div>
        <h1 class="text-xl font-semibold text-gray-900 mb-2">Obrigado por responder o formulário!</h1>
        <p class="text-gray-600">Sua resposta foi registrada com sucesso.</p>
        <p class="text-sm text-gray-500 mt-4">Este formulário é anônimo. Você pode fechar esta página.</p>
        @if(isset($formUrl))
            <p class="text-sm text-gray-400 mt-6" id="countdown-msg">A página será reiniciada em <strong id="countdown">30</strong> segundos para a próxima pessoa responder.</p>
        @endif
    </div>
</div>
@if(isset($formUrl))
<script>
(function() {
    let n = 30;
    const el = document.getElementById('countdown');
    const msg = document.getElementById('countdown-msg');
    const interval = setInterval(function() {
        n--;
        if (el) el.textContent = n;
        if (n <= 0) {
            clearInterval(interval);
            if (msg) msg.textContent = 'Redirecionando...';
            window.location.href = '{{ $formUrl }}';
        }
    }, 1000);
})();
</script>
@endif
@endsection
