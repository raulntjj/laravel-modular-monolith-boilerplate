# Comandos Artisan

## 🏗️ Module Commands

Comandos para gerenciar módulos da aplicação.

### Criar novo módulo

Cria um módulo completo com a estrutura DDD em 4 camadas:

```bash
php artisan module:make NomeDoModulo
```

**Estrutura criada:**
```
modules/NomeDoModulo/
├── Domain/
│   ├── Entities/
│   ├── ValueObjects/
│   └── Contracts/
├── Application/
│   ├── Commands/
│   └── Queries/
├── Infrastructure/
│   ├── Persistence/
│   └── Providers/
└── Interface/
    └── Http/
        └── Controllers/
```

**Exemplo:**
```bash
php artisan module:make Product
# Cria módulo Product com todas as camadas
```

### Criar migration de módulo

Cria uma migration dentro do módulo específico:

```bash
php artisan module:make-migration NomeDoModulo create_tabela_table
```

**Exemplo:**
```bash
php artisan module:make-migration Product create_products_table
# Cria: modules/Product/Infrastructure/Database/Migrations/
#       2024_01_15_120000_create_products_table.php
```

**Executar migrations do módulo:**
```bash
php artisan migrate --path=modules/Product/Infrastructure/Database/Migrations
```

### Testar módulo

Executa os testes de um módulo específico:

```bash
php artisan module:test NomeDoModulo
```

**Exemplo:**
```bash
php artisan module:test Product
# Executa testes em modules/Product/Tests/
```

**Com opções:**
```bash
# Parar no primeiro erro
php artisan module:test Product --stop-on-failure

# Com coverage
php artisan module:test Product --coverage
```

## 🗄️ Database Commands

### Migrations

```bash
# Executar todas as migrations
php artisan migrate

# Executar migrations de um módulo
php artisan migrate --path=modules/User/Infrastructure/Database/Migrations

# Rollback última migration
php artisan migrate:rollback

# Resetar banco (drop all + migrate)
php artisan migrate:fresh

# Resetar com seeders
php artisan migrate:fresh --seed
```

### Seeders

```bash
# Executar todos os seeders
php artisan db:seed

# Executar seeder específico
php artisan db:seed --class=UserSeeder
```

## 🧹 Cache Commands

```bash
# Limpar cache da aplicação
php artisan cache:clear

# Limpar cache de configuração
php artisan config:clear

# Limpar cache de rotas
php artisan route:clear

# Limpar cache de views
php artisan view:clear

# Limpar todos os caches
php artisan optimize:clear
```

## 🚀 Octane Commands

```bash
# Iniciar servidor Octane
php artisan octane:start

# Iniciar em modo watch (hot reload)
php artisan octane:start --watch

# Parar servidor
php artisan octane:stop

# Recarregar servidor
php artisan octane:reload

# Status do servidor
php artisan octane:status
```

## 📋 Informação

```bash
# Listar todas as rotas
php artisan route:list

# Listar rotas de API
php artisan route:list --path=api

# Ver informações da aplicação
php artisan about

# Listar providers
php artisan provider:list
```

## 🧪 Testing Commands

```bash
# Executar todos os testes
php artisan test

# Executar testes com coverage
php artisan test --coverage

# Executar teste específico
php artisan test --filter=UserTest

# Parar no primeiro erro
php artisan test --stop-on-failure
```

## 🔧 Desenvolvimento

```bash
# Modo de manutenção
php artisan down

# Sair do modo de manutenção
php artisan up

# Gerar application key
php artisan key:generate

# Otimizar aplicação (produção)
php artisan optimize

# Limpar otimizações
php artisan optimize:clear
```

## 📝 Comandos Customizados por Módulo

### Exemplo: User Module

Após criar o módulo User, você pode adicionar comandos específicos:

```bash
# Criar admin user
php artisan user:create-admin

# Listar usuários
php artisan user:list

# Sincronizar permissões
php artisan user:sync-permissions
```

## 💡 Dicas

### Criar comando artisan

```bash
php artisan make:command NomeDoComando
```

### Executar comando no Docker

```bash
docker compose exec backend php artisan comando
```

### Ver ajuda de um comando

```bash
php artisan help nome:comando
```

### Executar comando em produção

```bash
php artisan comando --force
```

## 🎯 Workflow Recomendado

### Criar novo módulo completo

```bash
# 1. Criar módulo
php artisan module:make Product

# 2. Criar migration
php artisan module:make-migration Product create_products_table

# 3. Implementar Domain, Application, Infrastructure, Interface

# 4. Executar migration
php artisan migrate --path=modules/Product/Infrastructure/Database/Migrations

# 5. Testar
php artisan module:test Product

# 6. Verificar rotas
php artisan route:list --path=api/products
```

### Desenvolvimento local

```bash
# Iniciar ambiente Docker
cd infrastructure/development
docker compose up -d

# Executar migrations
docker compose exec backend php artisan migrate

# Ver logs da aplicação
docker compose exec backend tail -f storage/logs/laravel.log

# Testar endpoint
curl http://localhost:8001/api/status
```

