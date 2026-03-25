# 📁 ESTRUTURA VISUAL DO PROJETO ENGEHUB

## 🌳 ÁRVORE DE DIRETÓRIOS PRINCIPAIS

```
EngeHub/
│
├── 📄 README.md                          # Documentação principal
├── 📄 ANALISE_COMPLETA_PROJETO.md       # Análise completa (este documento)
├── 📄 ESTRUTURA_VISUAL_PROJETO.md       # Estrutura visual
├── 📄 composer.json                      # Dependências PHP
├── 📄 package.json                       # Dependências Node.js
├── 📄 tailwind.config.js                 # Configuração Tailwind
├── 📄 vite.config.js                     # Configuração Vite
│
├── 📂 app/                               # Código da aplicação
│   ├── 📂 Console/
│   │   ├── 📂 Commands/                  # Comandos Artisan
│   │   │   ├── GenerateSecretUrls.php
│   │   │   ├── CheckCardStatus.php
│   │   │   ├── CheckServerStatus.php
│   │   │   └── Test*.php (múltiplos)
│   │   └── Kernel.php
│   │
│   ├── 📂 Exceptions/                    # Tratamento de exceções
│   │
│   ├── 📂 Http/
│   │   ├── 📂 Controllers/               # Controladores
│   │   │   ├── HomeController.php
│   │   │   ├── ServerController.php
│   │   │   ├── SecretUrlController.php
│   │   │   ├── FavoriteController.php
│   │   │   ├── 📂 Admin/                 # Controladores Admin
│   │   │   │   ├── TabController.php
│   │   │   │   ├── CardController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── SectorController.php
│   │   │   │   ├── SystemUserController.php
│   │   │   │   ├── SystemLoginController.php
│   │   │   │   ├── ServerController.php
│   │   │   │   └── ServerGroupController.php
│   │   │   └── 📂 Auth/                  # Autenticação
│   │   │
│   │   ├── 📂 Middleware/                # Middlewares
│   │   │   ├── CheckAnyAuth.php
│   │   │   ├── CheckAdminAccess.php
│   │   │   ├── CheckSecretUrl.php
│   │   │   ├── PublicAccessWithAuthCheck.php
│   │   │   ├── CheckSystemUserAccess.php
│   │   │   ├── ForceLogoutAfterSession.php
│   │   │   └── ValidateSession.php
│   │   │
│   │   ├── 📂 Requests/                  # Form Requests
│   │   │
│   │   └── Kernel.php                    # Kernel HTTP
│   │
│   ├── 📂 Models/                        # Modelos Eloquent
│   │   ├── User.php                      # Usuários admin
│   │   ├── SystemUser.php                # Usuários sistema/setores
│   │   ├── Card.php                      # Cards/sistemas
│   │   ├── Tab.php                       # Abas
│   │   ├── Category.php                 # Categorias
│   │   ├── DataCenter.php               # Data centers
│   │   ├── SystemLogin.php              # Logins dos sistemas
│   │   ├── SystemLoginPermission.php    # Permissões de logins
│   │   ├── UserPermission.php           # Permissões de usuários
│   │   ├── UserFavorite.php             # Favoritos
│   │   ├── Server.php                   # Servidores
│   │   ├── ServerGroup.php              # Grupos de servidores
│   │   └── SecretUrlAccessLog.php       # Logs de acesso
│   │
│   ├── 📂 Providers/                     # Service Providers
│   │
│   └── 📂 View/                          # View Composers
│
├── 📂 bootstrap/                         # Bootstrap da aplicação
│   ├── app.php
│   └── 📂 cache/                         # Cache
│
├── 📂 config/                            # Configurações
│   ├── app.php
│   ├── auth.php                          # Configuração de autenticação
│   ├── database.php                      # Configuração de banco
│   ├── permission.php                    # Spatie Permission
│   └── ...
│
├── 📂 database/                          # Banco de dados
│   ├── 📂 migrations/                    # Migrations
│   │   ├── create_users_table.php
│   │   ├── create_tabs_table.php
│   │   ├── create_cards_table.php
│   │   ├── create_system_users_table.php
│   │   ├── create_system_user_cards_table.php
│   │   ├── create_system_logins_table.php
│   │   ├── create_servers_table.php
│   │   ├── add_secret_url_to_system_users_table.php
│   │   ├── create_secret_url_access_logs_table.php
│   │   └── ... (outras migrations)
│   │
│   └── 📂 seeders/                       # Seeders
│       ├── DatabaseSeeder.php
│       ├── AdminUserSeeder.php
│       └── ...
│
├── 📂 public/                            # Arquivos públicos
│   ├── index.php                         # Entry point
│   ├── 📂 build/                         # Assets compilados
│   ├── 📂 media/                         # Mídias
│   │   ├── logo.png
│   │   ├── favicon.png
│   │   └── Wallpaper 1920x1080.png
│   └── 📂 storage/                       # Link simbólico
│
├── 📂 resources/                         # Recursos frontend
│   ├── 📂 css/
│   │   └── app.css                       # Estilos principais
│   │
│   ├── 📂 js/
│   │   ├── app.js                        # JavaScript principal
│   │   ├── bootstrap.js
│   │   └── toast.js                      # Sistema de toast
│   │
│   └── 📂 views/                         # Views Blade
│       ├── home.blade.php                # Página inicial
│       │
│       ├── 📂 layouts/                   # Layouts
│       │   ├── app.blade.php             # Layout principal
│       │   ├── secret-url-app.blade.php  # Layout URLs secretas
│       │   ├── navigation.blade.php      # Menu navegação
│       │   └── guest.blade.php          # Layout convidado
│       │
│       ├── 📂 admin/                     # Views administrativas
│       │   ├── 📂 tabs/                  # Gerenciar abas
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   │
│       │   ├── 📂 cards/                 # Gerenciar cards
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   ├── edit.blade.php
│       │   │   └── logins.blade.php
│       │   │
│       │   ├── 📂 sectors/              # Gerenciar setores
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   ├── edit.blade.php
│       │   │   ├── cards.blade.php
│       │   │   └── secret-url.blade.php
│       │   │
│       │   ├── 📂 system-users/         # Gerenciar usuários
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   ├── edit.blade.php
│       │   │   ├── permissions.blade.php
│       │   │   └── secret-url.blade.php
│       │   │
│       │   ├── 📂 system-logins/        # Gerenciar logins
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   ├── edit.blade.php
│       │   │   └── permissions.blade.php
│       │   │
│       │   ├── 📂 servers/              # Gerenciar servidores
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   │
│       │   └── 📂 categories/           # Gerenciar categorias
│       │       ├── index.blade.php
│       │       ├── create.blade.php
│       │       └── edit.blade.php
│       │
│       ├── 📂 secret-url/               # Views URLs secretas
│       │   ├── home.blade.php           # Página do setor
│       │   └── logins.blade.php         # Logins do setor
│       │
│       ├── 📂 components/                # Componentes
│       │   └── toast-notification.blade.php
│       │
│       ├── 📂 auth/                      # Autenticação
│       │   └── ... (Laravel Breeze)
│       │
│       └── 📂 errors/                    # Páginas de erro
│
├── 📂 routes/                            # Rotas
│   ├── web.php                           # Rotas web principais
│   ├── auth.php                          # Rotas de autenticação
│   ├── api.php                           # Rotas API (futuro)
│   └── console.php                       # Rotas console
│
├── 📂 storage/                           # Armazenamento
│   ├── 📂 app/                           # Uploads
│   │   └── 📂 public/                    # Arquivos públicos
│   │       └── 📂 cards/                 # Imagens/PDFs dos cards
│   │
│   └── 📂 logs/                          # Logs da aplicação
│
├── 📂 vendor/                            # Dependências PHP (composer)
│
└── 📂 node_modules/                      # Dependências Node.js (npm)
```

