<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\Admin\TabController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DataCenterController;
use App\Http\Controllers\Admin\SystemUserController;
use App\Http\Controllers\Admin\SystemLoginController;
use App\Http\Controllers\Admin\ServerController as AdminServerController;
use App\Http\Controllers\Admin\ServerGroupController;
use App\Http\Controllers\Admin\SectorController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SecretUrlController;
use App\Http\Controllers\Admin\NetworkMapController;
use App\Http\Controllers\Admin\SeatController;
use App\Http\Controllers\Admin\CameraController as AdminCameraController;
use App\Http\Controllers\Admin\FormController as AdminFormController;
use App\Http\Controllers\Admin\BranchController as AdminBranchController;
use App\Http\Controllers\Admin\StandardWeightProfileController as AdminStandardWeightProfileController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\FormController;

// Formulários públicos (anônimos, sem layout do sistema)
Route::middleware(['throttle:30,1'])->prefix('formulario')->name('form.')->group(function () {
    Route::get('/{token}', [FormController::class, 'show'])->name('show');
    Route::post('/{token}', [FormController::class, 'submit'])->name('submit');
});

// Rotas de acesso por URL secreta (ANTES das outras rotas para evitar conflitos)
Route::middleware(['secret.url', 'throttle:60,1'])->prefix('s')->group(function () {
    Route::get('/{secret_url}', [SecretUrlController::class, 'index'])->name('secret.url');
    Route::get('/{secret_url}/cards/{card}/logins', [SecretUrlController::class, 'logins'])->name('secret.url.logins');
});

// Rota pública (acesso livre para todos, mas com informações de autenticação)
Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('public.auth');

// Rota pública para visualizar servidores
Route::get('/servers', [ServerController::class, 'index'])->name('servers.index')->middleware('public.auth');

// Rota para verificação de status de servidores (usuários logados)
Route::post('/servers/{server}/check-status', [ServerController::class, 'checkStatus'])->name('servers.check-status')->middleware('auth');

// Rota pública para acessar logins de cards (usuários comuns precisam acessar)
Route::get('/cards/{card}/logins', [CardController::class, 'logins'])->name('public.cards.logins')->middleware('public.auth');

// Rota de teste do sistema de toast
Route::get('/test-toast', function () {
    return view('test-toast');
})->name('test-toast');

// Rota de teste do sistema de permissões
Route::get('/test-permissions', function () {
    return view('test-permissions');
})->name('test-permissions');

// Rota de teste do modal click outside
Route::get('/test-modal-click', function () {
    return view('test-modal-click');
})->name('test-modal-click');

// Rotas para favoritos (protegidas por autenticação)
Route::middleware(['auth.any'])->prefix('favorites')->name('favorites.')->group(function () {
    Route::post('/{card}/toggle', [FavoriteController::class, 'toggle'])->name('toggle');
    Route::get('/', [FavoriteController::class, 'index'])->name('index');
    Route::get('/{card}/check', [FavoriteController::class, 'check'])->name('check');
});

