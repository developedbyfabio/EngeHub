# 🗺️ MÓDULO MAPAS DE REDE - IMPLEMENTAÇÃO COMPLETA

## ✅ PROGRESSO DA IMPLEMENTAÇÃO

### 1. ✅ BANCO DE DADOS - COMPLETO

#### Migrations Criadas:
- `2026_02_12_144719_create_network_maps_table.php`
- `2026_02_12_144719_create_seats_table.php`
- `2026_02_12_144720_create_seat_network_points_table.php`
- `2026_02_12_144720_create_seat_assignments_table.php`

**Para executar as migrations:**
```bash
php artisan migrate --force
```

---

### 2. ✅ MODELS - COMPLETO

#### Models Criados com Relacionamentos:

**NetworkMap** (`app/Models/NetworkMap.php`)
- Relacionamento: hasMany Seat
- Métodos úteis: `getSvgContent()`, `fileExists()`
- Accessors: `full_path`, `file_url`

**Seat** (`app/Models/Seat.php`)
- Relacionamento: belongsTo NetworkMap
- Relacionamento: hasMany NetworkPoints, Assignments
- Relacionamento: hasOne currentAssignment
- Métodos: `assignUser()`, `release()`, `isOccupied()`

**SeatNetworkPoint** (`app/Models/SeatNetworkPoint.php`)
- Relacionamento: belongsTo Seat
- Accessor: `formatted_mac`

**SeatAssignment** (`app/Models/SeatAssignment.php`)
- Relacionamento: belongsTo Seat, User
- Scopes: `active()`, `ended()`
- Accessors: `duration`, `period`

---

### 3. ⚙️ CONTROLLERS - EM CRIAÇÃO

#### Controllers Criados:
- `app/Http/Controllers/Admin/NetworkMapController.php`
- `app/Http/Controllers/Admin/SeatController.php`
- `app/Http/Controllers/Api/SeatApiController.php`

---

## 📋 PRÓXIMOS PASSOS

### 1. Implementar NetworkMapController

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NetworkMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NetworkMapController extends Controller
{
    public function index()
    {
        $maps = NetworkMap::withCount('seats')->latest()->get();
        return view('admin.network-maps.index', compact('maps'));
    }

    public function create()
    {
        return view('admin.network-maps.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:svg|max:10240', // 10MB
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('media'), $filename);

            $map = NetworkMap::create([
                'name' => $validated['name'],
                'file_name' => $filename,
                'file_path' => '/media/',
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return redirect()->route('admin.network-maps.show', $map)
                ->with('success', 'Mapa criado com sucesso!');
        }

        return back()->with('error', 'Erro ao fazer upload do arquivo.');
    }

    public function show(NetworkMap $networkMap)
    {
        $networkMap->load(['seats.currentAssignment.user', 'seats.networkPoints']);
        return view('admin.network-maps.show', compact('networkMap'));
    }

    public function edit(NetworkMap $networkMap)
    {
        return view('admin.network-maps.edit', compact('networkMap'));
    }

    public function update(Request $request, NetworkMap $networkMap)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:svg|max:10240',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('file')) {
            // Delete old file
            if (file_exists($networkMap->full_path)) {
                unlink($networkMap->full_path);
            }

            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('media'), $filename);

            $validated['file_name'] = $filename;
        }

        $networkMap->update($validated);

        return redirect()->route('admin.network-maps.show', $networkMap)
            ->with('success', 'Mapa atualizado com sucesso!');
    }

    public function destroy(NetworkMap $networkMap)
    {
        // Delete file
        if (file_exists($networkMap->full_path)) {
            unlink($networkMap->full_path);
        }

        $networkMap->delete();

        return redirect()->route('admin.network-maps.index')
            ->with('success', 'Mapa excluído com sucesso!');
    }

    public function toggleStatus(NetworkMap $networkMap)
    {
        $networkMap->update(['is_active' => !$networkMap->is_active]);
        
        return back()->with('success', 'Status atualizado com sucesso!');
    }
}
```

---

### 2. Implementar SeatApiController

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use Illuminate\Http\Request;

class SeatApiController extends Controller
{
    /**
     * Retorna informações completas de uma mesa
     */
    public function show($code)
    {
        $seat = Seat::where('code', $code)
            ->with([
                'currentAssignment.user',
                'networkPoints',
                'assignments' => function($query) {
                    $query->with('user')->latest('started_at')->limit(10);
                }
            ])
            ->first();

        if (!$seat) {
            return response()->json([
                'success' => false,
                'message' => 'Assento não encontrado'
            ], 404);
        }

        $currentUser = $seat->currentAssignment?->user;

        return response()->json([
            'success' => true,
            'data' => [
                'code' => $seat->code,
                'setor' => $seat->setor,
                'disponivel' => !$seat->isOccupied(),
                'colaborador' => $currentUser ? [
                    'nome' => $currentUser->name,
                    'email' => $currentUser->email,
                    'computador' => $seat->currentAssignment->computer_name,
                ] : null,
                'pontos_rede' => $seat->networkPoints->map(function($point) {
                    return [
                        'code' => $point->code,
                        'ip' => $point->ip,
                        'mac' => $point->formatted_mac,
                    ];
                }),
                'historico' => $seat->assignments->map(function($assignment) {
                    return [
                        'colaborador' => $assignment->user?->name ?? 'N/A',
                        'computador' => $assignment->computer_name,
                        'periodo' => $assignment->period,
                        'duracao' => $assignment->formatted_duration,
                        'motivo' => $assignment->reason,
                    ];
                }),
            ]
        ]);
    }

    /**
     * Retorna lista de assentos ocupados
     */
    public function occupied()
    {
        $seats = Seat::has('currentAssignment')->pluck('code');

        return response()->json([
            'success' => true,
            'seats' => $seats
        ]);
    }
}
```

