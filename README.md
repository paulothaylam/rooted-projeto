# Rooted

Aplicação acadêmica de eventos construída em PHP, com MySQL e execução local em Docker.
O código atual, e não propostas anteriores de arquitetura, é a fonte de verdade para
funcionalidades e diagramas.

## Escopo

O projeto está fechado para novas funcionalidades. Não serão adicionadas integrações
externas de negócio ou autenticação: pagamentos, login social, envio de e-mail e serviços
de armazenamento não fazem parte do sistema. O fluxo de eventos pagos permanece sem
publicação e sem processamento de pagamento. O Bootstrap 5 carregado pela interface é
um recurso visual, não uma integração funcional.

O sistema oferece cadastro e login por e-mail e senha, navegação de eventos, reservas e
inscrições gratuitas, gerenciamento básico de eventos, edição de perfil, cancelamento
de inscrição e credenciamento manual. No cadastro, a pessoa escolhe participante ou
organizador; a área Seeds é exclusiva da conta administrativa. Consulte
[docs/CONTEXTO-PROJETO.md](docs/CONTEXTO-PROJETO.md)
para limites e comportamentos detalhados.

## Executar

```bash
docker compose up -d --build
```

Acesse http://localhost:8083.

O banco deste ambiente já recebeu a coluna de tipo de conta e a conta administrativa foi
promovida. Em uma cópia com banco antigo, aplique uma vez
[`docker/mysql/migrations/2026-11-24-add-tipo-perfil.sql`](docker/mysql/migrations/2026-11-24-add-tipo-perfil.sql)
antes de usar a versão atual.

Para parar os containers:

```bash
docker compose down
```

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

## Diagramas UML

Os scripts PlantUML estão em [docs/diagramas/scripts/](docs/diagramas/scripts/). Há 21
diagramas, limitados aos tipos solicitados: caso de uso, classe, objeto, pacote, sequência,
comunicação, estado e atividade. O contexto de modelagem está em
[docs/diagramas/contexto-uml-rooted.md](docs/diagramas/contexto-uml-rooted.md).
