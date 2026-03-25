@extends('layouts.form')

@section('title', $form->title . ' - ' . config('app.name'))

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Tela inicial com título e botão Iniciar --}}
    <div id="form-intro" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-3">{{ $form->title }}</h1>
        @if($form->description)
            <p class="text-gray-600 mb-6 whitespace-pre-line">{{ $form->description }}</p>
        @endif
        <p class="text-sm text-gray-500 mb-6">Este formulário é <strong>100% anônimo</strong>. Suas respostas não serão vinculadas a nenhum dado pessoal.</p>
        <button type="button" onclick="formWizard.start()" class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
            <i class="fas fa-play mr-2"></i> Iniciar
        </button>
    </div>

    {{-- Container do wizard (temas + perguntas) --}}
    <div id="form-wizard" class="hidden mt-8">
        <form action="{{ route('form.submit', $token) }}" method="POST" id="form-responder">
            @csrf
            <input type="hidden" name="started_at" id="form-started-at" value="">
            @foreach($themesWithQuestions as $themeIndex => $theme)
                @php $themeId = 'theme-' . $theme->id; $isLast = $themeIndex === $themesWithQuestions->count() - 1; @endphp

                {{-- Intro do tema --}}
                <div id="{{ $themeId }}-intro" class="form-step hidden bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Agora você vai responder as perguntas do seguinte tema:</h2>
                    <h3 class="text-lg font-medium text-primary-600 mb-3">{{ $theme->title }}</h3>
                    @if($theme->description)
                        <p class="text-gray-600 mb-6 whitespace-pre-line">{{ $theme->description }}</p>
                    @endif
                    <p class="text-sm text-gray-500 mb-6">Este tema possui <strong>{{ $theme->questions->count() }}</strong> perguntas.</p>
                    <button type="button" onclick="formWizard.nextToQuestions({{ $themeIndex }})" class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        <i class="fas fa-arrow-right mr-2"></i> Avançar
                    </button>
                </div>

                {{-- Perguntas do tema --}}
                <div id="{{ $themeId }}-questions" class="form-step hidden bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">{{ $theme->title }}</h2>
                    <p class="text-sm text-gray-500 mb-6">Responda às perguntas abaixo.</p>

                    @foreach($theme->questions as $qIndex => $question)
                        <div class="question-block border-b border-gray-100 pb-6 last:border-0 last:pb-0 mb-6 last:mb-0" data-question-id="{{ $question->id }}">
                            <label class="block text-sm font-medium text-gray-900 mb-3">
                                {{ $qIndex + 1 }}. {{ $question->question_text }}
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2">
                                @foreach($question->options as $option)
                                    <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" class="rounded-full border-gray-300 text-primary-600 focus:ring-primary-500">
                                        <span class="ml-3 text-gray-900">{{ $option->option_text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="pt-6 flex gap-3 justify-between">
                        <button type="button" onclick="formWizard.backToIntro({{ $themeIndex }})" class="px-4 py-2 text-gray-600 hover:text-gray-900 font-medium">
                            <i class="fas fa-arrow-left mr-2"></i> Voltar
                        </button>
                        @if($isLast)
                            <button type="button" onclick="formWizard.submitForm({{ $themeIndex }})" class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                <i class="fas fa-check mr-2"></i> Finalizar
                            </button>
                        @else
                            <button type="button" onclick="formWizard.nextToTheme({{ $themeIndex + 1 }})" class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                Próximo tema <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </form>
    </div>
</div>

<style>
@keyframes question-shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
    20%, 40%, 60%, 80% { transform: translateX(4px); }
}
.question-block.shake {
    animation: question-shake 1s ease-in-out;
}
</style>

<script>
const FORM_CACHE_KEY = 'form-answers-{{ $token }}';
const formWizard = {
    themes: @json($themesWithQuestions->map(fn($t) => ['id' => $t->id, 'questionIds' => $t->questions->pluck('id')->values()->toArray()])->toArray()),
    currentStep: -1,

    start() {
        document.getElementById('form-started-at').value = Date.now().toString();
        document.getElementById('form-intro').classList.add('hidden');
        document.getElementById('form-wizard').classList.remove('hidden');
        formWizard.restoreCache();
        formWizard.setupCacheListeners();
        this.showThemeIntro(0);
    },

    showThemeIntro(index) {
        this.hideAll();
        const theme = this.themes[index];
        if (!theme) return;
        const el = document.getElementById('theme-' + theme.id + '-intro');
        if (el) {
            el.classList.remove('hidden');
            el.scrollIntoView({ behavior: 'smooth' });
        }
        this.currentStep = index;
    },

    showThemeQuestions(index) {
        this.hideAll();
        const theme = this.themes[index];
        if (!theme) return;
        const el = document.getElementById('theme-' + theme.id + '-questions');
        if (el) {
            el.classList.remove('hidden');
            el.scrollIntoView({ behavior: 'smooth' });
        }
        this.currentStep = index;
    },

    hideAll() {
        document.querySelectorAll('.form-step').forEach(el => el.classList.add('hidden'));
    },

    getUnansweredInTheme(themeIndex) {
        const theme = this.themes[themeIndex];
        if (!theme || !theme.questionIds) return [];
        const unanswered = [];
        theme.questionIds.forEach(qId => {
            const input = document.querySelector(`input[name="answers[${qId}]"]:checked`);
            if (!input) unanswered.push(qId);
        });
        return unanswered;
    },

    nextToQuestions(index) {
        this.showThemeQuestions(index);
    },

    backToIntro(index) {
        this.showThemeIntro(index);
    },

    nextToTheme(nextIndex) {
        const currentIndex = this.currentStep;
        const unanswered = this.getUnansweredInTheme(currentIndex);
        if (unanswered.length > 0) {
            const theme = this.themes[currentIndex];
            const container = document.getElementById('theme-' + theme.id + '-questions');
            if (container) {
                container.classList.remove('hidden');
                this.hideAll();
                container.classList.remove('hidden');
            }
            const firstQId = unanswered[0];
            const firstBlock = document.querySelector(`.question-block[data-question-id="${firstQId}"]`);
            if (firstBlock) {
                firstBlock.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstBlock.classList.add('shake');
                setTimeout(() => firstBlock.classList.remove('shake'), 1000);
            }
            if (typeof window.showToast === 'function') {
                window.showToast('Você precisa responder todas as questões para avançar para o próximo tema!', 'warning', 5000);
            }
            return;
        }
        this.showThemeIntro(nextIndex);
    },

    submitForm(themeIndex) {
        const unanswered = this.getUnansweredInTheme(themeIndex);
        if (unanswered.length > 0) {
            const theme = this.themes[themeIndex];
            const container = document.getElementById('theme-' + theme.id + '-questions');
            if (container) {
                container.classList.remove('hidden');
                this.hideAll();
                container.classList.remove('hidden');
            }
            const firstQId = unanswered[0];
            const firstBlock = document.querySelector(`.question-block[data-question-id="${firstQId}"]`);
            if (firstBlock) {
                firstBlock.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstBlock.classList.add('shake');
                setTimeout(() => firstBlock.classList.remove('shake'), 1000);
            }
            if (typeof window.showToast === 'function') {
                window.showToast('Você precisa responder todas as questões para avançar para o próximo tema!', 'warning', 5000);
            }
            return;
        }
        formWizard.clearCache();
        document.getElementById('form-responder').submit();
    },

    saveCache() {
        const answers = {};
        document.querySelectorAll('#form-responder input[name^="answers["]:checked').forEach(inp => {
            const m = inp.name.match(/answers\[(\d+)\]/);
            if (m) answers[m[1]] = inp.value;
        });
        try { localStorage.setItem(FORM_CACHE_KEY, JSON.stringify(answers)); } catch (e) {}
    },

    restoreCache() {
        try {
            const data = localStorage.getItem(FORM_CACHE_KEY);
            if (!data) return;
            const answers = JSON.parse(data);
            for (const [qId, optId] of Object.entries(answers)) {
                const input = document.querySelector(`input[name="answers[${qId}]"][value="${optId}"]`);
                if (input) input.checked = true;
            }
        } catch (e) {}
    },

    clearCache() {
        try { localStorage.removeItem(FORM_CACHE_KEY); } catch (e) {}
    },

    setupCacheListeners() {
        document.querySelectorAll('#form-responder input[name^="answers["]').forEach(inp => {
            inp.addEventListener('change', () => formWizard.saveCache());
        });
    }
};

document.getElementById('form-responder')?.addEventListener('submit', function() {
    formWizard.clearCache();
});

document.addEventListener('DOMContentLoaded', function() {
    formWizard.restoreCache();
});
</script>
@endsection
