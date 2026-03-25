<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Tab;
use App\Models\Category;
use App\Models\DataCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class CardController extends Controller
{
    public function index()
    {
        $tabs = Tab::withCount('cards')->with(['cards' => function($query) {
            $query->orderBy('name', 'asc');
        }])->orderBy('order', 'asc')->get();
        
        return view('admin.cards.index', compact('tabs'));
    }

    public function create()
    {
        $tabs = Tab::orderBy('order')->get();
        $categories = Category::orderBy('name')->get();
        $datacenters = DataCenter::orderBy('name')->get();
        
        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.cards.create', compact('tabs', 'categories', 'datacenters'))->render()
            ]);
        }
        
        return view('admin.cards.create', compact('tabs', 'categories', 'datacenters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'required|string|max:255',
            'tab_id' => 'required|exists:tabs,id',
            'category_id' => 'nullable|exists:categories,id',
            'data_center_id' => 'nullable|exists:data_centers,id',
            'icon' => 'nullable|string|max:255',
            'custom_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
            'monitor_status' => 'boolean',
            'monitoring_type' => 'required|in:http,ping'
        ]);

        $data = $request->all();
        $data['monitor_status'] = $request->has('monitor_status');

        // Upload do ícone personalizado
        if ($request->hasFile('custom_icon')) {
            $customIcon = $request->file('custom_icon');
            $customIconName = time() . '_' . $customIcon->getClientOriginalName();
            $customIcon->storeAs('public/custom_icons', $customIconName);
            $data['custom_icon_path'] = 'custom_icons/' . $customIconName;
        }

        // Upload do arquivo
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/files', $fileName);
            $data['file_path'] = 'files/' . $fileName;
        }

        $card = Card::create($data);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Card criado com sucesso!',
                'redirect' => route('admin.cards.index')
            ]);
        }

        return redirect()->route('admin.cards.index')->with('success', 'Card criado com sucesso!');
    }

    public function edit(Card $card)
    {
        $tabs = Tab::orderBy('order')->get();
        $categories = Category::orderBy('name')->get();
        $datacenters = DataCenter::orderBy('name')->get();
        
        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.cards.edit', compact('card', 'tabs', 'categories', 'datacenters'))->render()
            ]);
        }
        
        return view('admin.cards.edit', compact('card', 'tabs', 'categories', 'datacenters'));
    }

    public function update(Request $request, Card $card)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'required|string|max:255',
            'tab_id' => 'required|exists:tabs,id',
            'category_id' => 'nullable|exists:categories,id',
            'data_center_id' => 'nullable|exists:data_centers,id',
            'icon' => 'nullable|string|max:255',
            'custom_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
            'monitor_status' => 'boolean',
            'monitoring_type' => 'required|in:http,ping',
            'remove_custom_icon' => 'boolean',
            'remove_file' => 'boolean'
        ]);

        $data = $request->all();
        $data['monitor_status'] = $request->has('monitor_status');

        // Remover ícone personalizado se solicitado
        if ($request->has('remove_custom_icon') && $request->remove_custom_icon) {
            if ($card->custom_icon_path) {
                Storage::disk('public')->delete($card->custom_icon_path);
                $data['custom_icon_path'] = null;
            }
        } else if ($request->hasFile('custom_icon')) {
            // Upload do novo ícone personalizado
            if ($card->custom_icon_path) {
                Storage::disk('public')->delete($card->custom_icon_path);
            }
            $customIcon = $request->file('custom_icon');
            $customIconName = time() . '_' . $customIcon->getClientOriginalName();
            $customIcon->storeAs('public/custom_icons', $customIconName);
            $data['custom_icon_path'] = 'custom_icons/' . $customIconName;
        }

        // Remover arquivo se solicitado
        if ($request->has('remove_file') && $request->remove_file) {
            if ($card->file_path) {
                Storage::disk('public')->delete($card->file_path);
                $data['file_path'] = null;
            }
        } else if ($request->hasFile('file')) {
            // Upload do novo arquivo
            if ($card->file_path) {
                Storage::disk('public')->delete($card->file_path);
            }
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/files', $fileName);
            $data['file_path'] = 'files/' . $fileName;
        }

        $card->update($data);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Card atualizado com sucesso!',
                'redirect' => route('admin.cards.index')
            ]);
        }

        return redirect()->route('admin.cards.index')->with('success', 'Card atualizado com sucesso!');
    }

    public function destroy(Card $card)
    {
        // Remover arquivos associados
        if ($card->custom_icon_path) {
            Storage::disk('public')->delete($card->custom_icon_path);
        }
        if ($card->file_path) {
            Storage::disk('public')->delete($card->file_path);
        }

        $card->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Card excluído com sucesso!',
                'redirect' => route('admin.cards.index')
            ]);
        }

        return redirect()->route('admin.cards.index')->with('success', 'Card excluído com sucesso!');
    }

    public function checkStatus(Card $card)
    {
        if (!$card->monitor_status) {
            return response()->json([
                'success' => false,
                'message' => 'Monitoramento não está ativado para este card'
            ]);
        }

        $status = $card->checkStatus();
        
        return response()->json([
            'success' => true,
            'status' => $card->status,
            'status_text' => $card->status_text,
            'status_class' => $card->status_class,
            'response_time' => $card->response_time,
            'last_check' => $card->last_status_check ? $card->last_status_check->format('d/m/Y H:i:s') : null
        ]);
    }

    private function processCustomIcon($file)
    {
        // Criar nome único para o arquivo
        $filename = 'custom_icons/' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Redimensionar a imagem para 32x32 pixels
        $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $image = $manager->read($file);
        $image->resize(32, 32);
        
        // Salvar no storage
        Storage::disk('public')->put($filename, $image->encode());
        
        return $filename;
    }

    public function logins(Card $card)
    {
        // Verificar se o usuário tem permissão para acessar este card
        $hasPermission = false;
        
        // Verificar se é usuário admin com permissão total OU usuário comum com view_passwords
        if (auth()->check() && auth()->user()->canViewPasswords()) {
            $hasPermission = true;
        }
        // Verificar se é usuário system com acesso a este card específico
        elseif (auth()->guard('system')->check() && auth()->guard('system')->user()->canViewSystem($card->id)) {
            $hasPermission = true;
        }
        
        \Log::info('CardController::logins - Verificação de permissões', [
            'card_id' => $card->id,
            'card_name' => $card->name,
            'web_auth' => auth()->check(),
            'system_auth' => auth()->guard('system')->check(),
            'web_user_id' => auth()->check() ? auth()->id() : null,
            'web_user_name' => auth()->check() ? auth()->user()->name : null,
            'system_user_id' => auth()->guard('system')->check() ? auth()->guard('system')->id() : null,
            'has_permission' => $hasPermission,
            'can_view_passwords' => auth()->check() ? auth()->user()->canViewPasswords() : false
        ]);
        
        if (!$hasPermission) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para acessar os logins deste sistema. Verifique suas permissões com o administrador.'
                ], 403);
            }
            
            abort(403, 'Você não tem permissão para acessar os logins deste sistema.');
        }

        // Buscar logins e senhas do sistema
        $systemLogins = $card->systemLogins()->orderBy('title')->get();
        \Log::info('=== DEBUG: CardController::logins ===');
        \Log::info('Card ID: ' . $card->id . ', Name: ' . $card->name);
        \Log::info('Total logins encontrados: ' . $systemLogins->count());
        \Log::info('Auth guard: ' . (auth()->guard('system')->check() ? 'system' : 'web'));
        \Log::info('Auth user: ' . (auth()->check() ? auth()->user()->name : 'null'));
        
        // Aplicar filtro granular apenas para usuários comuns (não administradores)
        if (auth()->check()) {
            $userId = auth()->id();
            $user = auth()->user();
            
            // Verificar se é administrador (tem full_access)
            $isAdmin = $user->isAdmin();
            
            if ($isAdmin) {
                \Log::info('Administrador logado - sem filtro aplicado');
            } else {
                \Log::info('Usuário comum logado - aplicando filtro granular para usuário ID: ' . $userId);
                $systemLogins = $systemLogins->filter(function ($login) use ($userId) {
                    return $login->canUserView($userId);
                });
                \Log::info('Logins após filtro granular: ' . $systemLogins->count());
            }
        } elseif (auth()->guard('system')->check()) {
            $systemUserId = auth()->guard('system')->id();
            \Log::info('Aplicando filtro para usuário system ID: ' . $systemUserId);
            $systemLogins = $systemLogins->filter(function ($login) use ($systemUserId) {
                return $login->canUserView($systemUserId);
            });
            \Log::info('Logins após filtro: ' . $systemLogins->count());
        } else {
            \Log::info('Nenhum usuário logado - sem filtro aplicado');
        }

        // Determinar qual view usar baseado no tipo de usuário
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        $viewName = $isAdmin ? 'admin.cards.logins' : 'admin.cards.logins-user';

        if (request()->ajax()) {
            return response()->json([
                'html' => view($viewName, compact('card', 'systemLogins'))->render()
            ]);
        }
        
        return view($viewName, compact('card', 'systemLogins'));
    }
} 