---

### 3. Configurar Rotas

**web.php** - Adicionar ao grupo de admin:

```php
use App\Http\Controllers\Admin\NetworkMapController;
use App\Http\Controllers\Admin\SeatController;

Route::middleware(['auth.any', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Mapas de Rede
    Route::resource('network-maps', NetworkMapController::class);
    Route::post('network-maps/{networkMap}/toggle-status', [NetworkMapController::class, 'toggleStatus'])
        ->name('network-maps.toggle-status');
    
    // Mesas (Seats)
    Route::resource('seats', SeatController::class);
});
```

**api.php** - Adicionar rotas de API:

```php
use App\Http\Controllers\Api\SeatApiController;

// Rotas públicas ou com auth
Route::get('/seats/{code}', [SeatApiController::class, 'show']);
Route::get('/seats/occupied/list', [SeatApiController::class, 'occupied']);
```

---

### 4. Criar Views Admin

#### Estrutura de Diretórios:
```
resources/views/admin/network-maps/
├── index.blade.php (listagem)
├── create.blade.php (criar novo)
├── edit.blade.php (editar)
└── show.blade.php (visualizar mapa interativo)
```

---

### 5. JavaScript - Detecção Automática de Mesas

Criar arquivo: `public/js/network-map.js`

```javascript
class NetworkMapManager {
    constructor(svgContainerId) {
        this.container = document.getElementById(svgContainerId);
        this.regex = /^[A-Z]\d{2}$/;
        this.occupiedSeats = [];
        
        this.init();
    }

    init() {
        this.detectSeats();
        this.loadOccupiedSeats();
        this.initializeZoomControls();
    }

    /**
     * Detecta automaticamente os códigos de mesas no SVG
     */
    detectSeats() {
        const textElements = this.container.querySelectorAll('text, tspan');
        
        textElements.forEach(element => {
            const text = element.textContent.trim();
            
            // Verifica se bate com o regex ^[A-Z]\d{2}$
            if (this.regex.test(text)) {
                // Adiciona atributos dinâmicamente
                element.setAttribute('data-seat', text);
                element.classList.add('seat');
                element.style.cursor = 'pointer';
                
                // Adiciona evento de clique
                element.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.openSeatModal(text);
                });
                
                console.log(`✓ Mesa detectada: ${text}`);
            }
        });
    }

    /**
     * Carrega lista de mesas ocupadas via API
     */
    async loadOccupiedSeats() {
        try {
            const response = await fetch('/api/seats/occupied/list');
            const data = await response.json();
            
            if (data.success) {
                this.occupiedSeats = data.seats;
                this.applySeatsColors();
            }
        } catch (error) {
            console.error('Erro ao carregar mesas ocupadas:', error);
        }
    }

    /**
     * Aplica cores baseado no status
     */
    applySeatsColors() {
        document.querySelectorAll('[data-seat]').forEach(element => {
            const code = element.getAttribute('data-seat');
            
            if (this.occupiedSeats.includes(code)) {
                element.classList.add('seat-occupied');
                element.style.fill = '#10b981'; // Verde
            } else {
                element.classList.add('seat-available');
                element.style.fill = '#d1d5db'; // Cinza
            }
        });
    }

    /**
     * Abre modal com informações da mesa
     */
    async openSeatModal(code) {
        // Implementar modal Bootstrap 5
        const modalElement = document.getElementById('seatModal');
        const modal = new bootstrap.Modal(modalElement);
        
        // Loading
        document.getElementById('seatModalContent').innerHTML = '<div class="text-center"><div class="spinner-border"></div></div>';
        
        modal.show();
        
        try {
            const response = await fetch(`/api/seats/${code}`);
            const data = await response.json();
            
            if (data.success) {
                this.renderSeatDetails(data.data);
            }
        } catch (error) {
            console.error('Erro:', error);
        }
    }

    /**
     * Renderiza detalhes da mesa no modal
     */
    renderSeatDetails(data) {
        let html = '';
        
        if (data.disponivel) {
            html = `
                <div class="alert alert-info">
                    <h5>Assento Disponível</h5>
                    <p>O assento <strong>${data.code}</strong> está livre.</p>
                </div>
            `;
        } else {
            html = `
                <h5>Colaborador Atual</h5>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>Nome:</strong> ${data.colaborador.nome}</li>
                    <li class="list-group-item"><strong>Email:</strong> ${data.colaborador.email}</li>
                    <li class="list-group-item"><strong>Computador:</strong> ${data.colaborador.computador}</li>
                </ul>
                
                <h6>Pontos de Rede</h6>
                <table class="table table-sm">
                    <thead><tr><th>Código</th><th>IP</th><th>MAC</th></tr></thead>
                    <tbody>
                        ${data.pontos_rede.map(p => `
                            <tr>
                                <td>${p.code}</td>
                                <td>${p.ip || '-'}</td>
                                <td>${p.mac || '-'}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
                
                <h6>Histórico</h6>
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-sm table-striped">
                        <thead><tr><th>Colaborador</th><th>Período</th><th>Duração</th></tr></thead>
                        <tbody>
                            ${data.historico.map(h => `
                                <tr>
                                    <td>${h.colaborador}</td>
                                    <td>${h.periodo}</td>
                                    <td>${h.duracao}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }
        
        document.getElementById('seatModalContent').innerHTML = html;
    }

    /**
     * Inicializa controles de zoom
     */
    initializeZoomControls() {
        // Usar biblioteca svg-pan-zoom ou implementação customizada
        // (Já implementado na versão anterior do mapa)
    }
}

