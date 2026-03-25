# 📊 ANÁLISE COMPLETA DO SISTEMA ENGEHUB

## 🎯 PROPÓSITO E FINALIDADE DO SISTEMA

O **EngeHub** é uma **plataforma de intranet corporativa** desenvolvida para centralizar e organizar o acesso a todos os sistemas, links, credenciais e servidores da empresa. O sistema funciona como um **hub único** onde colaboradores podem:

- **Acessar rapidamente** todos os sistemas da empresa
- **Visualizar credenciais** de acesso aos sistemas (com controle de permissões)
- **Monitorar status** de sistemas e servidores em tempo real
- **Organizar favoritos** para acesso rápido aos sistemas mais usados
- **Gerenciar servidores** e infraestrutura de forma centralizada

---

## 🏗️ ARQUITETURA GERAL DO SISTEMA

### **Stack Tecnológica**

- **Backend**: Laravel 10.x (PHP 8.1+)
- **Frontend**: Blade Templates + Alpine.js + Tailwind CSS
- **Banco de Dados**: MySQL 8.0+ (UTF-8MB4)
- **Autenticação**: Laravel Breeze (múltiplos guards)
- **Permissões**: Spatie Laravel Permission + Sistema Customizado
- **Processamento de Imagens**: Intervention Image
- **Build Tools**: Vite + Node.js 18.x
- **Servidor Web**: Apache2

### **Estrutura de Diretórios**

```
EngeHub/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Controllers administrativos
│   │   │   ├── HomeController.php
│   │   │   ├── ServerController.php
│   │   │   └── FavoriteController.php
│   │   └── Middleware/         # Middlewares de autenticação e permissões
│   ├── Models/                 # Modelos Eloquent
│   └── Providers/              # Service Providers
├── database/
│   ├── migrations/             # Migrations do banco de dados
│   └── seeders/                # Seeders para dados iniciais
├── resources/
│   ├── views/                  # Templates Blade
│   ├── css/                    # Estilos CSS
│   └── js/                     # JavaScript/Alpine.js
├── routes/
│   ├── web.php                 # Rotas web principais
│   └── auth.php                # Rotas de autenticação
└── public/                     # Arquivos públicos
```

---

## 🔐 SISTEMA DE AUTENTICAÇÃO HÍBRIDA

### **Dois Tipos de Usuários**

O sistema implementa uma arquitetura de autenticação **híbrida** com dois guards distintos:

#### **1. Guard 'web' - Usuários Administrativos**
- **Modelo**: `App\Models\User`
- **Tabela**: `users`
- **Características**:
  - Acesso completo ao painel administrativo
  - Pode gerenciar usuários, cards, abas, servidores
  - Sistema de permissões granular (UserPermission + Spatie)
  - Pode visualizar senhas (se tiver permissão)

#### **2. Guard 'system' - Usuários de Sistema**
- **Modelo**: `App\Models\SystemUser`
- **Tabela**: `system_users`
- **Características**:
  - Acesso limitado apenas aos cards permitidos
  - Não tem acesso ao painel administrativo
  - Permissões baseadas em relacionamentos many-to-many
  - Pode visualizar logins apenas dos sistemas permitidos

### **Sistema de Login Unificado**

O sistema implementa um **login único** que tenta autenticar em ambos os guards:

1. Primeiro tenta autenticar como `SystemUser` (por username)
2. Se falhar, tenta autenticar como `User` admin (por email)
3. Redireciona baseado no tipo de usuário autenticado

### **Middleware de Autenticação**

- **`CheckAnyAuth`**: Verifica se está autenticado em qualquer guard
- **`PublicAccessWithAuthCheck`**: Permite acesso público mas adiciona informações de autenticação
- **`CheckAdminAccess`**: Verifica acesso administrativo
- **`ValidateSession`**: Valida sessão do usuário

---

## 🔑 SISTEMA DE PERMISSÕES GRANULAR

### **Arquitetura Dual de Permissões**

O sistema combina **dois sistemas de permissões**:

#### **A) Sistema Customizado (UserPermission)**

