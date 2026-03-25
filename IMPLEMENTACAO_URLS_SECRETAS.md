# Implementação de URLs Secretas - EngeHub

## 📋 Resumo

A funcionalidade de URLs secretas permite que **setores** acessem o sistema sem login tradicional, usando uma URL única e não-adivinhável. Cada setor vê apenas os links que o administrador autorizou.

## 🎯 Acesso Rápido

**Para gerenciar setores e URLs secretas:**
1. Faça login como administrador
2. Acesse o menu **"Setores"** na barra de navegação
3. Crie um novo setor e selecione os links que ele pode ver
4. Copie a URL secreta e compartilhe com o setor

## ✅ O que foi implementado

### 1. Banco de Dados

**Novas colunas na tabela `system_users`:**
- `secret_url` - URL secreta única (32 caracteres)
- `secret_url_expires_at` - Data/hora de expiração (opcional)
- `secret_url_generated_at` - Data/hora de geração
- `secret_url_enabled` - Status de habilitação

**Nova tabela `secret_url_access_logs`:**
- Registra todos os acessos por URL secreta
- Armazena IP, user-agent, referer e timestamp

### 2. Models

**`SystemUser` (atualizado):**
- Novos campos fillable
- Métodos: `generateSecretUrl()`, `isSecretUrlValid()`, `regenerateSecretUrl()`, `disableSecretUrl()`, `enableSecretUrl()`
- Atributo: `full_secret_url` (retorna URL completa)

**`SecretUrlAccessLog` (novo):**
- Model para logs de acesso

### 3. Middleware

**`CheckSecretUrl`:**
- Valida URL secreta
- Verifica expiração
- Registra log de acesso
- Adiciona `secret_system_user` ao request

### 4. Controllers

**`SecretUrlController` (novo):**
- `index()` - Exibe cards do setor
- `logins()` - Exibe logins de um card específico

**`SystemUserController` (atualizado):**
- `showSecretUrl()` - Gerenciamento de URL secreta
- `regenerateSecretUrl()` - Regenera URL
- `toggleSecretUrl()` - Habilita/desabilita
- `setSecretUrlExpiration()` - Define expiração

### 5. Rotas

```
GET  /s/{secret_url}                    - Página principal do setor
GET  /s/{secret_url}/cards/{card}/logins - Logins de um card

GET  /admin/system-users/{user}/secret-url           - Modal de gerenciamento
POST /admin/system-users/{user}/secret-url/regenerate - Regenerar URL
POST /admin/system-users/{user}/secret-url/toggle    - Habilitar/desabilitar
POST /admin/system-users/{user}/secret-url/expiration - Definir expiração
```

### 6. Views

**`layouts/secret-url-app.blade.php`:**
- Layout simplificado sem menu de navegação

**`secret-url/home.blade.php`:**
- Exibe cards do setor com filtros
- Modal de logins integrado

**`secret-url/logins.blade.php`:**
- Exibe logins com copiar/mostrar senha

**`admin/system-users/secret-url.blade.php`:**
- Modal de gerenciamento no admin

**`admin/system-users/index.blade.php` (atualizado):**
- Botão de URL secreta na tabela

### 7. Comando Artisan

```bash
# Gerar URLs para todos os usuários sem URL
php artisan secret-url:generate --all

# Regenerar URLs para TODOS os usuários
php artisan secret-url:generate --regenerate

# Gerar para usuário específico
php artisan secret-url:generate --user=1

# Gerar para SystemUser específico
php artisan secret-url:generate --system-user=1

# Menu interativo
php artisan secret-url:generate
```

---

## 🚀 Como Usar

### 1. Gerenciar Setores (PRINCIPAL)

**Acesso:** Menu **"Setores"** no painel admin (`/admin/sectors`)

**Na página de Setores você pode:**
- ✅ Criar novos setores
- ✅ Selecionar quais links cada setor pode ver
- ✅ Gerar/regenerar URLs secretas
- ✅ Habilitar/desabilitar URLs
- ✅ Definir datas de expiração
- ✅ Ver histórico de acessos