---

## 📊 ESTATÍSTICAS DO PROJETO

### **Arquivos por Tipo**

| Tipo | Quantidade | Descrição |
|------|-----------|-----------|
| **Controllers** | 9 | Controladores principais + Admin |
| **Models** | 13 | Modelos Eloquent |
| **Middleware** | 13 | Middlewares customizados |
| **Migrations** | 30+ | Migrations do banco de dados |
| **Views Blade** | 50+ | Templates Blade |
| **Commands** | 20+ | Comandos Artisan |
| **Documentação .md** | 40+ | Arquivos de documentação |

### **Tecnologias Utilizadas**

| Tecnologia | Versão | Uso |
|-----------|--------|-----|
| **Laravel** | 10.x | Framework backend |
| **PHP** | 8.1+ | Linguagem backend |
| **MySQL** | 8.0+ | Banco de dados |
| **Tailwind CSS** | 3.3.6 | Framework CSS |
| **Alpine.js** | 3.13.3 | Framework JS reativo |
| **Vite** | 4.5.0 | Build tool |
| **Font Awesome** | 6.4.0 | Ícones |
| **Spatie Permission** | 5.10 | Sistema de permissões |

---

## 🔄 FLUXO DE REQUISIÇÕES

### **1. Acesso Público (Não Autenticado)**
```
GET / 
  → HomeController@index
  → Middleware: public.auth
  → View: home.blade.php
  → Exibe TODOS os cards (sem filtro)
```