**Tabela**: `user_permissions`

**Tipos de Permissão**:
- `view_passwords`: Ver senhas dos sistemas
- `manage_system_users`: Gerenciar usuários de sistema
- `full_access`: Acesso total (administrador)

**Uso**: Controle de permissões específicas para usuários administrativos

#### **B) Sistema Spatie Laravel Permission**

**Uso**: Permissões mais granulares e roles para controle de acesso

### **Sistema de Permissões por Login**

**Tabela**: `system_login_permissions`

Permite que cada login tenha permissões específicas por usuário:
- Cada `SystemLogin` pode ter múltiplos `SystemUser` com acesso
- Cada `SystemUser` pode ver apenas os logins permitidos
- Administradores sempre veem todos os logins

### **Hierarquia de Permissões**

```
FULL_ACCESS (Admin)
    ├── VIEW_PASSWORDS
    └── MANAGE_SYSTEM_USERS

SYSTEM_USER
    └── Acesso apenas aos cards e logins permitidos
```

---

## 📊 ESTRUTURA DE DADOS PRINCIPAL

### **Entidades Principais**

#### **1. Tab (Abas)**
- Organiza cards em categorias visuais
- Campos: `name`, `description`, `color`, `order`
- Relacionamento: `hasMany(Card)`

#### **2. Card (Sistemas/Links)**
- Representa um sistema ou link
- Campos principais:
  - `name`, `description`, `link`
  - `tab_id`, `category_id`, `data_center_id`
  - `icon`, `custom_icon_path`, `file_path`
  - `monitor_status`, `status`, `monitoring_type`
  - `last_status_check`, `response_time`
- Relacionamentos:
  - `belongsTo(Tab, Category, DataCenter)`
  - `hasMany(SystemLogin)`
  - `belongsToMany(SystemUser)` (acesso)

#### **3. SystemLogin (Credenciais)**
- Armazena logins e senhas dos sistemas
- Campos: `card_id`, `title`, `username`, `password`, `notes`, `is_active`
- Relacionamentos:
  - `belongsTo(Card)`
  - `belongsToMany(SystemUser)` (permissões)

#### **4. SystemUser (Usuários de Sistema)**
- Usuários finais que acessam os sistemas
- Campos: `name`, `username`, `password`, `notes`, `is_active`, `user_id`
- Relacionamentos:
  - `belongsTo(User)` (opcional - vinculação com admin)
  - `belongsToMany(Card)` (sistemas permitidos)
  - `belongsToMany(SystemLogin)` (logins permitidos)

#### **5. User (Usuários Administrativos)**
- Administradores do sistema
- Campos: `name`, `username`, `email`, `password`
- Relacionamentos:
  - `hasMany(UserPermission)`
  - `hasOne(SystemUser)` (opcional)
  - `hasMany(UserFavorite)`

#### **6. Server (Servidores)**
- Gerenciamento de servidores físicos/virtuais
- Campos:
  - `name`, `ip_address`, `description`
  - `data_center_id`, `server_group_id`
  - `webmin_url`, `nginx_url`, `operating_system`
  - `monitor_status`, `status`, `response_time`
- Relacionamentos:
  - `belongsTo(DataCenter, ServerGroup)`

#### **7. Category (Categorias)**
- Categorização adicional dos cards
- Campos: `name`, `description`, `color`, `order`
- Relacionamento: `hasMany(Card)`

#### **8. DataCenter (Data Centers)**
- Organização por localização física
- Campos: `name`, `description`
- Relacionamentos: `hasMany(Card, Server)`

#### **9. ServerGroup (Grupos de Servidores)**
- Agrupamento lógico de servidores
- Campos: `name`, `description`, `order`, `is_active`
- Relacionamento: `hasMany(Server)`

#### **10. UserFavorite (Favoritos)**
- Sistema de favoritos para acesso rápido
- Campos: `user_id`, `system_user_id`, `card_id`
- Suporta ambos os tipos de usuários

### **Tabelas de Relacionamento (Pivot)**

