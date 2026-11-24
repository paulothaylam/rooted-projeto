# Contexto editável do projeto Rooted

## Objetivo

Mantenha as decisões deste projeto neste arquivo e atualize-o quando o escopo mudar.

## Estado atual

- Projeto acadêmico da plataforma Rooted.
- Ambiente Docker com PHP, Nginx e MySQL.
- Estrutura MVC simples em `src/model`, `src/view` e `src/controller`.
- A página inicial apresenta a plataforma Rooted e o escopo da fase 1.
- Bootstrap 5 está carregado por CDN.
- O ícone da marca é o emoji 🌱.
- Existem telas iniciais de login e cadastro em `src/view/pages`.
- Cadastro e login usam MySQL e senhas com bcrypt.
- O usuário é autenticado por e-mail e senha.
- Após cadastro válido, o sistema redireciona automaticamente para o login.

## Fluxo de desenvolvimento das telas pós-login

As telas serão criadas uma por vez. Nenhuma tela nova será considerada aprovada até o usuário visualizar e confirmar o protótipo.

1. Identificar o painel adequado ao perfil autenticado.
2. Criar e mostrar somente a primeira tela da etapa.
3. Registrar decisões e pendências neste arquivo.
4. Aguardar aprovação ou ajustes.
5. Implementar a próxima tela somente após autorização.

Fluxos da fase 1 que devem orientar as telas:

- Participante: vitrine, detalhes do evento, compra, inscrições, perfil e QR pessoal.
- Organizador: painel, criação e publicação de eventos, inscrições, vendas e Mercado Pago.
- Credenciamento: leitura do QR, resultado da validação e presença em tempo real.
- Administrador da Seeds: aprovar organizações, aprovar eventos beneficentes, bloquear organizadores e acompanhar vendas.

## Ordem proposta das próximas telas

1. Painel do participante.
2. Vitrine de eventos.
3. Página de detalhes do evento.
4. Fluxo de compra e reserva.
5. Painel do organizador.
6. Criar e publicar evento.
7. Tela de credenciamento.
8. Painel da Seeds.

## Progressos concluídos

- Painel inicial do participante criado.
- Login bem-sucedido redireciona para `?pagina=painel-participante`.
- Tela mostra nome do usuário, minhas inscrições, QR pessoal e perfil.
- Primeira implementação cobre apenas a interface; dados reais de inscrições e QR ainda não foram criados.
- Vitrine pública de eventos criada com busca visual, filtros e estado vazio.
- Filtros pagos, gratuitos e dados reais de eventos ainda não estão implementados.
- Navegação da vitrine adicionada à home e ao painel do participante.
- Página de detalhes ligada aos registros reais da tabela `eventos`.
- Eventos de exemplo disponíveis: Conecta Innovation, Rooted Experience e Futuro Digital.
- Filtros da vitrine funcionam por busca e tipo de evento.
- Cards de evento mostram a quantidade de ingressos disponíveis.
- Painel do participante mostra todas as inscrições vinculadas ao usuário.
- Busca da vitrine aceita nome do evento, organização e local.
- Filtros Pagos e Gratuitos consultam o banco; Beneficente aparece em Gratuitos.
- Quantidades atuais dos eventos de exemplo: Conecta Innovation 98, Rooted Experience 100 e Futuro Digital 100.
- Próxima tela aprovada: fluxo de reserva e inscrição baseado na atividade de compra.
- Tela de reserva criada em `/?pagina=reservar&id=<evento>`.
- Eventos gratuitos confirmam a inscrição diretamente.
- Eventos pagos exibem reserva por 15 minutos, ainda sem Mercado Pago nesta etapa.
- Tabelas criadas: `tipos_ingresso`, `reservas` e `inscricoes`.
- Fluxo gratuito testado com sucesso no banco.
- Eventos reais de exemplo foram adicionados ao banco: Conecta Innovation, Rooted Experience e Futuro Digital.
- Tela 1 das cinco telas pendentes criada: painel do organizador.
- Link para o painel do organizador incluído na home e no painel do participante.
- Tela 2 criada: formulário de criação de evento em rascunho.
- Tela 2 testada com evento rascunho e 75 ingressos.
- Tela 3 criada: lista de inscritos por evento.
- Tela 3 validada com filtro por evento e CPF mascarado.
- Tela 4 criada: credenciamento com busca manual e registros de check-in.
- Tela 4 testada: entrada registrada e entrada duplicada bloqueada.
- Tela 5 criada: painel administrativo da Seeds.
- Telas pendentes desta sequência concluídas e aprovadas: painel do organizador, criar evento, inscritos, credenciamento e painel da Seeds.
- Navegação pós-login organizada por áreas: participante, organizador e Seeds dentro dos próprios painéis.
- Home pública mantém apenas eventos, conteúdo institucional e acesso à conta.

