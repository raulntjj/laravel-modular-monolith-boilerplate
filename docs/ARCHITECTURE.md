# Arquitetura Boilerplate

## 📋 Visão Geral

Monolito Modular com **DDD** (Domain-Driven Design) + **CQRS** (Command Query Responsibility Segregation) usando Laravel Octane para alta performance.

## 🏗️ Estrutura de Camadas

Cada módulo segue a estrutura de 4 camadas:

```
modules/
├── Shared/              # Componentes reutilizáveis
│   ├── Domain/
│   │   └── Contracts/
│   ├── Application/
│   ├── Infrastructure/
│   │   ├── Cache/
│   │   ├── Logging/
│   │   └── Persistence/
│   └── Interface/
│
└── User/                # Exemplo de módulo
    ├── Domain/          # 1. Regras de negócio puras
    │   ├── Entities/
    │   ├── ValueObjects/
    │   └── Contracts/
    ├── Application/     # 2. Casos de uso
    │   ├── Commands/    # Write operations
    │   └── Queries/     # Read operations
    ├── Infrastructure/  # 3. Implementação técnica
    │   ├── Persistence/
    │   └── Providers/
    └── Interface/       # 4. Pontos de entrada
        └── Http/
            └── Controllers/
```

## 🎯 Princípios DDD

### Domain Layer (Camada de Domínio)
- **Entities**: Objetos com identidade única
- **ValueObjects**: Objetos imutáveis sem identidade
- **Contracts**: Interfaces que definem contratos

```php
// Domain/Entities/User.php
final readonly class User
{
    public function __construct(
        public string $id,
        public Email $email,
        public string $name
    ) {}
}
```

### Application Layer (Casos de Uso)
- **Commands**: Operações de escrita (CREATE, UPDATE, DELETE)
- **Queries**: Operações de leitura (SELECT)

```php
// Application/Commands/CreateUserCommand.php
final readonly class CreateUserCommand
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {}
}

// Application/Queries/FindUserByIdQuery.php
final readonly class FindUserByIdQuery
{
    public function __construct(
        public string $userId
    ) {}
}
```

### Infrastructure Layer (Implementação)
- **Repositories**: Persistência de dados
- **Services**: Serviços de infraestrutura
- **Providers**: Registro de dependências

### Interface Layer (Apresentação)
- **Controllers**: Endpoints HTTP
- **Requests**: Validação de entrada
- **Resources**: Formatação de saída

## 🔄 CQRS Pattern

Separação entre **Commands** (escrita) e **Queries** (leitura):

- **Commands**: Modificam estado, invalidam cache
- **Queries**: Apenas leitura, usam cache com Redis

### Exemplo de Command (Escrita)

```php
// POST /web/api/users
$command = new CreateUserCommand(
    name: 'John Doe',
    email: 'john@example.com',
    password: 'secret123'
);

$userId = $useCase->execute($command);
// 1. Valida dados
// 2. Cria entidade User
// 3. Persiste no MySQL
// 4. Invalida cache relacionado
```

### Exemplo de Query (Leitura)

```php
// GET /web/api/users/{id}
$query = new FindUserByIdQuery($userId);
$user = $query->execute();
// 1. Busca no Redis Cache primeiro
// 2. Se cache MISS, busca no MySQL
// 3. Armazena resultado no Redis
// 4. Retorna usuário
```

### Endpoints Separados por Plataforma

**Web (Offset Pagination)**
```php
GET /web/api/users              # Todos sem paginação
GET /web/api/users/paginated    # Paginação offset (page, per_page)
GET /web/api/users/{id}         # Buscar por ID (cache)
POST /web/api/users             # Criar usuário
```

**Mobile (Cursor Pagination)**
```php
GET /mobile/api/users/paginated  # Paginação cursor (infinite scroll)
```
$command = new CreateUserCommand(
    name: 'John Doe',
    email: 'john@example.com',
    password: 'secret'
);
$userId = $this->createUserUseCase->execute($command);

// Query - Leitura (com cache)
$query = new FindUserByIdQuery($userId);
$user = $this->findUserByIdQuery->execute($query);
```

## 📦 Módulos

### Shared Module
Componentes compartilhados entre módulos:
- `BaseRepository`: Repositório base com cache
- `CacheService`: Serviço centralizado de cache
- `StructuredLogger`: Sistema de logs estruturado
- `ApiResponse`: Respostas HTTP padronizadas

### User Module
Módulo exemplo com CRUD completo:
- Entities: `User`
- ValueObjects: `Email`, `Password`
- Commands: `CreateUserCommand`
- Queries: `FindUserByIdQuery`
- Repository: `UserRepository`

## 🔧 Padrões Utilizados

- **Repository Pattern**: Abstração de persistência
- **Factory Pattern**: Criação de objetos complexos
- **Dependency Injection**: Inversão de controle
- **Cache-Aside**: Pattern de cache
- **Value Object**: Objetos imutáveis

## 📁 Árvore de Arquivos

```
boilerplate/
├── modules/                    # Módulos da aplicação
│   ├── Shared/                # Componentes compartilhados
│   │   ├── Domain/
│   │   │   └── Contracts/
│   │   ├── Application/
│   │   ├── Infrastructure/
│   │   │   ├── Cache/
│   │   │   ├── Logging/
│   │   │   ├── Persistence/
│   │   │   └── Concerns/
│   │   └── Interface/
│   │
│   └── User/                  # Módulo de usuários
│       ├── Domain/
│       │   ├── Entities/
│       │   ├── ValueObjects/
│       │   └── Contracts/
│       ├── Application/
│       │   ├── Commands/
│       │   └── Queries/
│       ├── Infrastructure/
│       │   ├── Persistence/
│       │   └── Providers/
│       └── Interface/
│           └── Http/
│
├── infrastructure/            # Infraestrutura
│   └── development/          # Docker para desenvolvimento
│       ├── docker-compose.yml
│       ├── Dockerfile
│       ├── mysql/
│       ├── redis/
│       └── php/
│
├── Routes/                   # Rotas da aplicação
│   └── api.php
│
├── config/                   # Configurações
│   ├── octane.php
│   └── ...
│
├── bootstrap/                # Bootstrap da aplicação
│   └── app.php
│
├── storage/                  # Armazenamento
│   ├── logs/
│   └── framework/
│
└── docs/                     # Documentação
    ├── ARCHITECTURE.md
    ├── DIAGRAMS.md
    ├── INFRASTRUCTURE.md
    └── COMMANDS.md
```

## 🚀 Vantagens da Arquitetura

1. **Separação de Responsabilidades**: Cada camada tem papel específico
2. **Testabilidade**: Domain puro, fácil de testar
3. **Escalabilidade**: Módulos independentes
4. **Manutenibilidade**: Código organizado e desacoplado
5. **Performance**: CQRS + Cache otimizam leituras
6. **Flexibilidade**: Fácil adicionar novos módulos

