<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Form;
use App\Models\FormLink;
use App\Models\FormQuestion;
use App\Models\FormQuestionOption;
use App\Models\FormResponse;
use App\Models\FormResponseAnswer;
use App\Models\FormTheme;
use App\Models\StandardWeightProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::withCount(['questions', 'responses', 'links'])
            ->orderBy('created_at', 'desc')
            ->get();

        $branches = Branch::withCount('formLinks')->orderBy('name')->get();

        return view('admin.forms.index', compact('forms', 'branches'));
    }

    public function create()
    {
        return view('admin.forms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Form::create($validated);

        return redirect()
            ->route('admin.forms.index')
            ->with('success', 'Formulário criado com sucesso.');
    }

    public function show(Form $form, Request $request)
    {
        $form->load(['themes.questions.options', 'links.branch', 'responses.branch', 'standardWeightProfiles.options']);
        $responseLogs = $form->responses()->with('branch')->orderBy('submitted_at', 'desc')->get();
        $openLog = $request->boolean('open_log');

        return view('admin.forms.show', compact('form', 'responseLogs', 'openLog'));
    }

    public function edit(Form $form)
    {
        return view('admin.forms.edit', compact('form'));
    }

    public function update(Request $request, Form $form)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $form->update($validated);

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Formulário atualizado com sucesso.');
    }

    public function destroy(Form $form)
    {
        $form->delete();

        return redirect()
            ->route('admin.forms.index')
            ->with('success', 'Formulário excluído com sucesso.');
    }

    public function clearData(Request $request, Form $form)
    {
        $request->validate([
            'password' => 'required|string',
            'clear_option' => 'required|in:logs,responses',
        ]);

        if ($request->input('password') !== '@n@lis3') {
            return redirect()
                ->route('admin.forms.show', $form)
                ->with('error', 'Senha incorreta.');
        }

        $count = $form->responses()->count();
        $form->responses()->delete();

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', "Dados de teste apagados. {$count} registro(s) removido(s).");
    }

    public function storeTheme(Request $request, Form $form)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['order'] = $validated['order'] ?? ($form->themes()->max('order') ?? 0) + 1;

        $form->themes()->create($validated);

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Tema adicionado.');
    }

    public function updateTheme(Request $request, Form $form, FormTheme $theme)
    {
        if ($theme->form_id !== $form->id) {
            abort(404);
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $theme->update($validated);

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Tema atualizado.');
    }

    public function destroyTheme(Form $form, FormTheme $theme)
    {
        if ($theme->form_id !== $form->id) {
            abort(404);
        }
        $theme->delete();

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Tema excluído.');
    }

    public function storeQuestion(Request $request, Form $form)
    {
        $validated = $request->validate([
            'theme_id' => 'required|exists:form_themes,id',
            'question_text' => 'required|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $theme = FormTheme::where('form_id', $form->id)->findOrFail($validated['theme_id']);
        $validated['order'] = $validated['order'] ?? ($theme->questions()->max('order') ?? 0) + 1;
        $validated['form_id'] = $form->id;

        $theme->questions()->create($validated);

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Pergunta adicionada.');
    }

    public function updateQuestion(Request $request, Form $form, FormQuestion $question)
    {
        if ($question->form_id !== $form->id) {
            abort(404);
        }
        $validated = $request->validate([
            'question_text' => 'required|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $question->update($validated);

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Pergunta atualizada.');
    }

    public function destroyQuestion(Form $form, FormQuestion $question)
    {
        if ($question->form_id !== $form->id) {
            abort(404);
        }
        $question->delete();

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Pergunta excluída.');
    }

    public function storeOption(Request $request, Form $form, FormQuestion $question)
    {
        if ($question->form_id !== $form->id) {
            abort(404);
        }
        $validated = $request->validate([
            'option_text' => 'required|string|max:255',
            'weight' => 'required|integer',
        ]);

        $maxOrder = $question->options()->max('order') ?? 0;
        $question->options()->create(array_merge($validated, ['order' => $maxOrder + 1]));

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Opção adicionada.');
    }

    public function updateOption(Request $request, Form $form, FormQuestion $question, FormQuestionOption $option)
    {
        if ($question->form_id !== $form->id || $option->question_id !== $question->id) {
            abort(404);
        }
        $validated = $request->validate([
            'option_text' => 'required|string|max:255',
            'weight' => 'required|integer',
        ]);

        $option->update($validated);

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Opção atualizada.');
    }

    public function destroyOption(Form $form, FormQuestion $question, FormQuestionOption $option)
    {
        if ($question->form_id !== $form->id || $option->question_id !== $question->id) {
            abort(404);
        }
        $option->delete();

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Opção excluída.');
    }

    public function reorderOptions(Request $request, Form $form, FormQuestion $question)
    {
        if ($question->form_id !== $form->id) {
            abort(404);
        }

        $validated = $request->validate([
            'option_ids' => 'required|array',
            'option_ids.*' => 'required|integer|exists:form_question_options,id',
        ]);

        $optionIds = $validated['option_ids'];
        $questionOptions = $question->options()->whereIn('id', $optionIds)->pluck('id')->toArray();

        if (count($optionIds) !== count($questionOptions)) {
            return response()->json(['error' => 'IDs inválidos'], 422);
        }

        foreach ($optionIds as $order => $optionId) {
            FormQuestionOption::where('id', $optionId)->where('question_id', $question->id)->update(['order' => $order + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function applyStandardWeights(Request $request, Form $form, FormQuestion $question)
    {
        if ($question->form_id !== $form->id) {
            abort(404);
        }

        $validated = $request->validate([
            'profile_id' => 'required|exists:standard_weight_profiles,id',
        ]);

        $profile = StandardWeightProfile::where('form_id', $form->id)
            ->with('options')
            ->findOrFail($validated['profile_id']);

        $question->options()->delete();

        foreach ($profile->options as $idx => $opt) {
            $question->options()->create([
                'option_text' => $opt->option_text,
                'weight' => $opt->weight,
                'order' => $idx + 1,
            ]);
        }

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Pesos padrão aplicados: ' . $profile->options->count() . ' opções definidas.');
    }

    public function storeLink(Request $request, Form $form)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);

        $exists = FormLink::where('form_id', $form->id)
            ->where('branch_id', $validated['branch_id'])
            ->exists();

        if ($exists) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Já existe um link para esta filial.'], 422);
            }
            return redirect()
                ->route('admin.forms.show', $form)
                ->with('error', 'Já existe um link para esta filial.');
        }

        FormLink::create([
            'form_id' => $form->id,
            'branch_id' => $validated['branch_id'],
            'token' => FormLink::generateToken(),
            'is_active' => true,
        ]);

        if ($request->wantsJson()) {
            $form->load('links.branch');
            return response()->json([
                'success' => true,
                'message' => 'Link criado com sucesso.',
                'html' => view('admin.forms.partials.modal-links-body', compact('form'))->render(),
            ]);
        }

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Link criado com sucesso.');
    }

    public function storeLinksForAll(Request $request, Form $form)
    {
        $linkedBranchIds = FormLink::where('form_id', $form->id)->pluck('branch_id');
        $branches = \App\Models\Branch::whereNotIn('id', $linkedBranchIds)->get();

        $created = 0;
        foreach ($branches as $branch) {
            FormLink::create([
                'form_id' => $form->id,
                'branch_id' => $branch->id,
                'token' => FormLink::generateToken(),
                'is_active' => true,
            ]);
            $created++;
        }

        $message = $created > 0
            ? "Links criados para {$created} filial(is)."
            : 'Todas as filiais já possuem link.';

        if ($request->wantsJson()) {
            $form->load('links.branch');
            return response()->json([
                'success' => true,
                'message' => $message,
                'html' => view('admin.forms.partials.modal-links-body', compact('form'))->render(),
            ]);
        }

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', $message);
    }

    public function toggleLink(Request $request, Form $form, FormLink $link)
    {
        if ($link->form_id !== $form->id) {
            abort(404);
        }
        $link->update(['is_active' => !$link->is_active]);

        if ($request->wantsJson()) {
            $form->load('links.branch');
            return response()->json([
                'success' => true,
                'message' => $link->is_active ? 'Link ativado.' : 'Link desativado.',
                'html' => view('admin.forms.partials.modal-links-body', compact('form'))->render(),
            ]);
        }

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', $link->is_active ? 'Link ativado.' : 'Link desativado.');
    }

    public function destroyLink(Request $request, Form $form, FormLink $link)
    {
        if ($link->form_id !== $form->id) {
            abort(404);
        }
        $link->delete();

        if ($request->wantsJson()) {
            $form->load('links.branch');
            return response()->json([
                'success' => true,
                'message' => 'Link excluído.',
                'html' => view('admin.forms.partials.modal-links-body', compact('form'))->render(),
            ]);
        }

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Link excluído.');
    }

    public function stats(Form $form, Request $request)
    {
        $form->load(['themes.questions.options', 'links']);

        $branchId = $request->query('branch_id');

        $stats = $this->getFormStats($form, $branchId);

        return view('admin.forms.stats', compact('form', 'stats', 'branchId'));
    }

    public function exportCsv(Form $form, Request $request): StreamedResponse
    {
        $form->load('themes.questions');
        $branchId = $request->query('branch_id');

        $query = FormResponse::where('form_id', $form->id)
            ->with(['branch', 'answers.question', 'answers.selectedOption']);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $responses = $query->with(['answers.selectedOption'])->orderBy('submitted_at', 'desc')->get();

        $filename = 'formulario-' . \Str::slug($form->title) . '-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($responses, $form) {
            $handle = fopen('php://output', 'w');
            $header = ['ID', 'Filial', 'Data/Hora'];
            foreach ($form->themes as $theme) {
                foreach ($theme->questions as $q) {
                    $header[] = $theme->title . ' - ' . $q->question_text;
                }
            }
            fputcsv($handle, $header);

            foreach ($responses as $r) {
                $row = [$r->id, $r->branch->name ?? '-', $r->submitted_at->format('d/m/Y H:i')];
                foreach ($form->themes as $theme) {
                    foreach ($theme->questions as $q) {
                        $ans = $r->answers->firstWhere('question_id', $q->id);
                        $row[] = $ans ? $ans->selectedOption->option_text : '-';
                    }
                }
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportPdf(Form $form, Request $request)
    {
        $exportAll = filter_var($request->input('export_all', 0), FILTER_VALIDATE_BOOLEAN);
        $branchIds = $request->input('branch_ids', []);

        if (!$exportAll && empty(array_filter($branchIds ?? []))) {
            return redirect()->route('admin.forms.stats', $form)->with('error', 'Selecione "Todas" ou ao menos uma filial.');
        }

        $form->load(['themes.questions.options', 'links']);
        $pages = [];

        if ($exportAll) {
            $stats = $this->getFormStats($form, null, true);
            $branchTodas = (object) ['id' => null, 'name' => 'Todas'];
            $pages[] = ['branch' => $branchTodas, 'stats' => $stats];
        } else {
            $validIds = array_filter(array_map('intval', $branchIds ?? []));
            $branches = Branch::whereIn('id', $validIds)->orderBy('name')->get();
            foreach ($branches as $branch) {
                $stats = $this->getFormStats($form, $branch->id, false);
                $pages[] = ['branch' => $branch, 'stats' => $stats];
            }
        }

        if (empty($pages)) {
            return redirect()->route('admin.forms.stats', $form)->with('error', 'Selecione "Todas" ou ao menos uma filial.');
        }

        $pdf = Pdf::loadView('admin.forms.stats-pdf', compact('form', 'pages'));
        $filename = 'estatisticas-' . \Str::slug($form->title) . '-' . now()->format('Y-m-d-His') . '.pdf';
        return $pdf->download($filename);
    }

    private function getFormStats(Form $form, ?int $branchId = null, bool $allQuestions = false): array
    {
        $baseQuery = FormResponse::where('form_id', $form->id);

        if ($branchId) {
            $baseQuery->where('branch_id', $branchId);
        }

        $responseIds = (clone $baseQuery)->pluck('id');
        $totalResponses = $responseIds->count();

        $answersQuery = FormResponseAnswer::whereIn('response_id', $responseIds);
        $totalWeightSum = $answersQuery->sum('weight');
        $totalWeightCount = $answersQuery->count();

        $generalAverage = $totalWeightCount > 0
            ? min(5, max(1, round($totalWeightSum / $totalWeightCount, 2)))
            : 0;

        $riskClassification = $this->getRiskClassification($generalAverage);

        $byBranchRaw = DB::table('form_response_answers')
            ->join('form_responses', 'form_response_answers.response_id', '=', 'form_responses.id')
            ->whereIn('form_response_answers.response_id', $responseIds)
            ->select(
                'form_responses.branch_id',
                DB::raw('COUNT(DISTINCT form_responses.id) as total_responses'),
                DB::raw('SUM(form_response_answers.weight) as weight_sum'),
                DB::raw('COUNT(form_response_answers.weight) as weight_count')
            )
            ->groupBy('form_responses.branch_id')
            ->get();

        $branchModels = Branch::whereIn('id', $byBranchRaw->pluck('branch_id'))->get()->keyBy('id');
        $byBranch = $byBranchRaw->map(function ($r) use ($branchModels) {
            $avg = $r->weight_count > 0 ? min(5, max(1, round($r->weight_sum / $r->weight_count, 2))) : 0;
            return [
                'branch' => $branchModels->get($r->branch_id),
                'total' => (int) $r->total_responses,
                'average' => $avg,
                'risk_classification' => $this->getRiskClassification($avg),
            ];
        })->values()->all();

        $byThemeRaw = DB::table('form_response_answers')
            ->join('form_questions', 'form_response_answers.question_id', '=', 'form_questions.id')
            ->whereIn('form_response_answers.response_id', $responseIds)
            ->where('form_questions.form_id', $form->id)
            ->select(
                'form_questions.theme_id',
                DB::raw('SUM(form_response_answers.weight) as weight_sum'),
                DB::raw('COUNT(form_response_answers.weight) as weight_count')
            )
            ->groupBy('form_questions.theme_id')
            ->get();

        $themeModels = FormTheme::withCount('questions')->whereIn('id', $byThemeRaw->pluck('theme_id'))->get()->keyBy('id');
        $byTheme = $byThemeRaw->map(function ($r) use ($themeModels) {
            $theme = $themeModels->get($r->theme_id);
            $avg = $r->weight_count > 0 ? min(5, max(1, round($r->weight_sum / $r->weight_count, 2))) : 0;
            return [
                'theme' => $theme,
                'questions_count' => $theme ? $theme->questions_count : 0,
                'points' => (int) $r->weight_sum,
                'average' => $avg,
                'risk_classification' => $this->getRiskClassification($avg),
                'weight_count' => (int) $r->weight_count,
            ];
        })->values()->all();

        $byQuestion = [];
        foreach ($form->themes ?? [] as $theme) {
            foreach ($theme->questions ?? [] as $q) {
                $qAnswers = FormResponseAnswer::whereIn('response_id', $responseIds)->where('question_id', $q->id);
                $qSum = $qAnswers->sum('weight');
                $qCount = $qAnswers->count();
                $avg = $qCount > 0 ? min(5, max(1, round($qSum / $qCount, 2))) : 0;
                $byQuestion[$q->id] = [
                    'question' => $q,
                    'theme' => $theme,
                    'sum' => $qSum,
                    'count' => $qCount,
                    'average' => $avg,
                    'risk_classification' => $this->getRiskClassification($avg),
                ];
            }
        }

        $criticalQuestions = collect($byQuestion)
            ->sortByDesc('average')
            ->take($allQuestions ? 9999 : 10)
            ->values()
            ->all();

        $byBranchThemeRaw = DB::table('form_response_answers')
            ->join('form_responses', 'form_response_answers.response_id', '=', 'form_responses.id')
            ->join('form_questions', 'form_response_answers.question_id', '=', 'form_questions.id')
            ->whereIn('form_response_answers.response_id', $responseIds)
            ->where('form_questions.form_id', $form->id)
            ->select(
                'form_responses.branch_id',
                'form_questions.theme_id',
                DB::raw('SUM(form_response_answers.weight) as weight_sum'),
                DB::raw('COUNT(form_response_answers.weight) as weight_count')
            )
            ->groupBy('form_responses.branch_id', 'form_questions.theme_id')
            ->get();

        $themes = $form->themes ?? collect();
        $branchThemeMatrix = [];
        foreach ($byBranchRaw as $r) {
            $branch = $branchModels->get($r->branch_id);
            if (!$branch) {
                continue;
            }
            $row = ['branch' => $branch, 'themes' => []];
            foreach ($themes as $theme) {
                $bt = $byBranchThemeRaw->first(fn ($x) => $x->branch_id == $r->branch_id && $x->theme_id == $theme->id);
                if ($bt && $bt->weight_count > 0) {
                    $avg = min(5, max(1, round($bt->weight_sum / $bt->weight_count, 2)));
                    $row['themes'][$theme->id] = [
                        'average' => $avg,
                        'risk_classification' => $this->getRiskClassification($avg),
                    ];
                } else {
                    $row['themes'][$theme->id] = null;
                }
            }
            $branchThemeMatrix[] = $row;
        }

        return [
            'total_responses' => $totalResponses,
            'general_average' => $generalAverage,
            'risk_classification' => $riskClassification,
            'by_theme' => $byTheme,
            'critical_questions' => $criticalQuestions,
            'branch_theme_matrix' => $branchThemeMatrix,
            'themes' => $themes,
        ];
    }

    private function getThemeCode(FormTheme $theme): string
    {
        if (preg_match('/\(([A-Z0-9]+)\)\s*$/', $theme->title ?? '', $m)) {
            return $m[1];
        }
        return \Str::limit($theme->title ?? 'Tema', 8);
    }

    private function getRiskClassification(float $average): array
    {
        if ($average <= 2) {
            return ['label' => 'Baixo risco', 'color' => 'green', 'icon' => '🟢'];
        }
        if ($average <= 3.5) {
            return ['label' => 'Risco moderado', 'color' => 'yellow', 'icon' => '🟡'];
        }
        return ['label' => 'Alto risco', 'color' => 'red', 'icon' => '🔴'];
    }
}
