# VinylCatalog (Catálogo de Discos)

Aplicação web em PHP (sem framework) para gerenciar sua coleção de discos. Você pode registrar, fazer login e cadastrar discos com capa (upload), além de buscar e filtrar sua coleção.

## Tecnologias

- PHP
- MySQL (via PDO)
- Bootstrap 5 (CSS/JS locais em `public/assets/bootstrap` + Bootstrap Icons via CDN)
- JavaScript (modais/alertas)

## Requisitos

- PHP com extensão PDO habilitada
- MySQL/MariaDB
- Pasta `public/uploads` com permissão de escrita (para salvar as capas)

## Configuração do banco

1. Copie/edite o arquivo `.env` na raiz do projeto.
2. Configure:
   - `DB_HOST`
   - `DB_NAME`
   - `DB_USER`
   - `DB_PASS`

Exemplo (arquivo `.env`):

```env
DB_HOST=localhost
DB_NAME=catalogo_discos
DB_USER=root
DB_PASS=...
```

## Tabelas (SQL sugerido)

O projeto espera estas tabelas/colunas:

- `usuarios`: `id`, `nome`, `email`, `password`
- `discos`: `id`, `nome`, `genero`, `artista`, `avaliacao`, `formato`, `status`, `user_id`, `capa`

Use como base o script abaixo (ajuste nomes/tamanhos se necessário):

```sql
CREATE DATABASE IF NOT EXISTS catalogo_discos
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE catalogo_discos;

-- 1. Tabela de Usuários (Adicionado is_admin)
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  is_admin BOOLEAN NOT NULL DEFAULT FALSE, -- O "crachá" de administrador
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabela de Discos (Relacionada ao usuário)
CREATE TABLE IF NOT EXISTS discos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  genero VARCHAR(255) NOT NULL,
  artista VARCHAR(255) NOT NULL,
  avaliacao DECIMAL(4,1) NOT NULL DEFAULT 0,
  formato VARCHAR(100) NOT NULL,
  status VARCHAR(100) NOT NULL,
  user_id INT NOT NULL,
  capa VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  -- Chave Estrangeira com deleção em cascata
  CONSTRAINT fk_discos_usuario
    FOREIGN KEY (user_id) REFERENCES usuarios(id)
    ON DELETE CASCADE
);

CREATE INDEX idx_discos_user_id ON discos(user_id);
CREATE INDEX idx_usuarios_email ON usuarios(email); 

INSERT INTO usuarios (nome, email, password, is_admin) 
VALUES ('Administrador', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);
```

## Como rodar

1. Aponte seu servidor web para a pasta `public/` (por exemplo, com Laragon).
2. Acesse:
   - `/login`
   - `/register`
3. Após logar, você entra em `/` (lista e filtros da coleção).

## Rotas principais

- `GET/POST /login`  
- `GET/POST /register`  
- `GET /logout`  

- `GET /` (lista de discos com busca/filtros/paginação)
- `GET /colecao` (mesma tela de `/`)
- `GET /create` e `POST /store` (criar disco)
- `GET /edit/{id}` e `POST /update` (editar disco)
- `GET /disco/{id}` (detalhes do disco)
- `POST /delete` (excluir disco)

- `GET /profile` e `POST /profile` (atualizar nome e e-mail)

### Filtros na lista (`/`)

Você pode usar query string na URL:

- `q`: busca por `nome`, `artista` e `genero`
- `status`: `Na Coleção`, `Desejado` ou `Emprestado`
- `genero`: opções configuradas no front
- `formato`: opções configuradas no front
- `sort`: ordenação (ex.: `nome_asc`, `avaliacao_desc`)
- `page`: número da página

## Segurança

- Senhas: hash com `password_hash()` + verificação com `password_verify()`.
- CSRF: tokens gerados pelo `app/core/Csrf.php` e validados nos POST (ex.: delete, store, update, profile).
- Acesso às rotas protegidas: as rotas do catálogo exigem login via `app/core/Auth.php`.

## Estrutura do projeto

- `public/`
  - `index.php`: front controller e definição das rotas
  - `assets/`: CSS/JS do app e assets do Bootstrap
  - `uploads/`: local para capas
- `app/`
  - `core/`: Router, Auth, Flash, View, Csrf, Database
  - `controllers/`: DiscoController, AuthController
  - `repositories/`: DiscoRepository, UsuarioRepository
  - `models/`: Disco, Usuario
  - `views/`: layouts, partials e telas

## Próximos passos (ideias)

- Página de detalhes com mais seções (ações rápidas, histórico e layout melhorado)
- Upload com validações mais completas e feedback visual
- Paginação e ordenação mais sofisticadas (ex.: multi-filtro com contagens por categoria)

