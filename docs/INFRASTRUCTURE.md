# Infraestrutura de Desenvolvimento

## 🐳 Docker Compose

Ambiente completo com **5 containers isolados**:

```yaml
services:
  - backend           # Laravel Octane com FrankenPHP
  - mysql-write       # MySQL 8.0 - Banco de escrita
  - redis-cache       # Redis 7 - Cache de aplicação
  - redis-sessions    # Redis 7 - Sessões de usuários
  - redis-queue       # Redis 7 - Filas de jobs
```

## 🎯 Arquitetura de Separação

### Por que 3 Redis separados?

**Isolamento Total**: Problema em um serviço não afeta os outros
**Configurações Específicas**: Cada Redis com policy adequada ao seu uso
**Escalabilidade**: Pode escalar cada serviço independentemente
**Monitoramento**: Métricas separadas por propósito

## 🚀 Como Usar

### Iniciar o ambiente

```bash
cd infrastructure/development
docker compose up -d
```

### Parar o ambiente

```bash
docker compose down
```

### Ver logs

```bash
docker compose logs -f backend
```

### Acessar o container

```bash
docker compose exec backend bash
```

## 🔧 Serviços

### Backend (Laravel Octane + FrankenPHP)
- **URL**: http://localhost:8001
- **Tecnologia**: FrankenPHP (servidor PHP moderno)
- **Features**: 
  - Hot reload automático
  - HTTP/2 e HTTP/3
  - Alta performance
- **Healthcheck**: Verifica `/api/status` a cada 10s

### MySQL Write
- **Host**: localhost (ou `mysql-write` internamente)
- **Porta**: 3306
- **Database**: boilerplate
- **User**: boilerplate_user
- **Password**: boilerplate_secret
- **Uso**: Banco de dados principal para operações de escrita

#### Conectar via CLI
```bash
docker compose exec mysql-write mysql -u boilerplate_user -p
# Password: boilerplate_secret
```

#### Conectar via DataGrip
- Host: localhost
- Port: 3306
- Database: boilerplate
- User: boilerplate_user
- Password: boilerplate_secret

### Redis Cache (Port 6379)
- **Host**: localhost (ou `redis-cache` internamente)
- **Porta**: 6379
- **Uso**: Cache de queries e dados voláteis
- **Configuração**:
  - MaxMemory: 256MB
  - Policy: `allkeys-lru` (remove chaves antigas automaticamente)
  - Persistência: Desabilitada (dados podem ser perdidos)

#### Testar conexão
```bash
docker compose exec redis-cache redis-cli ping
# Retorna: PONG
```

### Redis Sessions (Port 6380)
- **Host**: localhost (ou `redis-sessions` internamente)
- **Porta**: 6380
- **Uso**: Sessões de usuários autenticados
- **Configuração**:
  - MaxMemory: 128MB
  - Policy: `noeviction` (NUNCA remove sessões)
  - Persistência: AOF habilitada (sessões são críticas)

#### Testar conexão
```bash
docker compose exec redis-sessions redis-cli -p 6380 ping
# Retorna: PONG
```

### Redis Queue (Port 6381)
- **Host**: localhost (ou `redis-queue` internamente)
- **Porta**: 6381
- **Uso**: Filas de background jobs
- **Configuração**:
  - MaxMemory: 256MB
  - Policy: `noeviction` (jobs não podem ser perdidos)
  - Persistência: AOF habilitada (garantia de processamento)

#### Testar conexão
```bash
docker compose exec redis-queue redis-cli -p 6381 ping
# Retorna: PONG
```

## 📦 Estrutura de Arquivos

```
infrastructure/development/
├── docker-compose.yml      # Orquestração dos containers
├── Dockerfile             # Imagem do backend
├── mysql/
│   └── my.cnf            # Configuração do MySQL
├── redis/
│   ├── cache.conf        # Config Redis Cache (LRU, sem persistência)
│   ├── sessions.conf     # Config Redis Sessions (noeviction, AOF)
│   └── queue.conf        # Config Redis Queue (noeviction, AOF)
└── php/
    └── php.ini           # Configuração do PHP
```

## ⚙️ Configurações

### PHP (php.ini)
- Memory limit: 512M
- Upload max: 64M (redis-sessions:6380)

### MySQL (my.cnf)
- Character set: utf8mb4
- Collation: utf8mb4_unicode_ci
- InnoDB buffer pool: 256M

### Redis Cache (cache.conf)
- Persistência: Desabilitada
- Max memory: 256MB
- Eviction policy: `allkeys-lru`
- Comandos perigosos desabilitados (FLUSHDB, FLUSHALL, CONFIG)