### 2. Criar um Novo Setor

1. Clique em **"Novo Setor"**
2. Informe o nome (ex: "Administrativo", "Contabilidade")
3. Marque os links que este setor pode acessar
4. Clique em **"Criar Setor"**
5. A URL secreta será gerada automaticamente

### 3. Gerenciar Links de um Setor

1. Na lista de setores, clique no ícone 🔗 (link azul)
2. Marque/desmarque os links desejados
3. Clique em **"Salvar Links"**

### 4. Gerenciar URL Secreta

1. Na lista de setores, clique no ícone 🔑 (chave roxa)
2. Copie a URL e compartilhe com o setor
3. Opções: Regenerar, Habilitar/Desabilitar, Definir Expiração

### 5. Acessar via URL Secreta

Os usuários do setor acessam diretamente pela URL:
```
http://seu-dominio/s/abc123xyz456...
```

### 6. Via Linha de Comando (opcional)

```bash
php artisan secret-url:generate --all
```

### 7. Gerenciar URLs (método antigo)

No painel admin (`/admin/system-users`):
- **Gerar/Regenerar URL**: Cria nova URL (invalida a anterior)
- **Habilitar/Desabilitar**: Ativa ou desativa o acesso
- **Definir Expiração**: Define quando a URL expira
- **Ver Logs**: Visualiza últimos acessos

---

## 🔒 Segurança

1. **URLs não-adivinháveis**: 32 caracteres aleatórios
2. **Rate limiting**: 60 requisições/minuto por IP
3. **Logs de acesso**: Todos os acessos são registrados
4. **Expiração configurável**: URLs podem expirar
5. **Desabilitação imediata**: URLs podem ser desativadas a qualquer momento
6. **Isolamento**: Cada URL mostra apenas os cards permitidos do SystemUser

---

## 📁 Arquivos Criados/Modificados

### Criados:
- `database/migrations/2025_11_25_105555_add_secret_url_to_system_users_table.php`
- `database/migrations/2025_11_25_105618_create_secret_url_access_logs_table.php`
- `app/Models/SecretUrlAccessLog.php`
- `app/Http/Middleware/CheckSecretUrl.php`
- `app/Http/Controllers/SecretUrlController.php`
- `app/Console/Commands/GenerateSecretUrls.php`
- `resources/views/layouts/secret-url-app.blade.php`
- `resources/views/secret-url/home.blade.php`
- `resources/views/secret-url/logins.blade.php`
- `resources/views/admin/system-users/secret-url.blade.php`

### Modificados:
- `app/Models/SystemUser.php`
- `app/Http/Kernel.php`
- `app/Http/Controllers/Admin/SystemUserController.php`
- `routes/web.php`
- `resources/views/admin/system-users/index.blade.php`

---

## 🔄 Como Reverter

Para reverter a implementação:

```bash
# Reverter migrations
php artisan migrate:rollback --step=2

# Remover arquivos (opcional)
rm app/Models/SecretUrlAccessLog.php
rm app/Http/Middleware/CheckSecretUrl.php
rm app/Http/Controllers/SecretUrlController.php
rm app/Console/Commands/GenerateSecretUrls.php
rm -rf resources/views/secret-url/
rm resources/views/layouts/secret-url-app.blade.php
rm resources/views/admin/system-users/secret-url.blade.php
```

E reverter manualmente as alterações nos arquivos modificados.

---

## 🧪 Testando

1. Gere uma URL secreta para um usuário:
```bash
php artisan secret-url:generate --user=1
```

2. Acesse a URL gerada no navegador

3. Verifique os logs de acesso no painel admin

---

## 📝 Notas Importantes

- Cada `SystemUser` tem sua própria URL secreta
- URLs podem ser regeneradas a qualquer momento (a anterior é invalidada)
- O acesso por URL secreta mostra APENAS os cards vinculados ao SystemUser
- O login tradicional continua funcionando normalmente
- Administradores continuam acessando via `/admin` ou `/login`