### **2. Acesso via Login Tradicional**
```
GET /login
  → AuthController@create
  → POST /login
  → Autentica (web ou system guard)
  → Redirect: /
  → HomeController@index
  → View: home.blade.php
  → Filtra cards por permissões
```

### **3. Acesso via URL Secreta**
```
GET /s/{secret_url}
  → Middleware: secret.url
  → Valida URL → Autentica SystemUser
  → SecretUrlController@index
  → View: secret-url/home.blade.php
  → Exibe APENAS cards do setor
```

### **4. Acesso Administrativo**
```
GET /admin/*
  → Middleware: auth.any + admin.access
  → Verifica hasFullAccess()
  → Controller Admin/*
  → View: admin/*.blade.php
```

---

## 🗄️ ESTRUTURA DO BANCO DE DADOS

### **Tabelas Principais**

```
users (Usuários Admin)
  ├── id
  ├── name
  ├── email
  ├── username
  └── password

system_users (Setores/Usuários Sistema)
  ├── id
  ├── name
  ├── username
  ├── password
  ├── secret_url              # URL secreta
  ├── secret_url_expires_at   # Expiração
  ├── secret_url_enabled      # Status
  ├── custom_secret_slug      # Slug personalizado
  └── user_id (opcional)

cards (Sistemas/Links)
  ├── id
  ├── name
  ├── description
  ├── link
  ├── tab_id
  ├── category_id
  ├── data_center_id
  └── monitoring_type

tabs (Abas)
  ├── id
  ├── name
  ├── color
  └── order

system_user_cards (Pivot)
  ├── system_user_id
  └── card_id

system_logins (Logins dos Sistemas)
  ├── id
  ├── card_id
  ├── title
  ├── username
  └── password

secret_url_access_logs (Logs)
  ├── id
  ├── system_user_id
  ├── ip_address
  ├── user_agent
  └── accessed_at
```

---

## 🎨 COMPONENTES DE INTERFACE

### **Sistema de Toast Notifications**
```
toast-notification.blade.php
  ├── Container fixo no topo
  ├── Animações de entrada/saída
  ├── Barra de progresso
  └── Auto-dismiss configurável
```

### **Modais AJAX**
```
Todos os modais admin
  ├── Backdrop blur
  ├── Proteção contra fechamento
  ├── Validação client-side
  └── Feedback toast
```

### **Grid de Cards**
```
home.blade.php / secret-url/home.blade.php
  ├── Grid responsivo (4 colunas)
  ├── Filtros por categoria/data center
  ├── Ordenação
  └── Indicadores de status
```

---

## 📝 DOCUMENTAÇÃO EXISTENTE

### **Arquivos .md Principais**

1. **README.md** - Documentação principal
2. **ANALISE_COMPLETA_PROJETO.md** - Análise completa
3. **ANALISE_IMPLEMENTACAO_URLS_SECRETAS.md** - Análise técnica URLs
4. **RESUMO_EXECUTIVO_URLS_SECRETAS.md** - Resumo executivo URLs
5. **ANALISE_AREA_ADMINISTRATIVA.md** - Análise área admin
6. **ANALISE_COMPLETA_SISTEMA.md** - Análise geral
7. **Múltiplos arquivos de correções** - Histórico de bugs/melhorias

---

## 🚀 COMANDOS ÚTEIS

### **Desenvolvimento**
```bash
# Instalar dependências
composer install
npm install

# Build assets
npm run dev          # Desenvolvimento
npm run build        # Produção

# Limpar cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### **Banco de Dados**
```bash
# Executar migrations
php artisan migrate

# Executar seeders
php artisan db:seed

# Gerar URLs secretas
php artisan secret-url:generate
```

### **Monitoramento**
```bash
# Verificar status de cards
php artisan card:check-status

# Verificar status de servidores
php artisan server:check-status
```

---

## 🔒 SEGURANÇA E PERMISSÕES

### **Middleware Stack**
```
Request
  → Global Middleware
  → Web Middleware Group
  → Route Middleware
  → Controller
```

### **Guards de Autenticação**
```
web (User)
  └── Acesso administrativo

system (SystemUser)
  ├── Login tradicional
  └── URL secreta
```

### **Controle de Acesso**
```
hasFullAccess() → Admin completo
canViewPasswords() → Ver senhas
canViewSystem() → Ver card específico
```

---

**Última Atualização**: 2025-01-27  
**Versão**: 1.0



