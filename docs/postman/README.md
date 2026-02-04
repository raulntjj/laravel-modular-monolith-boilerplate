# 📮 Postman Collection

Collection pronta para testar a API do boilerplate Laravel DDD + CQRS com suporte para **Web** e **Mobile**.

## 📦 Arquivos

- **`Boilerplate.postman_collection.json`**: Collection principal com todas as rotas (Web + Mobile)
- **`Local.postman_environment.json`**: Variáveis de ambiente para desenvolvimento local

## 🚀 Como Importar

### 1. Importar Collection

1. Abra o Postman
2. Clique em **Import** (canto superior esquerdo)
3. Arraste o arquivo `Boilerplate.postman_collection.json`
4. Clique em **Import**

### 2. Importar Environment

1. No Postman, clique no ícone de **Environments** (⚙️ no canto superior direito)
2. Clique em **Import**
3. Selecione o arquivo `Local.postman_environment.json`
4. Ative o environment **Local Development** no dropdown

## 📋 Endpoints Disponíveis

### Health Check
- ✅ **GET** `/api/status` - Verifica status da aplicação (database + cache)

### Users - Web (Offset Pagination)
- 📋 **GET** `/web/api/users` - Listar todos (sem paginação)
- 📄 **GET** `/web/api/users/paginated` - Paginação offset (navegação por páginas)
- 🔍 **GET** `/web/api/users/{id}` - Buscar por ID (com cache)
- ➕ **POST** `/web/api/users` - Criar usuário

### Users - Mobile (Cursor Pagination)
- 📱 **GET** `/mobile/api/users/paginated` - Paginação cursor (infinite scroll)
- 🔄 **GET** `/mobile/api/users/paginated?cursor={cursor}` - Próxima página (auto-preenche)

### Cache Tests
Pasta especial para testar o padrão **Cache-Aside**:
1. Primeira busca (Cache MISS)
2. Segunda busca (Cache HIT)
3. Listagem paginada (Cache)

## 🔧 Variáveis de Ambiente

| Variável | Valor Padrão | Descrição |
|----------|--------------|-----------|
| `base_url` | `http://localhost:8001` | URL base da API |
| `user_id` | (auto) | ID do último usuário criado |
| `next_cursor` | (auto) | Cursor para próxima página (mobile) |
| `auth_token` | (vazio) | Token de autenticação (futuro) |

**Nota:** As variáveis `user_id` e `next_cursor` são automaticamente preenchidas pelos scripts.

## 🎯 Workflow Recomendado

### Testando Web (Offset Pagination)

1. **Health Check**
   ```
   GET /api/status
   ```

2. **Criar Usuário**
   ```
   POST /web/api/users
   Body: { name, email, password }
   → user_id será salvo automaticamente
   ```

3. **Listar Todos (sem paginação)**
   ```
   GET /web/api/users
   → Retorna todos os usuários
   ```

4. **Listar Paginado (offset)**
   ```
   GET /web/api/users/paginated?page=1&per_page=15
   → Retorna: current_page, total, last_page, next_url
   ```

5. **Buscar Usuário por ID**
   ```
   GET /web/api/users/{{user_id}}
   → Usa cache (primeira vez MISS, depois HIT)
   ```

### Testando Mobile (Cursor Pagination)

1. **Primeira Página**
   ```
   GET /mobile/api/users/paginated?per_page=20
   → Retorna: users, next_cursor, has_more
   → next_cursor é salvo automaticamente
   ```

2. **Próxima Página (Infinite Scroll)**
   ```
   GET /mobile/api/users/paginated?cursor={{next_cursor}}&per_page=20
   → Usa cursor da resposta anterior
   → Novo cursor é salvo para continuar
   ```

3. **Continue navegando...**
   - Execute a mesma requisição para carregar mais
   - Quando `has_more = false`, não há mais dados

### Testando Cache (CQRS)

Execute os requests da pasta **Cache Tests** em sequência para ver:

1. 🔴 **Cache MISS**: Primeira busca vai no MySQL Write
2. 🟢 **Cache HIT**: Segunda busca retorna do Redis Cache (mais rápido)
3. 📄 **Listagem Paginada**: Também usa cache (TTL 5 minutos)

Observe os tempos de resposta para ver a diferença de performance!

## 📊 Verificando Cache

### Via DataGrip (Redis Cache - Port 6379)

```redis
# Ver todas as chaves
KEYS *

# Ver dados de um usuário específico
GET users:1

# Ver TTL de uma chave
TTL users:1
```

### Via Terminal

```bash
# Acessar Redis Cache
docker compose exec redis-cache redis-cli

# Comandos úteis
> KEYS users:*          # Ver chaves de usuários
> GET users:1           # Ver dados cached
> TTL users:1           # Ver tempo de expiração
> FLUSHDB               # Limpar cache (use com cuidado)
```

## 🐛 Troubleshooting

### Connection Refused

Verifique se o Docker está rodando:
```bash
cd infrastructure/development
docker compose ps
```

### Endpoint Not Found

Verifique as rotas disponíveis:
```bash
docker compose exec backend php artisan route:list --path=web
docker compose exec backend php artisan route:list --path=mobile
```

### Cache não está funcionando

Verifique conexão com Redis:
```bash
docker compose exec redis-cache redis-cli ping
# Deve retornar: PONG
```

## 💡 Dicas

1. **Use o Runner**: Execute toda a collection de uma vez com Postman Runner
2. **Monitore logs**: Acompanhe logs do backend para ver queries SQL e cache
3. **Compare tempos**: Note die `next_cursor` são salvos automaticamente
5. **Paginação Web vs Mobile**:
   - **Web**: Use offset pagination para navegação por páginas
   - **Mobile**: Use cursor pagination para infinite scroll
6. **Performance**: Cursor pagination é mais eficiente em grandes datasets

## 📱 Diferenças Web vs Mobile

| Característica | Web (Offset) | Mobile (Cursor) |
|----------------|-------------|-----------------|
| URL | `/web/api/users/paginated` | `/mobile/api/users/paginated` |
| Parâmetros | `page`, `per_page` | `cursor`, `per_page` |
| Navegação | Por número de página | Por cursor opaco |
| Total conhecido | ✅ Sim | ❌ Não |
| Performance | Degrada em páginas altas | Consistente |
| Uso ideal | Tabelas, paginação tradicional | Infinite scroll, feeds |
| Cache | ✅ Sim (5 min) | ❌ Não (sempre fresh) |

## 🔗 Links Úteis

- [Padrão de Endpoints](../ENDPOINTS_PATTERN.md)
- [Documentação da Arquitetura](../ARCHITECTURE.md)
- [Infraestrutura Docker](../INFRASTRUCTURE.md)
- [Comandos Disponíveis](..s/INFRASTRUCTURE.md)
- [Comandos Disponíveis](../docs/COMMANDS.md)
