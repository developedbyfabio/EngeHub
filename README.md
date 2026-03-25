# EngeHub - Intranet Hub

Um sistema de hub interno para centralização de links e sistemas da empresa, desenvolvido com Laravel 10, Tailwind CSS e Alpine.js.

## 🚀 Funcionalidades

- **Página Pública**: Acesso livre a todos os colaboradores com abas organizadas e cards dos sistemas
- **Área Administrativa**: Painel protegido por login para gerenciamento completo
- **CRUD de Abas**: Criação, edição e exclusão de categorias
- **CRUD de Cards**: Gerenciamento completo dos links dos sistemas
- **Upload de Arquivos**: Suporte para imagens e PDFs nos cards
- **Sistema de Permissões**: Controle de acesso com Spatie Laravel Permission
- **Design Responsivo**: Interface moderna e adaptável a todos os dispositivos

## 🛠️ Stack Tecnológica

- **Backend**: Laravel 10.x + PHP 8.1+
- **Frontend**: Blade Templates + Alpine.js + Tailwind CSS
- **Banco de Dados**: MySQL 8.0+ com suporte UTF-8MB4
- **Autenticação**: Laravel Breeze
- **Permissões**: Spatie Laravel Permission
- **Build Tools**: Vite + Node.js 18.x
- **Servidor**: Apache2

## 📋 Pré-requisitos

- Ubuntu 22.04.5 LTS
- Acesso root no servidor
- Conexão com internet para download de pacotes

## 🔧 Instalação Completa

### 1. Atualização do Sistema

```bash
# Atualizar o sistema
apt update && apt upgrade -y

# Instalar pacotes essenciais
apt install -y curl wget git unzip software-properties-common apt-transport-https ca-certificates gnupg lsb-release
```

### 2. Instalação do PHP 8.1

```bash
# Adicionar repositório do PHP
add-apt-repository ppa:ondrej/php -y
apt update

# Instalar PHP 8.1 e extensões necessárias
apt install -y php8.1 php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath php8.1-intl php8.1-soap php8.1-xmlrpc php8.1-ldap php8.1-imap php8.1-fpm

# Verificar instalação
php -v
```

### 3. Instalação do MySQL 8.0

```bash
# Baixar e instalar MySQL
wget https://dev.mysql.com/get/mysql-apt-config_0.8.24-1_all.deb
dpkg -i mysql-apt-config_0.8.24-1_all.deb
apt update

# Instalar MySQL Server
apt install -y mysql-server

# Configurar MySQL
mysql_secure_installation
```

**Durante a configuração do MySQL:**
- Definir senha root: `Jgr34eng02@`
- Remover usuários anônimos: `Y`
- Desabilitar login root remoto: `Y`
- Remover banco de teste: `Y`
- Recarregar privilégios: `Y`

### 4. Instalação do Apache2

```bash
# Instalar Apache2
apt install -y apache2

# Habilitar módulos necessários
a2enmod rewrite
a2enmod ssl
a2enmod headers

# Reiniciar Apache
systemctl restart apache2
systemctl enable apache2
```

### 5. Instalação do Composer

```bash
# Baixar e instalar Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Verificar instalação
composer --version
```

### 6. Instalação do Node.js 18.x

```bash
# Adicionar repositório do NodeSource
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -

# Instalar Node.js
apt install -y nodejs

# Verificar instalação
node --version
npm --version
```

### 7. Configuração do Banco de Dados

```bash
# Acessar MySQL
mysql -u root -p

# Criar banco de dados e usuário
CREATE DATABASE engehub_intranet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'engehub_user'@'localhost' IDENTIFIED BY 'Jgr34eng02@';
GRANT ALL PRIVILEGES ON engehub_intranet.* TO 'engehub_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 8. Configuração do Projeto

```bash
# Navegar para o diretório web
cd /var/www

# Clonar ou copiar o projeto
# (Se você já tem o projeto, copie para este diretório)
# cp -r /caminho/do/projeto EngeHub

# Definir permissões
chown -R www-data:www-data /var/www/EngeHub
chmod -R 755 /var/www/EngeHub
chmod -R 775 /var/www/EngeHub/storage
chmod -R 775 /var/www/EngeHub/bootstrap/cache

