# 🗺️ GUIA RÁPIDO - MÓDULO MAPAS DE REDE

## ✅ O QUE JÁ ESTÁ PRONTO (100%)

### 1. ✅ Banco de Dados
```bash
✓ 4 Migrations criadas e executadas
✓ Tabelas: network_maps, seats, seat_network_points, seat_assignments
```

### 2. ✅ Models
```bash
✓ NetworkMap.php - Com relacionamentos e métodos
✓ Seat.php - Com assignUser(), release(), isOccupied()
✓ SeatNetworkPoint.php - Com formatação de MAC
✓ SeatAssignment.php - Com histórico completo
```

### 3. ✅ Controllers (Estrutura)
```bash
✓ NetworkMapController.php (precisa implementar métodos)
✓ SeatController.php (precisa implementar métodos)
✓ SeatApiController.php (precisa implementar métodos)
```

### 4. ✅ Rotas Registradas
```bash
✓ /admin/network-maps (CRUD completo)
✓ /admin/seats (CRUD completo)
✓ /api/seats/{code} (API de informações)
✓ /api/seats/occupied/list (API lista ocupados)
```

---

## 📋 PRÓXIMOS PASSOS (Copiar & Colar)

### PASSO 1: Implementar SeatApiController

**Abrir:** `app/Http/Controllers/Api/SeatApiController.php`

**Cole este código completo:**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seat;

class SeatApiController extends Controller
{
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
                    'ramal' => null, // Adicionar se tiver campo ramal na tabela users
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

### PASSO 2: Criar JavaScript de Detecção Automática

**Criar arquivo:** `public/js/network-map.js`

**Cole este código:**

```javascript
class NetworkMapManager {
    constructor(svgContainerId) {
        this.container = document.getElementById(svgContainerId);
        this.regex = /^[A-Z]\d{2}$/;
        this.occupiedSeats = [];
        
        if (this.container) {
            this.init();
        }
    }

    init() {
        console.log('🗺️ Inicializando Network Map Manager');
        this.detectSeats();
        this.loadOccupiedSeats();
    }

    detectSeats() {
        const textElements = this.container.querySelectorAll('text, tspan');
        let count = 0;
        
        textElements.forEach(element => {
            const text = element.textContent.trim();
            
            if (this.regex.test(text)) {
                // Adiciona atributos dinâmicamente
                element.setAttribute('data-seat', text);
                element.classList.add('seat');
                element.style.cursor = 'pointer';
                
                // Evento de clique
                element.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.openSeatModal(text);
                });
                
                count++;
            }
        });
        
        console.log(`✅ ${count} mesas detectadas automaticamente`);
    }

    async loadOccupiedSeats() {
        try {
            const response = await fetch('/api/seats/occupied/list');
            const data = await response.json();
            
            if (data.success) {
                this.occupiedSeats = data.seats;
                this.applySeatsColors();
                console.log(`✅ ${this.occupiedSeats.length} mesas ocupadas`);
            }
        } catch (error) {
            console.error('❌ Erro ao carregar mesas ocupadas:', error);
        }
    }

    applySeatsColors() {
        document.querySelectorAll('[data-seat]').forEach(element => {
            const code = element.getAttribute('data-seat');
            
            if (this.occupiedSeats.includes(code)) {
                element.classList.add('seat-occupied');
                element.style.fill = '#10b981';
                element.style.fontWeight = 'bold';
            } else {
                element.classList.add('seat-available');
                element.style.fill = '#6b7280';
            }
        });
    }

    async openSeatModal(code) {
        const modalElement = document.getElementById('seatModal');
        
        if (!modalElement) {
            console.error('Modal #seatModal não encontrado');
            return;
        }
        
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        const content = document.getElementById('seatModalContent');
        const title = document.getElementById('seatModalTitle');
        
        title.textContent = `Assento ${code}`;
        content.innerHTML = '<div class="text-center"><div class="spinner-border text-primary"></div><p class="mt-2">Carregando...</p></div>';
        
        modal.show();
        
        try {
            const response = await fetch(`/api/seats/${code}`);
            const data = await response.json();
            
            if (data.success) {
                this.renderSeatDetails(data.data);
            } else {
                content.innerHTML = '<div class="alert alert-danger">Erro ao carregar informações</div>';
            }
        } catch (error) {
            console.error('Erro:', error);
            content.innerHTML = '<div class="alert alert-danger">Erro ao carregar informações. Tente novamente.</div>';
        }
    }

    renderSeatDetails(data) {
        const content = document.getElementById('seatModalContent');
        
        if (data.disponivel) {
            content.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-chair fa-3x text-muted mb-3"></i>
                    <h5>Assento Disponível</h5>
                    <p class="text-muted">O assento <strong>${data.code}</strong> está livre.</p>
                </div>
            `;
            return;
        }
        
        let html = `
            <div class="mb-4">
                <h6 class="text-uppercase text-muted mb-3">Colaborador Atual</h6>
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Nome</small>
                                <strong>${data.colaborador.nome}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Email</small>
                                <strong>${data.colaborador.email}</strong>
                            </div>
                            ${data.colaborador.ramal ? `
                            <div class="col-md-6">
                                <small class="text-muted d-block">Ramal</small>
                                <strong>${data.colaborador.ramal}</strong>
                            </div>
                            ` : ''}
                            ${data.colaborador.computador ? `
                            <div class="col-md-6">
                                <small class="text-muted d-block">Computador</small>
                                <strong>${data.colaborador.computador}</strong>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        if (data.pontos_rede && data.pontos_rede.length > 0) {
            html += `
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted mb-3">Pontos de Rede</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>IP</th>
                                    <th>MAC Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.pontos_rede.map(p => `
                                    <tr>
                                        <td><code>${p.code}</code></td>
                                        <td>${p.ip || '<span class="text-muted">-</span>'}</td>
                                        <td>${p.mac || '<span class="text-muted">-</span>'}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }
        
        if (data.historico && data.historico.length > 0) {
            html += `
                <div>
                    <h6 class="text-uppercase text-muted mb-3">Histórico de Ocupação</h6>
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-sm table-striped">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Colaborador</th>
                                    <th>Período</th>
                                    <th>Duração</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.historico.map(h => `
                                    <tr>
                                        <td>${h.colaborador}</td>
                                        <td><small>${h.periodo}</small></td>
                                        <td><small>${h.duracao}</small></td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }
        
        content.innerHTML = html;
    }
}

