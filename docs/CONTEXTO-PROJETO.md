# Contexto atual do projeto Rooted

## Objetivo e escopo

Rooted é um projeto acadêmico de eventos, implementado em PHP com arquitetura MVC,
MySQL e Docker (PHP-FPM e Nginx). O escopo está encerrado: não estão planejadas novas
funcionalidades nem integrações externas de negócio ou autenticação. Os diagramas
representam o comportamento que existe no código atual, não propostas antigas ou planos.

Não há processamento de pagamentos, login social, envio de e-mail, armazenamento externo
ou chamadas a APIs de terceiros. Eventos pagos não podem ser publicados. A página usa
Bootstrap 5 via CDN para apresentação visual; isso não corresponde a uma integração
funcional do domínio.

## Funcionalidades implementadas

- Cadastro com nome, e-mail, CPF, telefone, senha e escolha entre participante e
  organizador; a senha é persistida com bcrypt.
- Login por e-mail e senha, sessão PHP e logout.
- Vitrine pública com busca por nome, organização ou local e filtros de tipo.
- Página de detalhes de evento.
- Inscrição imediata para evento gratuito; evento pago cria apenas uma reserva com prazo
  armazenado de 15 minutos, sem pagamento.
- Criação de evento em rascunho, edição e exclusão quando não há inscrições.
- Publicação de evento gratuito; evento beneficente vai para `EM_APROVACAO`; evento pago
  tem publicação bloqueada.
- Painel do participante com inscrições, edição de perfil, cancelamento de inscrição e
  exclusão de conta quando não há inscrições.
- Painel do organizador com eventos e inscritos.
- Credenciamento manual, pesquisando participante por nome ou CPF e registrando check-in.
- Painel da Seeds que exibe organizações e eventos beneficentes. Botões de aprovação,
  recusa, bloqueio e demais ações sem implementação ficam desabilitados ou são apenas
  conteúdo visual.
- Os painéis pós-login têm uma barra horizontal no topo para alternar entre as áreas de
  participante e organizador; Seeds aparece somente para a conta administradora. Admin
  também pode acessar os painéis de participante e organizador.
  A opção do cadastro determina a área inicial, mas contas comuns podem acessar ambas.

## Limites que os diagramas devem preservar

- O prazo da reserva é gravado em `vence_em`, mas o PHP atual não libera
  automaticamente reservas vencidas nem altera sua situação por expiração.
- O check-in é um registro separado; não muda a situação da inscrição.
- As situações de inscrição usadas pelo código são `ATIVA` e `CANCELADA`.
- As situações de evento usadas nos fluxos atuais incluem `RASCUNHO`, `PUBLICADO` e
  `EM_APROVACAO`. Aprovação ou recusa de evento beneficente não está implementada.
- O check-in é manual. Não há leitura de QR Code.
- A área Seeds e suas rotas são restritas ao papel `admin`; cadastro público só aceita
  `participante` ou `organizador`. Contas comuns podem acessar as áreas de participante
  e organizador; admins podem acessar as três.
- O código não vincula operações de eventos ao proprietário do evento.
- A interface de vendas/taxas e reembolsos da Seeds é apenas placeholder; não representa
  dados nem operações disponíveis.

## Estrutura

```text
src/
├── index.php
├── controller/HomeController.php
├── model/
└── view/
    ├── layouts/
    ├── pages/
    └── assets/
```

`HomeController` encaminha as páginas por `?pagina=...`. `Database` fornece conexão PDO;
`Perfil`, `Evento` e `Compra` concentram operações de persistência e fluxo. As tabelas
usadas incluem `perfis`, `organizacoes`, `eventos`, `tipos_ingresso`, `reservas`,
`inscricoes`, `checkins` e `teste_conexao`.

## Documentação UML

Os scripts PlantUML em `docs/diagramas/scripts/` cobrem somente estes oito tipos:
caso de uso, classe, objeto, pacote, sequência, comunicação, estado e atividade.
Referências a integrações e comportamentos não implementados devem ser excluídas dos
diagramas. Consulte [contexto-uml-rooted.md](diagramas/contexto-uml-rooted.md) para a
lista e os critérios de modelagem.
