
# VinylCatalog (Catálogo de Discos) 💿

Aplicação web em PHP puro (arquitetura MVC simplificada) para gerenciar sua coleção de discos. O sistema permite controle total de usuários por administradores e gerenciamento de discos com upload de capas.

## 🚀 Tecnologias

- **PHP 8.x**
- **MySQL** (via PDO)
- **Bootstrap 5** & **Bootstrap Icons**
- **JavaScript** (Vanilla)

## 📋 Requisitos

- PHP com extensão **PDO** e **GD** (para imagens) habilitadas.
- MySQL/MariaDB.
- Permissão de escrita na pasta `public/uploads`.

## ⚙️ Configuração

1. Crie um arquivo `.env` na raiz do projeto (ou edite o existente):
```env
DB_HOST=localhost
DB_NAME=catalogo_discos
DB_USER=root
DB_PASS=suasenha
```

## 🗄️ Estrutura do Banco de Dados (SQL)


```sql
CREATE DATABASE IF NOT EXISTS catalogo_discos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE catalogo_discos;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  is_admin BOOLEAN NOT NULL DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

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
  CONSTRAINT fk_discos_usuario FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

INSERT INTO usuarios (nome, email, password, is_admin) 
VALUES ('Administrador', 'admin@admin.com', '$2y$10$OJfE8IlgNYEV9F58HqR.MOiEdRJ3ajL8R/3aLWlFxQs3xYLpuq/A6', 1);

```

## 🛣️ Rotas do Sistema

### Públicas
- `GET/POST /login` - Acesso ao sistema
- `GET/POST /register` - Cadastro de novos colecionadores

### Coleção (Privado)
- `GET /` ou `/colecao` - Dashboard com filtros e busca
- `GET /create` | `POST /store` - Adicionar novo disco
- `GET /edit/{id}` | `POST /update` - Editar dados do disco
- `POST /delete` - Remover disco da coleção

### Administrativo (Somente Admin)
- `GET /users` - Listagem de todos os usuários
- `GET /users/create` | `POST /users/store` - Novo usuário via admin
- `GET /users/edit/{id}` | `POST /users/update` - Editar qualquer usuário (incluindo nível de acesso)
- `POST /users/delete` - Remover usuário do sistema

### Perfil
- `GET /profile` | `POST /profile` - Meus dados e alteração de senha

## 🛡️ Segurança Implementada

- **BCRYPT**: Proteção de senhas com `password_hash`.
- **CSRF Protection**: Tokens de validação em todos os formulários `POST`.
- **Middleware de Autenticação**: Verificação de sessão e nível de privilégio (`is_admin`).
- **PDO Prepared Statements**: Proteção total contra SQL Injection.

## 📂 Estrutura de Pastas

```text
├── app/
│   ├── controllers/   # Lógica de controle
│   ├── core/          # Classes base (Router, Database, Auth...)
│   ├── models/        # Classes de dados (Usuario, Disco)
│   ├── repositories/  # Consultas ao banco de dados
│   └── views/         # Arquivos .php de visualização
├── public/
│   ├── assets/        # CSS, JS e Imagens estáticas
│   ├── uploads/       # Capas dos discos enviadas
│   └── index.php      # Front Controller (Entrada do app)
└── .env               # Configurações sensíveis
```

---