## Próxima validação

Aguardar aprovação da página de detalhes do evento antes de implementar o fluxo de compra.
- Os diagramas UML estão em `docs/diagramas/`.

## Stack planejada

- Front-end: React + Vite
- API: Spring Boot, Java 21+, REST e JWT
- Banco: MySQL + Flyway
- Pagamentos: Mercado Pago Checkout Pro
- Arquivos: Cloudflare R2 ou AWS S3
- Login social: Google OAuth2
- E-mail: Resend
- Monitoramento: Sentry

## Primeiro escopo

A fase 1 cobre venda de ingressos e credenciamento de um evento real.

Públicos:

- Público
- Participante
- Organizador
- Equipe de credenciamento
- Administrador da Seeds

## Regras essenciais

- A API decide as regras; o navegador apenas mostra e encaminha.
- Participantes precisam de conta e possuem um QR pessoal.
- O CPF não aparece completo fora do perfil do dono.
- Reservas vencidas liberam a vaga.
- Compras simultâneas não podem vender a mesma vaga.
- Webhooks repetidos não podem gerar inscrições duplicadas.
- Evento pago não é publicado sem conta Mercado Pago conectada.
- A Seeds recebe 3% por ingresso pago.

## Estrutura MVC atual

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

## Fases futuras

### Fase 2

- Fila de espera
- Checklist do organizador
- Certificados
- Portal do aluno
- Níveis bronze, prata e ouro
- Cupons e lotes
- Página da organização

### Fase 3

- Check-in offline
- WhatsApp
- Networking
- Chatbot
- Mapa do local
- Autoatendimento

## Decisões pendentes

- Associação definitiva entre Reembolso e Inscrição.
- Reserva como entidade própria ou estado do Pedido.
- Quantidade de ingressos por compra.
- Política de reembolso parcial do evento.
- Taxa mínima da Seeds.
- Quem absorve ou repassa tarifas.
- Tratamento da tarifa pelo Mercado Pago em reembolsos.

## Instruções para futuras solicitações

1. Consulte este arquivo e `docs/diagramas/contexto-uml-rooted.md`.
2. Não invente requisitos não documentados.
3. Marque sugestões como `[SUGESTÃO]`.
4. Não implemente fase 2 ou fase 3 sem pedido explícito.
5. Preserve `model`, `view`, `controller` e os layouts compartilhados.
6. Preserve Bootstrap 5 por CDN.
7. Use português nos nomes de domínio e interfaces.

## Itens pendentes

- [ ] Nome final do produto
- [ ] Design visual da vitrine
- [ ] Associação Reembolso–Pedido–Inscrição
- [ ] Quantidade de ingressos por compra
- [ ] Política de reembolso após 7 dias
- [ ] Taxa mínima da Seeds
- [x] Publicação de eventos gratuitos pelo painel do organizador
- [x] Bloqueio de eventos pagos sem integração Mercado Pago
- [x] Envio de eventos beneficentes para aprovação da Seeds
- [x] Publicação controlada pelas situações RASCUNHO, EM_APROVACAO e PUBLICADO
- [ ] Painel de detalhes para editar ou excluir eventos do organizador
- [x] Painel de detalhes do evento permite editar dados e quantidade de ingressos
- [x] Exclusão bloqueada quando o evento possui inscrições
- [x] Editar perfil da conta
- [x] Excluir conta com bloqueio enquanto houver inscrições
- [x] Cancelar inscrição pelo painel do participante