// Rotas administrativas (protegidas por autenticação e permissões de administrador)
Route::middleware(['auth.any', 'admin.access'])->prefix('admin')->name('admin.')->group(function () {
    // Rotas para gerenciamento de abas
    Route::resource('tabs', TabController::class);

    // Rotas para gerenciamento de cards
    Route::resource('cards', CardController::class);
    Route::get('/cards/{card}/logins', [CardController::class, 'logins'])->name('cards.logins');
    Route::get('/cards/{card}/check-status', [CardController::class, 'checkStatus'])->name('cards.check-status');

    // Rotas para gerenciamento de logins dos sistemas
    Route::resource('system-logins', SystemLoginController::class);
    Route::post('/system-logins/{systemLogin}/toggle-password', [SystemLoginController::class, 'togglePassword'])->name('system-logins.toggle-password');
    Route::get('/system-logins/{systemLogin}/permissions', [SystemLoginController::class, 'permissions'])->name('system-logins.permissions');
    Route::post('/system-logins/{systemLogin}/permissions', [SystemLoginController::class, 'updatePermissions'])->name('system-logins.update-permissions');
    Route::get('/cards/{card}/filtered-logins', [SystemLoginController::class, 'getFilteredLogins'])->name('cards.filtered-logins');

    // Rotas para gerenciamento de categorias
    Route::resource('categories', CategoryController::class);
    Route::get('/categories-get-all', [CategoryController::class, 'getAll'])->name('categories.get-all');

    // Rotas para gerenciamento de data centers
    Route::resource('datacenters', DataCenterController::class);

    // Rotas para gerenciamento de servidores
    Route::resource('servers', AdminServerController::class);
    Route::get('/servers/{server}/check-status', [AdminServerController::class, 'checkStatus'])->name('admin.servers.check-status');
    
    // Rotas para gerenciamento de grupos de servidores
    Route::resource('server-groups', ServerGroupController::class);

    // Rotas para gerenciamento de usuários dos sistemas
    Route::resource('system-users', SystemUserController::class)->parameters(['system-users' => 'user']);
    Route::get('/system-users/{user}/permissions', [SystemUserController::class, 'permissions'])->name('system-users.permissions');
    Route::post('/system-users/{user}/permissions', [SystemUserController::class, 'updatePermissions'])->name('system-users.update-permissions');
    Route::get('/system-users/{user}/secret-url', [SystemUserController::class, 'showSecretUrl'])->name('system-users.secret-url');
    Route::post('/system-users/{user}/secret-url/regenerate', [SystemUserController::class, 'regenerateSecretUrl'])->name('system-users.secret-url.regenerate');
    Route::post('/system-users/{user}/secret-url/toggle', [SystemUserController::class, 'toggleSecretUrl'])->name('system-users.secret-url.toggle');
    Route::post('/system-users/{user}/secret-url/expiration', [SystemUserController::class, 'setSecretUrlExpiration'])->name('system-users.secret-url.expiration');
    
    // Rotas para gerenciamento de SETORES (URLs secretas)
    Route::resource('sectors', SectorController::class)->parameters(['sectors' => 'sector']);
    Route::get('/sectors/{sector}/cards', [SectorController::class, 'cards'])->name('sectors.cards');
    Route::post('/sectors/{sector}/cards', [SectorController::class, 'updateCards'])->name('sectors.update-cards');
    Route::get('/sectors/{sector}/secret-url', [SectorController::class, 'secretUrl'])->name('sectors.secret-url');
    Route::post('/sectors/{sector}/secret-url/update', [SectorController::class, 'updateSecretUrl'])->name('sectors.secret-url.update');
    Route::post('/sectors/{sector}/secret-url/regenerate', [SectorController::class, 'regenerateSecretUrl'])->name('sectors.secret-url.regenerate');
    Route::post('/sectors/{sector}/secret-url/toggle', [SectorController::class, 'toggleSecretUrl'])->name('sectors.secret-url.toggle');
    Route::post('/sectors/{sector}/secret-url/expiration', [SectorController::class, 'setSecretUrlExpiration'])->name('sectors.secret-url.expiration');

    // Mapas de Rede
    Route::resource('network-maps', NetworkMapController::class);
    Route::post('network-maps/{networkMap}/toggle-status', [NetworkMapController::class, 'toggleStatus'])->name('network-maps.toggle-status');
    Route::get('network-maps/{network_map}/seats/{code}', [NetworkMapController::class, 'getSeat'])->name('network-maps.seats.get');
    Route::put('network-maps/{network_map}/seats/{code}', [NetworkMapController::class, 'updateSeat'])->name('network-maps.seats.update');
    Route::post('network-maps/{network_map}/resync-seats', [NetworkMapController::class, 'resyncSeats'])->name('network-maps.resync-seats');
    
    // Mesas (Seats)
    Route::resource('seats', SeatController::class);

    // Câmeras e DVRs
    Route::get('/cameras', [AdminCameraController::class, 'index'])->name('cameras.index');
    Route::get('/cameras/dvrs/create', [AdminCameraController::class, 'createDvrForm'])->name('cameras.dvrs.create');
    Route::post('/cameras/dvrs', [AdminCameraController::class, 'storeDvr'])->name('cameras.dvrs.store');
    Route::get('/cameras/dvrs/{dvr}/edit', [AdminCameraController::class, 'editDvrForm'])->name('cameras.dvrs.edit');
    Route::put('/cameras/dvrs/{dvr}', [AdminCameraController::class, 'updateDvr'])->name('cameras.dvrs.update');
    Route::delete('/cameras/dvrs/{dvr}', [AdminCameraController::class, 'destroyDvr'])->name('cameras.dvrs.destroy');
    Route::post('/cameras/dvrs/{dvr}/toggle-status', [AdminCameraController::class, 'toggleDvrStatus'])->name('cameras.dvrs.toggle-status');
    Route::post('/cameras/dvrs/{dvr}/reorder-cameras', [AdminCameraController::class, 'reorderCameras'])->name('cameras.dvrs.reorder-cameras');
    Route::post('/cameras/dvrs/{dvr}/import-cameras', [AdminCameraController::class, 'importCameras'])->name('cameras.dvrs.import-cameras');
    Route::get('/cameras/cameras/create', [AdminCameraController::class, 'createCameraForm'])->name('cameras.cameras.create');
    Route::post('/cameras/cameras', [AdminCameraController::class, 'storeCamera'])->name('cameras.cameras.store');
    Route::get('/cameras/cameras/{camera}/edit', [AdminCameraController::class, 'editCameraForm'])->name('cameras.cameras.edit');
    Route::put('/cameras/cameras/{camera}', [AdminCameraController::class, 'updateCamera'])->name('cameras.cameras.update');
    Route::delete('/cameras/cameras/{camera}', [AdminCameraController::class, 'destroyCamera'])->name('cameras.cameras.destroy');
    Route::post('/cameras/cameras/{camera}/toggle-status', [AdminCameraController::class, 'toggleCameraStatus'])->name('cameras.cameras.toggle-status');

    // Formulários e Checklists
    Route::get('/forms', [AdminFormController::class, 'index'])->name('forms.index');
    Route::get('/forms/create', [AdminFormController::class, 'create'])->name('forms.create');
    Route::post('/forms', [AdminFormController::class, 'store'])->name('forms.store');
    Route::get('/forms/{form}', [AdminFormController::class, 'show'])->name('forms.show');
    Route::get('/forms/{form}/edit', [AdminFormController::class, 'edit'])->name('forms.edit');
    Route::put('/forms/{form}', [AdminFormController::class, 'update'])->name('forms.update');
    Route::delete('/forms/{form}', [AdminFormController::class, 'destroy'])->name('forms.destroy');
    Route::post('/forms/{form}/questions', [AdminFormController::class, 'storeQuestion'])->name('forms.questions.store');
    Route::put('/forms/{form}/questions/{question}', [AdminFormController::class, 'updateQuestion'])->name('forms.questions.update');
    Route::delete('/forms/{form}/questions/{question}', [AdminFormController::class, 'destroyQuestion'])->name('forms.questions.destroy');
    Route::post('/forms/{form}/questions/{question}/options', [AdminFormController::class, 'storeOption'])->name('forms.options.store');
    Route::post('/forms/{form}/questions/{question}/options/reorder', [AdminFormController::class, 'reorderOptions'])->name('forms.options.reorder');
    Route::put('/forms/{form}/questions/{question}/options/{option}', [AdminFormController::class, 'updateOption'])->name('forms.options.update');
    Route::delete('/forms/{form}/questions/{question}/options/{option}', [AdminFormController::class, 'destroyOption'])->name('forms.options.destroy');
    Route::post('/forms/{form}/links', [AdminFormController::class, 'storeLink'])->name('forms.links.store');
    Route::post('/forms/{form}/links/create-all', [AdminFormController::class, 'storeLinksForAll'])->name('forms.links.store-all');
    Route::post('/forms/{form}/links/{link}/toggle', [AdminFormController::class, 'toggleLink'])->name('forms.links.toggle');
    Route::delete('/forms/{form}/links/{link}', [AdminFormController::class, 'destroyLink'])->name('forms.links.destroy');
    Route::get('/forms/{form}/stats', [AdminFormController::class, 'stats'])->name('forms.stats');
    Route::post('/forms/{form}/clear-data', [AdminFormController::class, 'clearData'])->name('forms.clear-data');
    Route::get('/forms/{form}/export-csv', [AdminFormController::class, 'exportCsv'])->name('forms.export-csv');
    Route::post('/forms/{form}/export-pdf', [AdminFormController::class, 'exportPdf'])->name('forms.export-pdf');
    Route::post('/forms/{form}/themes', [AdminFormController::class, 'storeTheme'])->name('forms.themes.store');
    Route::put('/forms/{form}/themes/{theme}', [AdminFormController::class, 'updateTheme'])->name('forms.themes.update');
    Route::delete('/forms/{form}/themes/{theme}', [AdminFormController::class, 'destroyTheme'])->name('forms.themes.destroy');
    Route::post('/forms/{form}/questions/{question}/apply-standard-weights', [AdminFormController::class, 'applyStandardWeights'])->name('forms.questions.apply-standard-weights');
    Route::post('/forms/{form}/standard-weight-profiles', [AdminStandardWeightProfileController::class, 'store'])->name('forms.standard-weight-profiles.store');
    Route::delete('/forms/{form}/standard-weight-profiles/{profile}', [AdminStandardWeightProfileController::class, 'destroy'])->name('forms.standard-weight-profiles.destroy');

    // Filiais (para formulários)
    Route::get('/branches', [AdminBranchController::class, 'index'])->name('branches.index');
    Route::post('/branches', [AdminBranchController::class, 'store'])->name('branches.store');
    Route::put('/branches/{branch}', [AdminBranchController::class, 'update'])->name('branches.update');
    Route::delete('/branches/{branch}', [AdminBranchController::class, 'destroy'])->name('branches.destroy');

    // Rota de debug para testar autenticação
    Route::get('/debug/auth', function() {
        $data = [
            'web_auth' => auth()->guard('web')->check(),
            'web_user' => auth()->guard('web')->user(),
            'system_auth' => auth()->guard('system')->check(),
            'system_user' => auth()->guard('system')->user(),
            'session_id' => session()->getId(),
            'session_data' => session()->all()
        ];
        
        if (auth()->guard('web')->check()) {
            $user = auth()->guard('web')->user();
            $data['user_permissions'] = $user->userPermissions->toArray();
            $data['can_view_passwords'] = $user->canViewPasswords();
        }
        
        return response()->json($data);
    });
});

