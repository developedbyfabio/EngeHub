<?php

namespace App\Http\Controllers;

use App\Models\Dvr;
use App\Models\Camera;
use App\Models\CameraChecklist;
use App\Models\CameraChecklistItem;
use App\Models\CameraChecklistAnexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CameraController extends Controller
{
    /**
     * Tela principal de Câmeras (lista DVRs disponíveis para checklist).
     */
    public function index(Request $request)
    {
        $dvrs = Dvr::with(['cameras' => fn ($q) => $q->whereIn('status', [Camera::STATUS_ATIVO, Camera::STATUS_AGUARDANDO_CORRECAO])->orderBy('ordem')->orderBy('created_at')])
            ->withCount(['cameras' => fn ($q) => $q->whereIn('status', [Camera::STATUS_ATIVO, Camera::STATUS_AGUARDANDO_CORRECAO])])
            ->where('status', 'ativo')
            ->orderBy('nome')
            ->get();

        $userId = auth()->check() ? auth()->user()->id : null;

        // Excluir checklists em andamento com mais de 9 horas
        $limite = now()->subHours(9);
        CameraChecklist::where('status', 'em_andamento')
            ->where('iniciado_em', '<', $limite)
            ->each(fn ($c) => $c->delete());

        $checklistsEmAndamento = CameraChecklist::where('status', 'em_andamento')
            ->when($userId, fn ($q) => $q->where('user_id', $userId))
            ->when(!$userId, fn ($q) => $q->whereNull('user_id'))
            ->with(['dvr', 'dvrs'])
            ->orderByDesc('iniciado_em')
            ->limit(10)
            ->get();

        // Histórico: checklists finalizados com filtros
        $period = $request->get('period', 'week');
        $dvrFilter = $request->get('dvr_id');
        $checklistsQuery = CameraChecklist::where('status', 'finalizado')
            ->with(['dvr', 'dvrs', 'itens'])
            ->orderByDesc('finalizado_em');

        if ($period === 'today') {
            $checklistsQuery->whereDate('finalizado_em', now()->toDateString());
        } elseif ($period === 'week') {
            $checklistsQuery->whereBetween('finalizado_em', [now()->subDays(7)->startOfDay(), now()->endOfDay()]);
        } elseif ($period === 'month') {
            $checklistsQuery->whereBetween('finalizado_em', [now()->subDays(30)->startOfDay(), now()->endOfDay()]);
        }

        if ($dvrFilter) {
            $checklistsQuery->where(function ($q) use ($dvrFilter) {
                $q->where('dvr_id', $dvrFilter)
                    ->orWhereHas('dvrs', fn ($sq) => $sq->where('dvr_id', $dvrFilter));
            });
        }

        $checklistsFinalizados = $checklistsQuery->limit(50)->get();

        return view('cameras.index', compact('dvrs', 'checklistsEmAndamento', 'checklistsFinalizados', 'period', 'dvrFilter'));
    }

    /**
     * Iniciar novo checklist (aceita múltiplos DVRs - um único checklist).
     */
    public function storeChecklist(Request $request)
    {
        $validated = $request->validate([
            'dvr_ids' => 'required|array',
            'dvr_ids.*' => 'required|integer|exists:dvrs,id',
            'camera_ids' => 'nullable|array',
            'camera_ids.*' => 'integer|exists:cameras,id',
            'responsavel_nome' => 'nullable|string|max:255',
        ]);

        $user = auth()->user() ?? auth()->guard('system')->user();
        $responsavel = $validated['responsavel_nome'] ?? $user?->name ?? 'Operador';
        $dvrIds = $validated['dvr_ids'];
        $cameraIds = $validated['camera_ids'] ?? [];
        $firstDvr = Dvr::findOrFail($dvrIds[0]);

        $checklist = CameraChecklist::create([
            'dvr_id' => $firstDvr->id,
            'user_id' => $user?->id,
            'responsavel_nome' => $responsavel,
            'status' => CameraChecklist::STATUS_EM_ANDAMENTO,
            'iniciado_em' => now(),
        ]);

        $checklist->dvrs()->attach($dvrIds);

        $camerasToAdd = collect();
        foreach ($dvrIds as $dvrId) {
            $dvr = Dvr::findOrFail($dvrId);
            $dvrCameras = $dvr->cameras()->whereIn('status', [Camera::STATUS_ATIVO, Camera::STATUS_AGUARDANDO_CORRECAO])->orderBy('ordem')->orderBy('created_at')->get();
            if (empty($cameraIds)) {
                $camerasToAdd = $camerasToAdd->merge($dvrCameras);
            } else {
                $camerasToAdd = $camerasToAdd->merge($dvrCameras->whereIn('id', $cameraIds));
            }
        }
        $camerasToAdd = $camerasToAdd->unique('id')->values();

        foreach ($camerasToAdd as $camera) {
            CameraChecklistItem::create([
                'camera_checklist_id' => $checklist->id,
                'camera_id' => $camera->id,
                'status_operacional' => CameraChecklistItem::STATUS_NAO_VERIFICADA,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('cameras.checklists.show', $checklist),
            ]);
        }
        return redirect()->route('cameras.checklists.show', $checklist);
    }

    /**
     * Apagar todo o histórico de checklists (exige senha).
     */
    public function apagarHistorico(Request $request)
    {
        $senha = $request->input('senha');
        $senhaCorreta = config('app.cameras_delete_history_password');

        if ($senha !== $senhaCorreta) {
            return response()->json(['success' => false, 'message' => 'Senha incorreta.'], 422);
        }

        $apagados = CameraChecklist::whereIn('status', [
            CameraChecklist::STATUS_FINALIZADO,
            CameraChecklist::STATUS_CANCELADO,
        ])->count();

        foreach (CameraChecklist::whereIn('status', [
            CameraChecklist::STATUS_FINALIZADO,
            CameraChecklist::STATUS_CANCELADO,
        ])->get() as $checklist) {
            $checklist->itens()->delete();
            $checklist->anexos()->each(function ($anexo) {
                \Storage::disk('public')->delete($anexo->caminho_arquivo);
            });
            $checklist->anexos()->delete();
            $checklist->delete();
        }

        $message = $apagados === 1
            ? '1 registro apagado com sucesso.'
            : "{$apagados} registros apagados com sucesso.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('cameras.index'),
            ]);
        }
        return redirect()->route('cameras.index')->with('success', $message);
    }

    /**
     * Exibir/continuar checklist.
     */
    public function showChecklist(CameraChecklist $checklist)
    {
        $checklist->load(['itens.camera.dvr', 'dvr', 'dvrs', 'anexos.dvr']);
        $itensPorDvr = $checklist->itens->groupBy(fn ($i) => $i->camera->dvr_id);

        $cameraIds = $checklist->itens->pluck('camera_id')->unique()->values();
        $problemaHistoricoPorCamera = CameraChecklistItem::where(function ($q) {
                $q->where('problema', true)
                    ->orWhereNotNull('descricao_problema')
                    ->orWhereNotNull('acao_corretiva_necessaria')
                    ->orWhereNotNull('acao_corretiva_realizada');
            })
            ->whereIn('camera_id', $cameraIds)
            ->with('cameraChecklist')
            ->orderBy('created_at')
            ->get()
            ->groupBy('camera_id');

        $totalDvrsAtivos = Dvr::where('status', 'ativo')->count();
        $mostrarTodosDvrs = $totalDvrsAtivos > 0 && $checklist->dvrs->count() >= $totalDvrsAtivos;

        return view('cameras.checklist', compact('checklist', 'itensPorDvr', 'problemaHistoricoPorCamera', 'mostrarTodosDvrs'));
    }

    /**
     * Exibir detalhes do checklist (somente leitura).
     */
    public function showChecklistDetalhes(CameraChecklist $checklist)
    {
        $checklist->load(['itens.camera.dvr', 'dvr', 'dvrs', 'anexos.dvr']);
        $itensPorDvr = $checklist->itens->groupBy(fn ($i) => $i->camera->dvr_id);

        $cameraIds = $checklist->itens->pluck('camera_id')->unique()->values();
        $problemaHistoricoPorCamera = CameraChecklistItem::where(function ($q) {
                $q->where('problema', true)
                    ->orWhereNotNull('descricao_problema')
                    ->orWhereNotNull('acao_corretiva_necessaria')
                    ->orWhereNotNull('acao_corretiva_realizada');
            })
            ->whereIn('camera_id', $cameraIds)
            ->with('cameraChecklist')
            ->orderBy('created_at')
            ->get()
            ->groupBy('camera_id');

        $totalDvrsAtivos = Dvr::where('status', 'ativo')->count();
        $mostrarTodosDvrs = $totalDvrsAtivos > 0 && $checklist->dvrs->count() >= $totalDvrsAtivos;

        return view('cameras.checklist-detalhes', compact('checklist', 'itensPorDvr', 'problemaHistoricoPorCamera', 'mostrarTodosDvrs'));
    }

    /**
     * Salvar/atualizar item do checklist (autosave).
     */
    public function storeItem(Request $request, CameraChecklist $checklist)
    {
        if ($checklist->status !== CameraChecklist::STATUS_EM_ANDAMENTO) {
            return response()->json(['success' => false, 'message' => 'Checklist já finalizado ou cancelado.'], 422);
        }

        $validated = $request->validate([
            'camera_id' => 'required|exists:cameras,id',
            'status_operacional' => 'nullable|in:online,offline,com_alerta,nao_verificada',
            'online' => 'nullable|boolean',
            'angulo_correto' => 'nullable|boolean',
            'gravando' => 'nullable|boolean',
            'problema' => 'nullable|boolean',
            'descricao_problema' => 'nullable|string|max:1000',
            'acao_corretiva_necessaria' => 'nullable|string|max:1000',
            'acao_corretiva_realizada' => 'nullable|string|max:1000',
            'status_acao' => 'nullable|in:pendente,em_andamento,resolvido',
            'motivo_nao_resolvido' => 'nullable|string|max:500',
            'observacao' => 'nullable|string|max:500',
        ]);

        $item = $checklist->itens()->where('camera_id', $validated['camera_id'])->first();
        if (!$item) {
            $item = $checklist->itens()->create([
                'camera_id' => $validated['camera_id'],
                'status_operacional' => CameraChecklistItem::STATUS_NAO_VERIFICADA,
            ]);
        }

        $item->update(array_filter($validated, fn($v) => $v !== null));

        return response()->json(['success' => true, 'item' => $item->fresh()]);
    }

    /**
     * Finalizar checklist.
     */
    public function finalizarChecklist(Request $request, CameraChecklist $checklist)
    {
        if ($checklist->status !== CameraChecklist::STATUS_EM_ANDAMENTO) {
            return response()->json(['success' => false, 'message' => 'Checklist já finalizado ou cancelado.'], 422);
        }

        $checklist->update([
            'status' => CameraChecklist::STATUS_FINALIZADO,
            'finalizado_em' => now(),
            'observacoes_gerais' => $request->input('observacoes_gerais'),
        ]);

        $itensComProblema = $checklist->itens()
            ->where('problema', true)
            ->whereNotNull('acao_corretiva_necessaria')
            ->where('acao_corretiva_necessaria', '!=', '')
            ->pluck('camera_id')
            ->unique();
        if ($itensComProblema->isNotEmpty()) {
            Camera::whereIn('id', $itensComProblema)->update(['status' => Camera::STATUS_AGUARDANDO_CORRECAO]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('cameras.index'),
                'message' => 'Checklist finalizado com sucesso.',
            ]);
        }
        return redirect()->route('cameras.index')->with('success', 'Checklist finalizado.');
    }

    /**
     * Cancelar checklist.
     */
    public function cancelarChecklist(CameraChecklist $checklist)
    {
        if ($checklist->status !== CameraChecklist::STATUS_EM_ANDAMENTO) {
            return response()->json(['success' => false, 'message' => 'Checklist já finalizado ou cancelado.'], 422);
        }

        $checklist->update([
            'status' => CameraChecklist::STATUS_CANCELADO,
            'finalizado_em' => now(),
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('cameras.index'),
                'message' => 'Checklist cancelado.',
            ]);
        }
        return redirect()->route('cameras.index')->with('success', 'Checklist cancelado.');
    }

    /**
     * Upload de print/anexo do DVR.
     */
    public function storeAnexo(Request $request, CameraChecklist $checklist)
    {
        if ($checklist->status !== CameraChecklist::STATUS_EM_ANDAMENTO) {
            return response()->json(['success' => false, 'message' => 'Checklist já finalizado ou cancelado.'], 422);
        }

        $request->validate([
            'anexo' => 'required|file|mimes:jpeg,png,jpg,gif,pdf,webp|max:10240',
            'dvr_id' => 'nullable|integer|exists:dvrs,id',
        ]);

        $file = $request->file('anexo');
        $path = $file->store('camera_checklist_anexos/' . $checklist->id, 'public');

        $anexo = CameraChecklistAnexo::create([
            'camera_checklist_id' => $checklist->id,
            'dvr_id' => $request->input('dvr_id'),
            'caminho_arquivo' => $path,
            'nome_original' => $file->getClientOriginalName(),
            'tipo_arquivo' => $file->getClientMimeType(),
        ]);

        return response()->json(['success' => true, 'anexo' => $anexo]);
    }

    /**
     * Registrar solução para câmera em "Aguardando correção" - volta para Ativo.
     */
    public function storeSolucao(Request $request, CameraChecklist $checklist)
    {
        if ($checklist->status !== CameraChecklist::STATUS_EM_ANDAMENTO) {
            return response()->json(['success' => false, 'message' => 'Checklist já finalizado ou cancelado.'], 422);
        }

        $validated = $request->validate([
            'item_id' => 'required|integer|exists:camera_checklist_itens,id',
            'acao_corretiva_realizada' => 'required|string|max:2000',
            'anexos' => 'nullable|array',
            'anexos.*' => 'file|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $item = $checklist->itens()->findOrFail($validated['item_id']);
        $camera = $item->camera;

        if ($camera->status !== Camera::STATUS_AGUARDANDO_CORRECAO) {
            return response()->json(['success' => false, 'message' => 'Esta câmera não está aguardando correção.'], 422);
        }

        $item->update([
            'acao_corretiva_realizada' => $validated['acao_corretiva_realizada'],
            'status_acao' => CameraChecklistItem::ACAO_RESOLVIDO,
        ]);

        $files = $request->file('anexos') ?? [];
        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $path = $file->store('camera_checklist_anexos/' . $checklist->id, 'public');
                CameraChecklistAnexo::create([
                    'camera_checklist_id' => $checklist->id,
                    'dvr_id' => $camera->dvr_id,
                    'camera_id' => $camera->id,
                    'caminho_arquivo' => $path,
                    'nome_original' => $file->getClientOriginalName(),
                    'tipo_arquivo' => $file->getClientMimeType(),
                ]);
            }
        }

        Camera::where('id', $camera->id)->update(['status' => Camera::STATUS_ATIVO]);

        $anexosCriados = CameraChecklistAnexo::where('camera_checklist_id', $checklist->id)
            ->where('camera_id', $camera->id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Solução registrada. Câmera voltou para status Ativo.',
            'item_id' => $item->id,
            'acao_corretiva_realizada' => $validated['acao_corretiva_realizada'],
            'anexos' => $anexosCriados->map(fn ($a) => asset('storage/' . $a->caminho_arquivo))->values()->toArray(),
        ]);
    }

    /**
     * Remover anexo do checklist.
     */
    public function destroyAnexo(CameraChecklist $checklist, CameraChecklistAnexo $anexo)
    {
        if ($anexo->camera_checklist_id !== $checklist->id) {
            abort(404);
        }
        if ($checklist->status !== CameraChecklist::STATUS_EM_ANDAMENTO) {
            return response()->json(['success' => false, 'message' => 'Checklist já finalizado.'], 422);
        }

        Storage::disk('public')->delete($anexo->caminho_arquivo);
        $anexo->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Limpar histórico de problema de um item (exige senha).
     */
    public function limparHistoricoItem(Request $request, CameraChecklist $checklist, CameraChecklistItem $item)
    {
        $senha = $request->input('senha');
        $senhaCorreta = config('app.cameras_delete_history_password');
        if ($senha !== $senhaCorreta) {
            return response()->json(['success' => false, 'message' => 'Senha incorreta.'], 422);
        }

        $cameraIdsNoChecklist = $checklist->itens->pluck('camera_id')->unique();
        if (!$cameraIdsNoChecklist->contains($item->camera_id)) {
            return response()->json(['success' => false, 'message' => 'Item não pertence a este checklist.'], 404);
        }

        $item->update([
            'problema' => false,
            'descricao_problema' => null,
            'acao_corretiva_necessaria' => null,
            'acao_corretiva_realizada' => null,
            'status_acao' => null,
        ]);

        $outrosComProblema = CameraChecklistItem::where('camera_id', $item->camera_id)
            ->where(function ($q) {
                $q->where('problema', true)
                    ->orWhereNotNull('descricao_problema')
                    ->orWhereNotNull('acao_corretiva_necessaria')
                    ->orWhereNotNull('acao_corretiva_realizada');
            })
            ->exists();

        if (!$outrosComProblema) {
            Camera::where('id', $item->camera_id)->update(['status' => Camera::STATUS_ATIVO]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Visualizar PDF do checklist.
     */
    public function viewPdf(CameraChecklist $checklist)
    {
        set_time_limit(600); // 10 minutos para checklists grandes
        ini_set('memory_limit', '1G');
        $checklist->load(['itens.camera.dvr', 'dvr', 'dvrs', 'anexos']);
        $totalDvrsAtivos = Dvr::where('status', 'ativo')->count();
        $mostrarTodosDvrs = $totalDvrsAtivos > 0 && $checklist->dvrs->count() >= $totalDvrsAtivos;
        $pdf = Pdf::loadView('cameras.checklist-pdf', compact('checklist', 'mostrarTodosDvrs'));
        return $pdf->stream('checklist-cameras-' . $checklist->id . '.pdf');
    }

    /**
     * Baixar PDF do checklist.
     */
    public function downloadPdf(CameraChecklist $checklist)
    {
        set_time_limit(600); // 10 minutos para checklists grandes
        ini_set('memory_limit', '1G');
        $checklist->load(['itens.camera.dvr', 'dvr', 'dvrs', 'anexos']);
        $totalDvrsAtivos = Dvr::where('status', 'ativo')->count();
        $mostrarTodosDvrs = $totalDvrsAtivos > 0 && $checklist->dvrs->count() >= $totalDvrsAtivos;
        $pdf = Pdf::loadView('cameras.checklist-pdf', compact('checklist', 'mostrarTodosDvrs'));
        $dvrNome = ($mostrarTodosDvrs ?? false) ? 'todos-os-dvrs' : ($checklist->dvrs->count() > 0 ? $checklist->dvrs->pluck('nome')->join('-') : ($checklist->dvr?->nome ?? 'checklist'));
        $fileName = 'checklist-' . \Str::slug($dvrNome) . '-' . $checklist->finalizado_em?->format('Y-m-d') . '.pdf';
        return $pdf->download($fileName);
    }
}