- `system_user_cards`: Relaciona SystemUser com Card (acesso aos sistemas)
- `system_login_permissions`: Relaciona SystemLogin com SystemUser (permissões)
- `user_favorites`: Relaciona User/SystemUser com Card (favoritos)

---

## 🎨 FUNCIONALIDADES PRINCIPAIS

### **1. Página Pública (Home)**

**Acesso**: Público (qualquer pessoa pode ver)

**Funcionalidades**:
- Visualização de todos os sistemas organizados por abas
- Filtros por categoria e data center
- Ordenação por nome ou ordem
- Sistema de favoritos (se logado)
- Indicadores visuais de status dos sistemas
- Design responsivo e moderno

**Características**:
- Não requer login para visualizar sistemas
- Botão "LOGINS" só funciona se usuário estiver logado
- Aba especial de "Favoritos" para usuários logados

### **2. Área Administrativa**

**Acesso**: Apenas usuários administrativos (`User` com permissões)

**Funcionalidades**:

#### **Gerenciamento de Abas**
- CRUD completo de abas
- Personalização de cores
- Ordenação customizada

#### **Gerenciamento de Cards**
- CRUD completo de cards
- Upload de ícones personalizados
- Upload de arquivos (PDFs, imagens)
- Configuração de monitoramento
- Seleção de tipo de monitoramento (HTTP/Ping)

#### **Gerenciamento de Logins**
- CRUD completo de logins por sistema
- Controle granular de permissões por login
- Visualização/ocultação de senhas
- Cópia rápida de credenciais

#### **Gerenciamento de Usuários**
- CRUD de usuários administrativos
- CRUD de usuários de sistema
- Vinculação de usuários de sistema com admins
- Atribuição de permissões

#### **Gerenciamento de Categorias**
- CRUD de categorias
- Organização visual por cores

#### **Gerenciamento de Data Centers**
- CRUD de data centers
- Organização geográfica

#### **Gerenciamento de Servidores**
- CRUD completo de servidores
- Agrupamento por grupos
- Monitoramento via ping
- Links para Webmin e Nginx

#### **Gerenciamento de Grupos de Servidores**
- CRUD de grupos
- Organização lógica

### **3. Sistema de Monitoramento**

**Funcionalidades**:
- Verificação automática de status (HTTP/Ping)
- Indicadores visuais (verde/vermelho)
- Tempo de resposta em milissegundos
- Última verificação registrada
- Comando Artisan para verificação em lote

**Tipos de Monitoramento**:
- **HTTP**: Para URLs web (usa HEAD/GET)
- **Ping**: Para IPs de servidores

**Implementação**:
- Timeout configurável (10 segundos)
- Tratamento de erros robusto
- Cache de resultados

### **4. Sistema de Favoritos**

**Funcionalidades**:
- Adicionar/remover cards dos favoritos
- Aba especial "Favoritos" na home
- Funciona para ambos os tipos de usuários
- Persistência no banco de dados

**API**:
- `POST /favorites/{card}/toggle`: Alternar favorito
- `GET /favorites`: Listar favoritos
- `GET /favorites/{card}/check`: Verificar se é favorito

### **5. Visualização de Servidores**

**Acesso**: Público

**Funcionalidades**:
- Lista de servidores com status
- Filtros por data center, SO e grupo
- Agrupamento visual por grupos
- Verificação manual de status
- Links para Webmin e Nginx

---

## 🔒 SEGURANÇA E VALIDAÇÃO

### **Camadas de Segurança**

1. **Autenticação**:
   - Sistema híbrido com múltiplos guards
   - Rate limiting no login (5 tentativas)
   - Sessões seguras

2. **Autorização**:
   - Middleware de verificação de permissões
   - Controle granular por login
   - Proteção de rotas administrativas

3. **Validação**:
   - Validação de entrada em todos os formulários
   - Sanitização de dados
   - Proteção CSRF em todas as requisições

4. **Armazenamento**:
   - Senhas criptografadas com bcrypt
   - Senhas de logins armazenadas sem hash (para visualização)
   - Upload seguro de arquivos