// Rotas de câmeras (checklists operacionais)
Route::middleware(['auth.any'])->prefix('cameras')->name('cameras.')->group(function () {
    Route::get('/', [CameraController::class, 'index'])->name('index');
    Route::post('/checklists', [CameraController::class, 'storeChecklist'])->name('checklists.store');
    Route::post('/historico/apagar', [CameraController::class, 'apagarHistorico'])->name('historico.apagar');
    Route::get('/checklists/{checklist}', [CameraController::class, 'showChecklist'])->name('checklists.show');
    Route::get('/checklists/{checklist}/detalhes', [CameraController::class, 'showChecklistDetalhes'])->name('checklists.detalhes');
    Route::post('/checklists/{checklist}/itens', [CameraController::class, 'storeItem'])->name('checklists.itens.store');
    Route::post('/checklists/{checklist}/itens/{item}/limpar-historico', [CameraController::class, 'limparHistoricoItem'])->name('checklists.itens.limpar-historico');
    Route::post('/checklists/{checklist}/finalizar', [CameraController::class, 'finalizarChecklist'])->name('checklists.finalizar');
    Route::post('/checklists/{checklist}/cancelar', [CameraController::class, 'cancelarChecklist'])->name('checklists.cancelar');
    Route::post('/checklists/{checklist}/anexos', [CameraController::class, 'storeAnexo'])->name('checklists.anexos.store');
    Route::post('/checklists/{checklist}/solucao', [CameraController::class, 'storeSolucao'])->name('checklists.solucao.store');
    Route::delete('/checklists/{checklist}/anexos/{anexo}', [CameraController::class, 'destroyAnexo'])->name('checklists.anexos.destroy');
    Route::get('/checklists/{checklist}/pdf', [CameraController::class, 'viewPdf'])->name('checklists.pdf');
    Route::get('/checklists/{checklist}/pdf/download', [CameraController::class, 'downloadPdf'])->name('checklists.pdf.download');
});

// Rotas do Laravel Breeze
require __DIR__.'/auth.php'; 