// Inicializar quando o DOM carregar
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('svgContainer')) {
        window.networkMap = new NetworkMapManager('svgContainer');
    }
});
```

---

### 6. Integrar no Menu Admin

Editar: `resources/views/layouts/admin-app.blade.php` (ou similar)

Adicionar link após "Servidores":

```html
<a href="{{ route('admin.network-maps.index') }}" 
   class="nav-link {{ request()->routeIs('admin.network-maps.*') ? 'active' : '' }}">
    <i class="fas fa-map-marked-alt"></i>
    Mapas de Rede
</a>
```

---

### 7. Integrar na Área de Setores

Editar: `resources/views/secret-url/home.blade.php`

Adicionar nova aba ao lado de "Área do Colaborador" (já implementado anteriormente).

---

## 📊 ESTRUTURA DO BANCO

```sql
network_maps
├── id
├── name
├── file_name
├── file_path
├── is_active
└── timestamps

seats
├── id
├── network_map_id (FK)
├── code (A01, V17...)
├── setor
├── observacoes
└── timestamps

seat_network_points
├── id
├── seat_id (FK)
├── code (A01-01, A01-02)
├── mac_address
├── ip
├── observacoes
└── timestamps

seat_assignments
├── id
├── seat_id (FK)
├── user_id (FK)
├── computer_name
├── started_at
├── ended_at
├── reason
└── timestamps
```

---

## 🚀 COMANDOS PARA EXECUTAR

```bash
# 1. Executar migrations
php artisan migrate --force

# 2. Limpar cache
php artisan cache:clear
php artisan view:clear

# 3. (Opcional) Popular banco com dados de teste
php artisan db:seed NetworkMapSeeder
```

---

## 📝 PRÓXIMAS ETAPAS

1. ✅ Migrations criadas
2. ✅ Models com relacionamentos
3. ⚙️ Controllers (estrutura criada, implementação em andamento)
4. ⏳ Rotas (documentadas acima)
5. ⏳ Views Admin
6. ⏳ JavaScript detecção automática
7. ⏳ Integração menu
8. ⏳ Permissões

---

**Desenvolvido para EngeHub - Sistema de Gerenciamento**