### **Proteções Implementadas**

- ✅ Proteção CSRF em todos os formulários
- ✅ Validação de entrada robusta
- ✅ Rate limiting no login
- ✅ Verificação de permissões em múltiplas camadas
- ✅ Logs detalhados para auditoria
- ✅ Sanitização de IDs e dados de entrada

---

## 📱 INTERFACE E EXPERIÊNCIA DO USUÁRIO

### **Design Responsivo**

- **Mobile-First**: Interface adaptável a todos os dispositivos
- **Tailwind CSS**: Framework moderno e utilitário
- **Alpine.js**: Interatividade sem complexidade

### **Componentes Visuais**

1. **Cards de Sistemas**:
   - Ícones Font Awesome ou customizados
   - Indicadores de status coloridos
   - Botões de ação (favoritar, logins)

2. **Modais Dinâmicos**:
   - Carregamento via AJAX
   - Fechamento ao clicar fora
   - Animações suaves

3. **Feedback Visual**:
   - Sistema de toast notifications
   - Confirmações de ações
   - Estados de loading

4. **Filtros e Busca**:
   - Filtros por categoria e data center
   - Ordenação dinâmica
   - Busca em tempo real

### **Acessibilidade**

- Cores contrastantes
- Ícones descritivos
- Feedback visual claro
- Navegação intuitiva

---

## 🚀 FUNCIONALIDADES AVANÇADAS

### **1. Upload e Processamento de Imagens**

- **Ícones Personalizados**:
  - Upload de imagens
  - Redimensionamento automático (32x32px)
  - Conversão para PNG
  - Armazenamento em `storage/app/public/custom_icons/`

- **Arquivos Gerais**:
  - Suporte a múltiplos formatos (JPG, PNG, GIF, PDF)
  - Tamanho máximo: 2MB (ícones) / 10MB (arquivos)
  - Armazenamento em `storage/app/public/files/`

### **2. Sistema de Logs**

- Logs detalhados em pontos críticos
- Rastreamento de ações de usuários
- Logs de autenticação e permissões
- Armazenamento em `storage/logs/`

### **3. Comandos Artisan**

- `cards:check-status`: Verifica status de cards
- `servers:check-status`: Verifica status de servidores
- Comandos personalizados para manutenção

### **4. API e Integrações**

- Rotas AJAX para operações dinâmicas
- Respostas JSON estruturadas
- Suporte a requisições assíncronas

---

## 📈 PERFORMANCE E OTIMIZAÇÃO

### **Estratégias Implementadas**

1. **Eager Loading**:
   - Carregamento otimizado de relacionamentos
   - Redução de queries N+1

2. **Cache**:
   - Cache de permissões Spatie
   - Cache de configurações Laravel

3. **Otimização de Imagens**:
   - Redimensionamento automático
   - Formatos otimizados

4. **Queries Otimizadas**:
   - Índices em campos críticos
   - Constraints únicas para evitar duplicatas

---

## 🔄 FLUXOS PRINCIPAIS DO SISTEMA

### **Fluxo de Acesso Público**

1. Usuário acessa a home (`/`)
2. Sistema carrega todas as abas e cards
3. Usuário visualiza sistemas sem login
4. Ao clicar em "LOGINS", sistema verifica autenticação
5. Se não logado, mostra mensagem para fazer login
6. Se logado, mostra logins permitidos

### **Fluxo de Login**

1. Usuário acessa `/login`
2. Sistema tenta autenticar como `SystemUser` (por username)
3. Se falhar, tenta como `User` (por email)
4. Redireciona baseado no tipo de usuário:
   - `SystemUser` → Home pública
   - `User` admin → Painel administrativo

### **Fluxo de Visualização de Logins**

1. Usuário clica em "LOGINS" de um card
2. Sistema verifica permissões:
   - Admin com `full_access` → Vê todos os logins
   - Admin com `view_passwords` → Vê todos os logins
   - `SystemUser` → Vê apenas logins permitidos