# Navegar para o projeto
cd EngeHub

# Instalar dependências do PHP
composer install --no-dev --optimize-autoloader

# Copiar arquivo de ambiente
cp env.example .env

# Gerar chave da aplicação
php artisan key:generate
```

### 9. Configuração do Arquivo .env

Editar o arquivo `.env` com as seguintes configurações:

```env
APP_NAME="EngeHub - Intranet"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://engehub.local

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=engehub_intranet
DB_USERNAME=engehub_user
DB_PASSWORD=Jgr34eng02@

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### 10. Configuração do Apache

```bash
# Copiar arquivo de configuração
cp engehub-intranet.conf /etc/apache2/sites-available/

# Habilitar o site
a2ensite engehub-intranet.conf

# Desabilitar site padrão (opcional)
a2dissite 000-default.conf

# Testar configuração
apache2ctl configtest

# Reiniciar Apache
systemctl restart apache2
```

### 11. Configuração do Hosts (Local)

```bash
# Editar arquivo hosts
echo "127.0.0.1 engehub.local" >> /etc/hosts
```

### 12. Execução das Migrations e Seeders

```bash
# Navegar para o projeto
cd /var/www/EngeHub

# Executar migrations
php artisan migrate

# Executar seeders
php artisan db:seed

# Criar link simbólico para storage
php artisan storage:link
```

### 13. Build dos Assets

```bash
# Instalar dependências do Node.js
npm install

# Build para produção
npm run build
```

### 14. Configuração de Permissões Finais

```bash
# Definir permissões corretas
chown -R www-data:www-data /var/www/EngeHub
chmod -R 755 /var/www/EngeHub
chmod -R 775 /var/www/EngeHub/storage
chmod -R 775 /var/www/EngeHub/bootstrap/cache
```

## 🔐 Credenciais de Acesso

Após a instalação, você pode acessar:

- **URL Pública**: http://engehub.local
- **Área Administrativa**: http://engehub.local/login
- **Email**: admin@engepecas.com
- **Senha**: admin123

## 📁 Estrutura do Projeto

```
EngeHub/
├── app/
│   ├── Http/Controllers/
│   │   ├── HomeController.php
│   │   └── Admin/
│   │       ├── TabController.php
│   │       └── CardController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Tab.php
│   │   └── Card.php
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── home.blade.php
│   │   ├── admin/
│   │   └── layouts/
│   ├── css/
│   └── js/
├── routes/
├── public/
└── config/
```

## 🎨 Personalização

### Cores das Abas
As cores das abas podem ser personalizadas no painel administrativo usando códigos hexadecimais (ex: #3B82F6).

### Ícones dos Cards
Os cards suportam ícones do Font Awesome. Exemplos:
- `fas fa-cogs` - Engrenagens
- `fas fa-users` - Usuários
- `fas fa-envelope` - E-mail
- `fas fa-cloud` - Nuvem
- `fas fa-book` - Livro

### Upload de Arquivos
- Formatos aceitos: JPG, PNG, GIF, PDF
- Tamanho máximo: 2MB
- Armazenamento: `storage/app/public/cards/`

## 🔧 Comandos Úteis

```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Otimizar para produção
php artisan optimize

# Verificar status do sistema
php artisan about

# Criar novo usuário admin
php artisan tinker
User::create(['name' => 'Novo Admin', 'email' => 'admin2@engehub.com', 'password' => Hash::make('senha123')]);
```

## 🛡️ Segurança

- Todas as senhas são criptografadas com bcrypt
- Proteção CSRF em todos os formulários
- Validação de entrada em todos os campos
- Controle de acesso baseado em roles e permissions
- Headers de segurança configurados no Apache

## 📞 Suporte

Para suporte técnico ou dúvidas sobre a instalação, entre em contato com a equipe de TI.

## 📝 Logs

Os logs da aplicação podem ser encontrados em:
- Laravel: `/var/www/EngeHub/storage/logs/`
- Apache: `/var/log/apache2/engehub_error.log`

## 🔄 Atualizações

Para atualizar o sistema:

```bash
cd /var/www/EngeHub
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate
npm install && npm run build
php artisan optimize
```

---

**Desenvolvido com ❤️ para a EngeHub** 
