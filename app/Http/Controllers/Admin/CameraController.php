<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dvr;
use App\Models\Camera;
use App\Models\CameraChecklistItem;
use Illuminate\Http\Request;

class CameraController extends Controller
{
    public function index()
    {
        $dvrs = Dvr::with(['cameras' => fn ($q) => $q->orderBy('ordem')->orderBy('created_at')])->orderBy('nome')->get();

        $cameraIds = $dvrs->pluck('cameras')->flatten()->pluck('id')->unique()->filter()->values();
        $problemaHistoricoPorCamera = CameraChecklistItem::where(function ($q) {
                $q->where('problema', true)
                    ->orWhereNotNull('descricao_problema')
                    ->orWhereNotNull('acao_corretiva_necessaria')
                    ->orWhereNotNull('acao_corretiva_realizada');
            })
            ->whereIn('camera_id', $cameraIds->isEmpty() ? [0] : $cameraIds)
            ->with('cameraChecklist')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('camera_id');

        return view('admin.cameras.index', compact('dvrs', 'problemaHistoricoPorCamera'));
    }

    public function storeDvr(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:500',
            'localizacao' => 'nullable|string|max:255',
            'acesso_web' => 'nullable|string|max:500',
            'status' => 'required|in:ativo,inativo',
        ]);

        Dvr::create($validated);

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'DVR cadastrado com sucesso.']);
        }
        return redirect()->route('admin.cameras.index')->with('success', 'DVR cadastrado com sucesso.');
    }

    public function updateDvr(Request $request, Dvr $dvr)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:500',
            'localizacao' => 'nullable|string|max:255',
            'acesso_web' => 'nullable|string|max:500',
            'status' => 'required|in:ativo,inativo',
        ]);

        $dvr->update($validated);

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'DVR atualizado com sucesso.']);
        }
        return redirect()->route('admin.cameras.index')->with('success', 'DVR atualizado com sucesso.');
    }

    public function destroyDvr(Dvr $dvr)
    {
        $dvr->cameras()->delete();
        $dvr->delete();

        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'DVR excluído com sucesso.']);
        }
        return redirect()->route('admin.cameras.index')->with('success', 'DVR excluído com sucesso.');
    }

    public function importCameras(Request $request, Dvr $dvr)
    {
        $request->validate([
            'fotos' => 'required|array|max:40',
            'fotos.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $maxOrdem = Camera::where('dvr_id', $dvr->id)->max('ordem') ?? 0;
        $importadas = 0;

        foreach ($request->file('fotos') as $file) {
            $nome = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fotoPath = $file->store('cameras', 'public');
            Camera::create([
                'dvr_id' => $dvr->id,
                'nome' => $nome,
                'status' => Camera::STATUS_ATIVO,
                'foto' => $fotoPath,
                'ordem' => ++$maxOrdem,
            ]);
            $importadas++;
        }

        $message = $importadas === 1
            ? '1 câmera importada com sucesso.'
            : "{$importadas} câmeras importadas com sucesso.";

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => $message]);
        }
        return redirect()->route('admin.cameras.index')->with('success', $message);
    }

    public function reorderCameras(Request $request, Dvr $dvr)
    {
        $validated = $request->validate([
            'camera_ids' => 'required|array',
            'camera_ids.*' => 'required|integer|exists:cameras,id',
        ]);

        $cameraIds = $validated['camera_ids'];
        foreach ($cameraIds as $ordem => $cameraId) {
            Camera::where('id', $cameraId)->where('dvr_id', $dvr->id)->update(['ordem' => $ordem + 1]);
        }

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'Ordem atualizada.']);
        }
        return redirect()->route('admin.cameras.index')->with('success', 'Ordem atualizada.');
    }

    public function toggleDvrStatus(Dvr $dvr)
    {
        $dvr->status = $dvr->status === 'ativo' ? 'inativo' : 'ativo';
        $dvr->save();

        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'status' => $dvr->status]);
        }
        return redirect()->back()->with('success', 'Status atualizado.');
    }

    public function storeCamera(Request $request)
    {
        $validated = $request->validate([
            'dvr_id' => 'required|exists:dvrs,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:500',
            'canal' => 'nullable|string|max:50',
            'status' => 'required|in:ativo,inativo',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('cameras', 'public');
        }

        $maxOrdem = Camera::where('dvr_id', $validated['dvr_id'])->max('ordem') ?? 0;
        $validated['ordem'] = $maxOrdem + 1;

        $camera = Camera::create($validated);

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Câmera cadastrada com sucesso.',
                'camera' => [
                    'id' => $camera->id,
                    'nome' => $camera->nome,
                    'canal' => $camera->canal,
                    'foto' => $camera->foto,
                    'status' => $camera->status,
                ],
            ]);
        }
        return redirect()->route('admin.cameras.index')->with('success', 'Câmera cadastrada com sucesso.');
    }

    public function updateCamera(Request $request, Camera $camera)
    {
        $validated = $request->validate([
            'dvr_id' => 'required|exists:dvrs,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:500',
            'canal' => 'nullable|string|max:50',
            'status' => 'required|in:ativo,inativo',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($camera->foto) {
                \Storage::disk('public')->delete($camera->foto);
            }
            $validated['foto'] = $request->file('foto')->store('cameras', 'public');
        }

        $camera->update($validated);

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Câmera atualizada com sucesso.',
                'camera' => [
                    'id' => $camera->id,
                    'nome' => $camera->nome,
                    'canal' => $camera->canal,
                    'foto' => $camera->foto,
                    'status' => $camera->status,
                ],
            ]);
        }
        return redirect()->route('admin.cameras.index')->with('success', 'Câmera atualizada com sucesso.');
    }

    public function destroyCamera(Camera $camera)
    {
        if ($camera->foto) {
            \Storage::disk('public')->delete($camera->foto);
        }
        $camera->delete();

        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'Câmera excluída com sucesso.', 'camera_id' => $camera->id]);
        }
        return redirect()->route('admin.cameras.index')->with('success', 'Câmera excluída com sucesso.');
    }

    public function toggleCameraStatus(Camera $camera)
    {
        $camera->status = $camera->status === 'ativo' ? 'inativo' : 'ativo';
        $camera->save();

        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'status' => $camera->status, 'camera_id' => $camera->id]);
        }
        return redirect()->back()->with('success', 'Status atualizado.');
    }

    /** Retorna HTML do formulário para modal (JSON quando AJAX) */
    public function createDvrForm()
    {
        $html = view('admin.cameras.partials.dvr-form', ['dvr' => null])->render();
        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['html' => $html]);
        }
        return response($html);
    }

    public function editDvrForm(Dvr $dvr)
    {
        $html = view('admin.cameras.partials.dvr-form', compact('dvr'))->render();
        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['html' => $html]);
        }
        return response($html);
    }

    public function createCameraForm(Request $request)
    {
        $dvrs = Dvr::orderBy('nome')->get();
        $preselectedDvrId = $request->get('dvr_id');
        $html = view('admin.cameras.partials.camera-form', [
            'camera' => null,
            'dvrs' => $dvrs,
            'preselectedDvrId' => $preselectedDvrId,
        ])->render();
        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['html' => $html]);
        }
        return response($html);
    }

    public function editCameraForm(Camera $camera)
    {
        $dvrs = Dvr::orderBy('nome')->get();
        $html = view('admin.cameras.partials.camera-form', compact('camera', 'dvrs'))->render();
        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['html' => $html]);
        }
        return response($html);
    }
}