3. Sistema aplica filtro granular se necessário
4. Retorna HTML com logins permitidos

### **Fluxo de Monitoramento**

1. Sistema executa comando `cards:check-status`
2. Para cada card com `monitor_status = true`:
   - Se `monitoring_type = 'ping'` → Executa ping
   - Se `monitoring_type = 'http'` → Faz requisição HTTP
3. Atualiza status, tempo de resposta e última verificação
4. Usuário visualiza indicadores visuais na interface

---

## 📋 RESUMO DAS ENTIDADES E RELACIONAMENTOS

### **Diagrama de Relacionamentos**

```
User (1) ←→ (N) UserPermission
User (1) ←→ (1) SystemUser (opcional)
User (1) ←→ (N) UserFavorite

SystemUser (N) ←→ (N) Card (via system_user_cards)
SystemUser (N) ←→ (N) SystemLogin (via system_login_permissions)
SystemUser (1) ←→ (N) UserFavorite

Card (N) ←→ (1) Tab
Card (N) ←→ (1) Category
Card (N) ←→ (1) DataCenter
Card (1) ←→ (N) SystemLogin
Card (N) ←→ (N) SystemUser (via system_user_cards)
Card (N) ←→ (N) User/SystemUser (via user_favorites)

Server (N) ←→ (1) DataCenter
Server (N) ←→ (1) ServerGroup

SystemLogin (N) ←→ (1) Card
SystemLogin (N) ←→ (N) SystemUser (via system_login_permissions)
```

---

## 🎯 CASOS DE USO PRINCIPAIS

### **1. Colaborador Acessando Sistemas**

1. Acessa a home pública
2. Visualiza todos os sistemas organizados por abas
3. Clica no sistema desejado → Redireciona para o link
4. Se precisar de credenciais, faz login
5. Acessa "LOGINS" e vê apenas os logins permitidos
6. Copia credenciais e acessa o sistema

### **2. Administrador Gerenciando Sistema**

1. Faz login como admin
2. Acessa painel administrativo
3. Cria/edita/exclui cards, abas, usuários
4. Configura permissões granulares por login
5. Monitora status dos sistemas
6. Gerencia servidores e infraestrutura

### **3. Usuário de Sistema com Acesso Limitado**

1. Faz login como SystemUser
2. Visualiza apenas os cards permitidos
3. Acessa logins apenas dos sistemas permitidos
4. Não tem acesso ao painel administrativo
5. Pode favoritar cards para acesso rápido

---

## 🔧 CONFIGURAÇÕES E DEPENDÊNCIAS

### **Dependências PHP (composer.json)**

- `laravel/framework`: ^10.10
- `laravel/breeze`: ^1.20
- `spatie/laravel-permission`: ^5.10
- `intervention/image`: ^3.11
- `laravel/sanctum`: ^3.2

### **Dependências JavaScript (package.json)**

- `alpinejs`: ^3.13.3
- `tailwindcss`: ^3.3.6
- `vite`: ^4.5.0
- `axios`: ^1.6.1

### **Configurações Importantes**

- **Banco de Dados**: MySQL 8.0+ com UTF-8MB4
- **Sessões**: File-based (pode ser Redis)
- **Cache**: File-based (pode ser Redis)
- **Storage**: Local (pode ser S3)

---

## 📝 CONCLUSÃO

O **EngeHub** é um sistema **robusto e completo** de intranet corporativa que oferece:

✅ **Centralização**: Todos os sistemas em um único lugar
✅ **Segurança**: Múltiplas camadas de autenticação e autorização
✅ **Flexibilidade**: Sistema híbrido de usuários e permissões
✅ **Monitoramento**: Verificação automática de status
✅ **Usabilidade**: Interface moderna e intuitiva
✅ **Escalabilidade**: Arquitetura preparada para crescimento
✅ **Manutenibilidade**: Código bem estruturado e documentado

O sistema está **100% funcional** e pronto para uso em produção, oferecendo uma solução completa para gerenciamento de sistemas corporativos internos.

---

**Desenvolvido com ❤️ para a EngeHub**