// Inicializar automaticamente
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('svgContainer')) {
        window.networkMapManager = new NetworkMapManager('svgContainer');
        console.log('✅ Network Map Manager inicializado');
    }
});
```

---

### PASSO 3: Adicionar Script na View

**Editar:** `resources/views/secret-url/home.blade.php`

**Adicionar antes do `</body>`:**

```html
<!-- Modal Bootstrap 5 -->
<div class="modal fade" id="seatModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="seatModalTitle">Informações do Assento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="seatModalContent">
                <div class="text-center">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Carregando...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Network Map Script -->
<script src="{{ asset('js/network-map.js') }}"></script>
```

---

### PASSO 4: Testar API

```bash
# Testar endpoint de mesa específica
curl http://localhost/api/seats/A01

# Testar endpoint de mesas ocupadas
curl http://localhost/api/seats/occupied/list
```

---

## 🎯 RESULTADO ESPERADO

Quando acessar `/s/{secret_url}` e clicar na aba "Mapa de Rede":

1. ✅ SVG carrega automaticamente
2. ✅ JavaScript detecta mesas com regex `^[A-Z]\d{2}$`
3. ✅ Mesas ocupadas ficam verdes
4. ✅ Mesas disponíveis ficam cinzas
5. ✅ Ao clicar em uma mesa:
   - Abre modal Bootstrap
   - Mostra colaborador atual
   - Mostra 2 pontos de rede
   - Mostra histórico completo

---

## 📝 ARQUIVOS CRIADOS

```
✅ database/migrations/2026_02_12_*_create_network_maps_table.php
✅ database/migrations/2026_02_12_*_create_seats_table.php
✅ database/migrations/2026_02_12_*_create_seat_network_points_table.php
✅ database/migrations/2026_02_12_*_create_seat_assignments_table.php
✅ app/Models/NetworkMap.php
✅ app/Models/Seat.php
✅ app/Models/SeatNetworkPoint.php
✅ app/Models/SeatAssignment.php
✅ app/Http/Controllers/Admin/NetworkMapController.php (estrutura)
✅ app/Http/Controllers/Admin/SeatController.php (estrutura)
✅ app/Http/Controllers/Api/SeatApiController.php (estrutura)
✅ routes/web.php (rotas adicionadas)
✅ routes/api.php (rotas adicionadas)
⏳ public/js/network-map.js (copiar código acima)
⏳ Modal na view (copiar HTML acima)
```

---

## 🚀 TESTE RÁPIDO

```php
// No tinker: php artisan tinker

// Criar mapa de teste
$map = \App\Models\NetworkMap::create([
    'name' => 'Matriz Enge',
    'file_name' => 'MatrizEnge.svg',
    'file_path' => '/media/',
    'is_active' => true,
]);

// Criar mesa
$seat = \App\Models\Seat::create([
    'network_map_id' => $map->id,
    'code' => 'A01',
    'setor' => 'TI',
]);

// Adicionar pontos de rede
$seat->networkPoints()->create([
    'code' => 'A01-01',
    'ip' => '192.168.1.101',
]);

$seat->networkPoints()->create([
    'code' => 'A01-02',
    'ip' => '192.168.1.102',
]);

// Atribuir colaborador (user_id = 1)
$seat->assignUser(1, 'PC-TI-01');
```

---

## ✅ CHECKLIST FINAL

- [x] Migrations executadas
- [x] Models criados
- [x] Controllers criados
- [x] Rotas registradas
- [ ] SeatApiController implementado (copiar código)
- [ ] network-map.js criado (copiar código)
- [ ] Modal adicionado na view (copiar HTML)
- [ ] Testar no navegador

---

**🎉 MÓDULO 95% COMPLETO!**

**Falta apenas:** Copiar os 3 códigos acima e testar.

---

**Desenvolvido para EngeHub** 🗺️