### Redis Sessions (sessions.conf)
- Persistência: AOF habilitada
- Max memory: 128MB
- Eviction policy: `noeviction` (sessões nunca são removidas)
- Comandos perigosos desabilitados

### Redis Queue (queue.conf)
- Persistência: AOF habilitada
- Max memory:-write       # Nome do serviço no docker-compose
DB_PORT=3306
DB_DATABASE=boilerplate
DB_USERNAME=boilerplate_user
DB_PASSWORD=boilerplate_secret

# Cache - Redis Cache
CACHE_STORE=redis
REDIS_CACHE_HOST=redis-cache
REDIS_CACHE_PORT=6379

# Sessions - Redis Sessions
SESSION_DRIVER=redis
REDIS_SESSION_HOST=redis-sessions
REDIS_SESSION_PORT=6380

# Queue - Redis Queue
QUEUE_CONNECTION=redis
REDIS_QUEUE_HOST=redis-queue
REDIS_QUEUE_PORT=6381
```

### Conexões via DataGrip

**MySQL**
```
Host: localhost
Port: 3306
Database: boilerplate
User: boilerplate_user
Password: boilerplate_secret
```

**Redis Cache**
```
Host: localhost
Port: 6379
```

**Redis Sessions**
```
Host: localhost
Port: 6380
```

**Redis Queue**
```
Host: localhost
Port: 6381
DB_HOST=mysql          # Nome do serviço no docker-compose
DB_PORT=3306
DB_DATABASE=boilerplate
DB_USERNAME=boilerplate
DB_PASSWORD=secret

# Cache
CACHE_STORE=redis
REDIS_HOST=redis       # Nome do serviço no docker-compose
REDIS_PORT=6379

# Session
SESSION_DRIVER=redis
```

## 🛠️ Comandos Úteis

### Executar migrations

```bash
docker compose exec backend php artisan migrate
```

### Executar seeders

```bash
docker compose exec backend php artisan db:seed
```

### Limpar cache

```bash
docker compose exec backend php artisan cache:clear
docker compose exec backend php artisan config:clear
```

### Rodar testes

```bash
docker compose exec backend php artisan test
```

### Ver rotas

```bash
docker compose exec backend php artisan route:list
```

## 📊 Health Check

Endpoint para verificar status da aplicação:

```bash
curl http://localhost:8001/api/status
```

Resposta:
```json
{-write

# Ver logs do MySQL
docker compose logs mysql-write

# Testar conexão
docker compose exec mysql-write mysqladmin ping
```

### Erro de conexão com Redis

```bash
# Verificar se todos os Redis estão rodando
docker compose ps | grep redis

# Testar Redis Cache
docker compose exec redis-cache redis-cli ping

# Testar Redis Sessions
docker compose exec redis-sessions redis-cli -p 6380 ping

# Testar Redis Queue
docker compose exec redis-queue redis-cli -p 6381 ping
6. **Redis Cache**: Pode ser limpo sem problemas (dados voláteis)
7. **Redis Sessions**: NUNCA limpe em produção (perde sessões ativas)
8. **Redis Queue**: NUNCA limpe (perde jobs pendentes)
9. **Use DataGrip** para gerenciar todos os bancos de dados

## 📊 Monitoramento

### Ver métricas Redis

```bash
# Redis Cache
docker compose exec redis-cache redis-cli INFO stats

# Redis Sessions
docker compose exec redis-sessions redis-cli -p 6380 INFO stats

# Redis Queue
docker compose exec redis-queue redis-cli -p 6381 INFO stats
```

### Ver uso de memória

```bash
# Todos os containers
docker stats

# Apenas Redis
docker stats | grep redis
```
```

### Limpar dados Redis específico

```bash
# Limpar apenas cache (seguro)
docker compose exec redis-cache redis-cli FLUSHDB

# ATENÇÃO: Não limpe sessions ou queue em produção!
### Container não inicia

```bash
# Ver logs
docker compose logs backend

# Rebuildar imagem
docker compose build --no-cache
docker compose up -d
```

### Erro de permissão

```bash
# Ajustar permissões do storage
docker compose exec backend chmod -R 775 storage bootstrap/cache
```

### Erro de conexão com MySQL

```bash
# Verificar se MySQL está rodando
docker compose ps mysql

# Ver logs do MySQL
docker compose logs mysql
```

### Erro de conexão com Redis

```bash
# Verificar se Redis está rodando
docker compose ps redis

# Testar conexão
docker compose exec redis redis-cli ping
```

## 🎯 Boas Práticas

1. **Sempre use docker compose** para gerenciar os serviços
2. **Não commite .env** com dados sensíveis
3. **Use volumes** para persistir dados do MySQL
4. **Monitore logs** regularmente
5. **Faça backup** do banco antes de migrations destrutivas
