# PHP com Docker e MySQL

Ambiente local com PHP 8.3 (FPM), Nginx e MySQL 8.4.

O projeto é uma implementação acadêmica da plataforma **Rooted**, baseada no contexto em
`docs/diagramas/contexto-uml-rooted.md`.

As decisões editáveis para futuras implementações estão em
[docs/CONTEXTO-PROJETO.md](docs/CONTEXTO-PROJETO.md).

## Executar

No Workbench, crie uma nova conexão:

- Host: 127.0.0.1
- Porta: 3307
- Usuário: rooted_app
- Senha: rooted_password

```bash
docker compose up -d --build
```

Acesse http://localhost:8080.

## Estrutura MVC

```text
src/
├── index.php
├── model/
├── view/
│   ├── layouts/
│   ├── pages/
│   └── assets/
└── controller/
```

## Comandos úteis

```bash
# Ver logs
docker compose logs -f

# Parar os containers
docker compose down

# Parar e remover também os dados do banco
docker compose down -v

# Entrar no PHP
docker compose exec php bash

# Entrar no MySQL
docker compose exec db mysql -uapp -papp app
```
