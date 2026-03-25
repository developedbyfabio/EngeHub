<?php

namespace App\Http\Controllers;

use App\Models\FormLink;
use App\Models\FormResponse;
use App\Models\FormResponseAnswer;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function show(string $token)
    {
        $link = FormLink::where('token', $token)
            ->where('is_active', true)
            ->with(['form.themes' => fn ($q) => $q->orderBy('order')])
            ->firstOrFail();

        $form = $link->form;
        $form->load(['themes.questions.options' => fn ($q) => $q->orderBy('weight')]);

        if (!$form->is_active) {
            abort(404, 'Formulário indisponível.');
        }

        $totalQuestions = $form->themes->sum(fn ($t) => $t->questions->count());
        if ($totalQuestions === 0) {
            abort(404, 'Formulário sem perguntas configuradas.');
        }

        $themesWithQuestions = $form->themes->filter(fn ($t) => $t->questions->isNotEmpty())->values();

        return view('form.show', [
            'form' => $form,
            'link' => $link,
            'token' => $token,
            'themesWithQuestions' => $themesWithQuestions,
        ]);
    }

    public function submit(Request $request, string $token)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|exists:form_question_options,id',
        ]);

        $link = FormLink::where('token', $token)
            ->where('is_active', true)
            ->with('form.themes.questions.options')
            ->firstOrFail();

        $form = $link->form;

        if (!$form->is_active) {
            return back()->withErrors(['token' => 'Formulário indisponível.']);
        }

        foreach ($form->themes as $theme) {
            foreach ($theme->questions as $question) {
                if (!isset($validated['answers'][$question->id])) {
                    return back()->withErrors(['answers' => 'Todas as perguntas são obrigatórias.']);
                }

                $optionId = $validated['answers'][$question->id];
                $option = $question->options->firstWhere('id', $optionId);
                if (!$option) {
                    return back()->withErrors(['answers' => 'Resposta inválida.']);
                }
            }
        }

        $completionTime = null;
        $startedAt = $request->input('started_at');
        if (is_numeric($startedAt) && $startedAt > 0) {
            $started = (int) $startedAt;
            $ended = (int) (now()->timestamp * 1000);
            if ($ended >= $started) {
                $completionTime = (int) round(($ended - $started) / 1000);
            }
        }

        $response = FormResponse::create([
            'form_id' => $form->id,
            'branch_id' => $link->branch_id,
            'submitted_at' => now(),
            'completion_time_seconds' => $completionTime,
        ]);

        foreach ($form->themes as $theme) {
            foreach ($theme->questions as $question) {
                $optionId = $validated['answers'][$question->id];
                $option = $question->options->firstWhere('id', $optionId);

                FormResponseAnswer::create([
                    'response_id' => $response->id,
                    'question_id' => $question->id,
                    'selected_option_id' => $option->id,
                    'weight' => $option->weight,
                ]);
            }
        }

        $formUrl = url("/formulario/{$token}");

        return view('form.thank-you', ['formUrl' => $formUrl]);
    }
}
