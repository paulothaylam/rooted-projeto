# Contexto para modelagem UML: Plataforma de eventos Rooted (Seeds Tecnologia)

> Cole este documento inteiro na IA e diga qual **parte** (seção 8) ela deve fazer. Se a IA aceitar anexos, anexe também os 4 documentos originais da Rooted. Eles são a fonte de verdade; este texto é um resumo guiado.

---

## 1. Seu papel

Você vai me ajudar a produzir os **diagramas UML** da plataforma **Rooted**, um projeto novo da **Seeds Tecnologia**. Eu sou um dos desenvolvedores juniores do time e vou montar os diagramas finais no **Modelio** (ferramenta de modelagem UML/BPMN). Eu quero entender o que estou desenhando, então explique brevemente as decisões de modelagem em vez de só despejar diagramas.

Regras de ouro para você:

1. **Não invente requisitos.** Tudo que vier dos documentos deve ser tratado como fato. Tudo que for sugestão sua (atributos, métodos, enums extras) deve vir marcado como **[SUGESTÃO]** para eu confirmar.
2. **Respeite o escopo.** Fase 1 é o que deve ser modelado em detalhe. Fases 2 e 3 só aparecem se eu pedir, e sempre marcadas como "fase 2" ou "fase 3".
3. **Pergunte antes de supor** quando algo for ambíguo (a seção 10 lista as ambiguidades que já encontrei).
4. **Formato de entrega**: veja a seção 9.

**Decisões já confirmadas por mim (não precisa perguntar de novo):**

- Eu sou responsável por **todos os diagramas** do projeto (partes A a I da seção 8).
- Nomes de classes, atributos, operações e atores em **português**.
- A faculdade não exige tipo de diagrama nem formato: tenho liberdade, desde que **siga os documentos** da Rooted.
- Uso a **versão mais recente do Modelio**.
- O modelo deve ser **organizado por fases** (seção 8): as fases 1, 2 e 3 do documento viram **pacotes separados**, para o modelo ficar limpo e fácil de navegar.

---

## 2. Sobre a Seeds Tecnologia

- **Software house** que abriga **programadores juniores** e tem como objetivo principal **ajudar outras empresas com soluções tecnológicas**.
- Os documentos do projeto são marcados como "Documento interno da Seeds Tecnologia", feitos por Marino Martins Dutra.
- A Seeds trabalha com um **stack padrão para todos os projetos** (React + Spring Boot + MySQL), de propósito, para o time **reaproveitar código e conhecimento** entre projetos. Isso importa para a modelagem: prefira estruturas simples, convencionais e fáceis de um júnior manter.
- O time se organiza com quadro de tarefas (A fazer, Análise, Desenvolvimento, Testes, Finalizado), pull request revisado por outra pessoa e foco em aprender fazendo.
- Observação: uma busca na web não retornou página oficial confiável da Seeds. As informações acima vêm dos documentos e de mim. Não complete com dados externos.

## 3. Sobre o projeto Rooted

**O que é:** plataforma onde **organizações do ecossistema do Oxygeni Hub** (hub de inovação ligado à Universidade Ceuma, em São Luís/MA) **criam eventos e vendem ingressos**, e onde **qualquer pessoa encontra eventos, se inscreve e entra com um QR Code pessoal**.

**Por que reflete a filosofia da Seeds:** é uma solução tecnológica que ajuda *outras organizações* (os organizadores) a rodar seus eventos, construída por um time júnior com stack padronizado.

**Dinheiro:** passa pelo **Mercado Pago (Checkout Pro, modelo marketplace)**. A Seeds recebe **3%** de cada ingresso pago; o resto vai direto para a conta do organizador. A Seeds nunca guarda dinheiro de ninguém.

**Nome:** provisório. "Rooted" é nome de trabalho (alternativas: Conecta Hub ou Rooted Connect).

### Stack e componentes

| Componente | Tecnologia |
|---|---|
| Front-end | React + Vite (hospedado em Vercel/Netlify/Cloudflare Pages) |
| API | Spring Boot (Java 21+), monólito modular, REST + JSON, JWT, sem estado |
| Banco | MySQL, migrações com Flyway |
| Arquivos | Cloudflare R2 ou AWS S3 (capas, materiais, certificados) |
| Pagamentos | Mercado Pago Checkout Pro (marketplace, OAuth com state e PKCE) |
| Login social | Google OAuth2 |
| E-mail | Resend via SMTP (Spring Mail) |
| Monitoramento | Sentry |
| Bibliotecas úteis | ZXing (QR), OpenPDF (certificados), Apache POI (planilhas), html5-qrcode (leitura no front) |

### Atores

| Ator | Papel |
|---|---|
| Público | Sem login: vê vitrine e página do evento |
| Participante | Com conta: compra, tem QR pessoal, cancela e transfere inscrição |
| Organizador | Membro de organização aprovada: cria eventos, acompanha inscritos e vendas |
| Equipe de credenciamento | Convidada pelo organizador: lê QR Codes na entrada |
| Administrador da Seeds | Aprova organizações e eventos beneficentes, vê vendas/taxas, bloqueia organizadores |
| Mercado Pago (sistema externo) | Processa pagamentos, divide a taxa, avisa a API por webhook |
| Google (sistema externo) | Confirma identidade no login social |
| Serviço de e-mail (sistema externo) | Envia e-mails (Resend) |

### Escopo por fase

- **Fase 1** (RF01 a RF47): vender ingressos e fazer check-in de um evento real. **É o foco dos diagramas.**
- **Fase 2** (RF48 a RF55): fila de espera, checklist do organizador, certificados, Portal do aluno, níveis bronze/prata/ouro, cupons, lotes, página da organização.
- **Fase 3** (RF56 a RF61): check-in offline (PWA), WhatsApp, networking, chatbot, mapa do local, autoatendimento.

---

## 4. Arquitetura da API (importante para diagramas de componentes e pacotes)

A API é **um único projeto Spring Boot** dividido em **módulos**. Dentro de cada módulo, todo pedido passa pelas mesmas camadas, nesta ordem:

**Controller** (recebe HTTP, sem regra de negócio) → **Service** (regras de negócio e início da transação) → **Repository** (Spring Data JPA, não decide nada) → **Entidades e DTOs** (entidade = tabela em classe Java; DTO = formato de entrada/saída da API).

**Módulos:** `auth`, `organizacoes`, `eventos`, `pedidos` (inclui integração Mercado Pago), `inscricoes`, `checkin`, `notificacoes`, `admin`. Mais as pastas `config` (segurança, CORS) e `jobs` (tarefas agendadas).

Pacote raiz: `br.com.seeds.rooted`. Cada módulo guarda as próprias camadas.

**Decisões de arquitetura já tomadas:** monólito modular; front separado (REST + JSON + JWT); API sem estado; Checkout Pro no modelo marketplace; banco versionado por Flyway.

**Regra de ouro (aparece em todos os documentos):** o front só mostra e encaminha; **quem decide é a API**. A API nunca confia no que vem do navegador.

**Tarefas agendadas (`@Scheduled`):** liberar reservas vencidas (a cada poucos minutos), enviar lembrete do evento (diário), renovar tokens do Mercado Pago perto de vencer (diário), chamar próximo da fila (fase 2).

---

## 5. Modelo de dados (base para o diagrama de classes)

Tabelas da fase 1, direto do documento. Cada uma vira uma entidade Java.

| Entidade | O que guarda (conforme o documento) |
|---|---|
| Perfil | nome, e-mail, CPF, telefone, código do QR pessoal, nível da conta, papel (participante ou administrador da Seeds) |
| Organizacao | nome, resumo, redes sociais, situação da aprovação |
| MembroOrganizacao | quem participa de cada organização e o papel (administrador ou equipe de credenciamento) |
| ContaMercadoPago | tokens de acesso (criptografados) e data de vencimento |
| Evento | dados do evento, situação (rascunho, publicado, cancelado), tipo (pago, gratuito, beneficente), política de reembolso, quem paga as taxas |
| LocalEvento | endereço ou link online (um evento pode ter mais de um) |
| ModeloEvento | estrutura salva para duplicar em novas edições |
| TipoIngresso | nome, público, preço, vagas, datas de venda, formas de pagamento, máximo de parcelas |
| Pedido | comprador, ingresso, valor, taxas, situação, códigos da preferência e do pagamento no Mercado Pago |
| Reserva | vaga reservada, tipo de ingresso, comprador, horário em que vence |
| Inscricao | titular, evento, tipo de ingresso, situação (ativa, cancelada, transferida) |
| Reembolso | pedido, valor devolvido, motivo, data |
| Checkin | inscrição, horário, quem fez a leitura |
| AvisoPagamento | cada webhook recebido, com o **código do pagamento único** (evita processar duas vezes) |
| AceiteTermos | quem aceitou, versão dos Termos e da Política de Privacidade, quando |

Fase 2 (só se pedido): FilaEspera (posição, situação aguardando/chamado/inscrito/expirado, prazo), Tarefa, Material, Certificado.

### Relações citadas nos documentos

- Uma **organização** tem vários **membros**, vários **eventos** e **uma** conta do Mercado Pago conectada.
- Um **evento** tem um ou mais **locais** e um ou mais **tipos de ingresso**.
- Um **pedido** pertence a um **perfil** e a um **tipo de ingresso**. Quando **aprovado**, gera uma **inscrição**.
- Uma **inscrição** pode ter um **check-in** e um ou mais **reembolsos**.

### Enumerações que aparecem nos documentos

- Papel do perfil: participante, administrador da Seeds
- Papel de membro: administrador, equipe de credenciamento
- Situação do evento: rascunho, publicado, cancelado
- Tipo do evento: pago, gratuito, beneficente
- Situação da inscrição: ativa, cancelada, transferida
- Situação do pedido: aprovado, pendente, recusado, vencido (e `RESERVADO` no exemplo de resposta da API)
- Política de reembolso após 7 dias: integral até uma data, parcial até uma data, ou só transferência **[opções ainda são sugestão]**

Tipos de atributo, visibilidade, métodos e nomes exatos de FKs **não estão nos documentos**. Marque como **[SUGESTÃO]**.

---

## 6. Regras de negócio que afetam os diagramas

| Regra | Efeito na modelagem |
|---|---|
| Seeds recebe **3%** por ingresso pago, separado pelo Mercado Pago na venda | Pedido guarda valor e taxa; cálculo no Service |
| Taxa mínima **R$ 1,00** por ingresso **[sugestão]** | Parâmetro de cálculo |
| Evento **gratuito** e **beneficente** não pagam taxa; beneficente é declarado pelo organizador e **aprovado pela Seeds** | Fluxo de aprovação no ciclo de vida do Evento |
| Tarifa do MP é descontada do organizador, que escolhe por evento se absorve ou repassa **[repasse é sugestão]** | Atributo "quem paga as taxas" no Evento |
| Reembolso **integral até 7 dias** após a compra; depois vale a política do evento | Fluxo de cancelamento com decisão |
| Em todo cancelamento, a **vaga volta a ficar disponível** | Efeito colateral no fluxo de cancelamento |
| **Conta obrigatória** para participante; um QR pessoal para todos os eventos (código aleatório, **nunca o CPF**) | Perfil guarda código do QR |
| Transferência de ingresso até **24h** antes; novo titular ganha QR próprio e o antigo **deixa de valer** | Estados da Inscrição |
| Reserva de vaga por **15 min** no cartão e Pix **[sugestão]**; boleto vence em pelo menos 3 dias e sai das opções a menos de 5 dias do evento | Reserva com vencimento; job de liberação |
| Um ingresso por compra, em nome de quem compra **[sugestão]** | Multiplicidade Pedido → Inscrição |
| **Evento pago não publica** sem conta Mercado Pago conectada | Pré-condição na publicação |
| Duas compras simultâneas **nunca** levam a mesma vaga (transação com `SELECT ... FOR UPDATE`) | Nota na sequência de compra |
| Webhook repetido **nunca** cria inscrição duplicada (código do pagamento único) | Nota na sequência do webhook |
| Token do MP vale 180 dias e é renovado por job antes de vencer, sem nova autorização | Fluxo de renovação |
| CPF nunca aparece completo fora do perfil do dono; no credenciamento aparece só parte | Restrição em DTO de check-in |
| Se o organizador não tem saldo, o MP recusa o reembolso: avisar participante, organizador e Seeds, e registrar para a Seeds acompanhar | Fluxo alternativo do reembolso |

---

## 7. Fluxos críticos (matéria-prima para sequência, atividade e estados)

### 7.1 Compra de ingresso pago (o mais delicado do sistema)

1. Participante clica em **comprar** no React.
2. React chama `POST /api/pedidos` com `{ "tipoIngressoId": 12 }` e o token JWT.
3. API confere login e vaga, **reserva a vaga** (transação com bloqueio) e **calcula a taxa da Seeds**.
4. API cria a **preferência de pagamento** pelo SDK do MP, usando o **token do organizador**, com: `items`, `marketplace_fee`, `payment_methods`, `external_reference` (código do pedido), `notification_url`, `back_urls`, `date_of_expiration`.
5. API responde **201** com `{ pedidoId, situacao: "RESERVADO", linkPagamento }`.
6. React **redireciona** o comprador ao Checkout Pro; ele paga (Pix, cartão ou boleto).
7. MP devolve o comprador à tela de retorno. **Isso não confirma nada**: o comprador pode fechar a janela. A tela mostra "pagamento em análise" até a API confirmar.
8. **MP envia o webhook** à API. A API **valida a assinatura secreta** e responde rápido.
9. API **consulta o pagamento** na API do MP, **ignora avisos repetidos** e atualiza o pedido:
   - **aprovado** → cria a **inscrição ativa**;
   - **pendente** (Pix/boleto) → mantém a reserva até vencer;
   - **recusado ou vencido** → **libera a vaga**.
10. API envia e-mail com resumo, **QR Code** e arquivo **.ics** (meta: menos de 1 minuto).

Evento **gratuito** não passa pelo checkout: a inscrição é criada direto pela API.

### 7.2 Login

- **Google:** clique no React → Google confirma e devolve código à API (`POST /api/auth/google`) → API cria/encontra o perfil no MySQL → gera **JWT** → React guarda e manda em `Authorization: Bearer`. Token vencido = **401** e volta para a tela de entrar.
- **E-mail e senha:** `POST /api/auth/login`, senha com **BCrypt**; recuperação por e-mail com link de validade curta.
- **Primeiro acesso:** nome completo, CPF (validado, sem duplicar) e telefone; gera código do QR pessoal; registra aceite dos Termos (versão e data).

### 7.3 Organizador conecta o Mercado Pago

Botão "Conectar Mercado Pago" → OAuth com **state e PKCE** → na volta a API troca o código (vale 10 minutos) pelos tokens → guarda **criptografados** → job renova antes de vencer.

### 7.4 Cancelamento e reembolso

Participante cancela → API verifica se está dentro de 7 dias → reembolso total pela API do MP, ou aplica a política do evento (total, parcial ou nenhum) → vaga volta → **taxa da Seeds devolvida na mesma proporção [sugestão]**. Falha por falta de saldo do organizador → notifica e registra.

### 7.5 Transferência de ingresso

Até 24h antes → novo titular recebe convite para criar conta → ganha QR próprio → inscrição antiga passa a "transferida" e o QR antigo deixa de valer.

### 7.6 Check-in

Equipe lê o QR pela câmera → `POST /api/checkin` → API mostra **nome, parte do CPF e tipo de ingresso**; avisa se **o QR não tem ingresso nesse evento**, se a **inscrição foi cancelada** ou se a **entrada já foi registrada** → presença atualizada em tempo real (WebSocket ou SSE) em vários celulares → busca manual por nome ou CPF como alternativa. Fase 1 exige internet.

### 7.7 Aprovações da Seeds

Organização nova → aprovada ou recusada (com motivo). Evento beneficente → aprovação antes de publicar. Administrador também pode **bloquear** organizador.

### 7.8 Ciclos de vida (para diagramas de estados)

| Objeto | Estados citados |
|---|---|
| Evento | rascunho → (aprovação, se beneficente) → publicado → cancelado |
| Pedido | reservado → aprovado / pendente / recusado / vencido |
| Reserva | ativa → vencida (liberada pelo job) ou convertida em inscrição |
| Inscrição | ativa → cancelada ou transferida |
| Organização | pendente → aprovada / recusada (+ bloqueada) |
| Fila de espera (fase 2) | aguardando → chamado → inscrito / expirado |

### 7.9 Endpoints iniciais (para rotular mensagens de sequência)

`POST /api/auth/google` · `POST /api/auth/login` · `GET /api/me` · `GET /api/eventos` · `GET /api/eventos/{id}` · `POST /api/organizacoes` · `GET /api/organizacoes/{id}/mercadopago/conectar` · `POST /api/eventos` · `POST /api/eventos/{id}/publicar` · `POST /api/pedidos` · `POST /api/webhooks/mercadopago` · `GET /api/inscricoes` · `POST /api/inscricoes/{id}/cancelar` · `POST /api/inscricoes/{id}/transferir` · `GET /api/eventos/{id}/inscritos` · `POST /api/checkin` · `GET /api/admin/organizacoes/pendentes`

Respostas: 201 cria, 200 consulta, 400 dados inválidos, 401 sem login/token vencido, 403 sem permissão, 404 não existe ou não pertence a quem pediu. Erros no mesmo formato via `@ControllerAdvice`.

---

## 8. Partes a serem feitas (divida entre IAs ou faça em sequência)

### Organização do modelo por fases

No Modelio, o modelo é dividido em pacotes, um por fase, cada um com os próprios diagramas:

```
Rooted
├── 01_Fase1_Venda_e_Checkin      (detalhe completo: RF01 a RF47)
├── 02_Fase2_Crescimento          (RF48 a RF55)
├── 03_Fase3_Comunidade           (RF56 a RF61)
└── 00_Comum                      (atores, enums e entidades base compartilhadas)
```

- **Fase 1:** todas as partes A a I, em detalhe.
- **Fase 2:** casos de uso, classes novas (FilaEspera, Tarefa, Material, Certificado, e o que mais o RF48 a RF55 exigir) e sequência da fila de espera. Estados da fila (aguardando, chamado, inscrito, expirado). Tudo marcado `[FASE 2]`.
- **Fase 3:** apenas casos de uso e, se fizer sentido, componentes (PWA de check-in offline, WhatsApp, chatbot). Tudo marcado `[FASE 3]`.
- **Regra de dependência:** a fase 2 e a fase 3 podem **usar** classes da fase 1 (ex.: FilaEspera referencia TipoIngresso e Perfil), mas a fase 1 **nunca depende** de elementos das fases 2 e 3. Se uma entidade da fase 1 ganha campo na fase 2, descreva isso como extensão marcada, sem alterar o diagrama da fase 1.
- **Entidades compartilhadas** (Perfil, Evento, TipoIngresso, etc.) ficam em um só lugar e são referenciadas, nunca duplicadas entre pacotes.

Se eu pedir uma parte sem dizer a fase, **assuma a fase 1**.

| Parte | Diagrama UML | O que deve conter | Prioridade |
|---|---|---|---|
| **A** | **Casos de uso** | Atores da seção 3. Casos de uso agrupados por módulo (Contas e login; Organizações; Eventos; Vitrine; Compra; Painel do participante; Painel do organizador; Credenciamento; Painel da Seeds; Termos). Base: RF01 a RF47. Usar `include`/`extend` onde fizer sentido (ex.: Comprar ingresso *inclui* Reservar vaga e Pagar; Mercado Pago como ator secundário) | Alta |
| **B** | **Classes (domínio)** | Entidades da seção 5, atributos, multiplicidades, enums, composição/agregação onde fizer sentido. Sem Controller/Service/Repository nesta versão | Alta |
| **C** | **Sequência: compra** | Fluxo 7.1 completo, com participantes: Participante, React, API (Controller/Service/Repository), MySQL, Mercado Pago. Mostrar o webhook como fluxo separado e as notas de concorrência e idempotência. Fragmentos `alt` para aprovado / pendente / recusado | Alta |
| **D** | **Sequência: outros fluxos** | Login com Google (7.2), conexão do Mercado Pago (7.3), cancelamento e reembolso (7.4), check-in (7.6) | Média |
| **E** | **Estados** | Pedido, Inscrição, Evento, Reserva, Organização (7.8) | Média |
| **F** | **Atividade** | Fluxo de compra do ponto de vista do negócio (da escolha do ingresso até o QR no e-mail), com raias: Participante / Sistema / Mercado Pago | Média |
| **G** | **Classes (camadas da API)** | Para o módulo `pedidos` (o mais crítico): PedidoController, PedidoService, PedidoRepository, PedidoDTO, integração com o SDK do MP, job de reservas vencidas. Opcional para os demais módulos | Baixa |
| **H** | **Componentes e implantação** | Seção 3 (tabela de stack): front, API, MySQL, R2/S3, Mercado Pago, Google, Resend, Sentry; ambientes local, prévia e produção | Baixa |
| **I** | **Pacotes** | Os 8 módulos + `config` + `jobs` e as dependências entre eles | Baixa |

Sugestão de ordem: **A → B → C → E → D → F → H/I → G**.

---

## 9. Convenções e formato de entrega

- **Notação:** UML 2.5. **Idioma dos nomes:** português, `PascalCase` para classes (`TipoIngresso`) e `camelCase` para atributos e operações (`vagasRestantes`). *(Se eu pedir inglês, mude.)*
- **Modelio:** eu vou montar os diagramas finais no **Modelio** (tipos disponíveis lá: casos de uso, classes, sequência, atividade, estados, componentes, implantação e pacotes). Por isso, entregue **especificações fáceis de reproduzir manualmente**:
  1. **Código-fonte do diagrama em PlantUML** (bloco de código, um diagrama por bloco);
  2. **Tabela de elementos** (para classes: nome, atributos com tipo, operações, relações com multiplicidade; para sequência: lista numerada de mensagens `origem → destino: mensagem`);
  3. **Lista curta do que construir no Modelio e em que ordem**, incluindo os estereótipos e os pacotes do modelo.
- **Se você tem um recurso de diagrama integrado** (Mermaid, artefato renderizado, canvas, geração de imagem): use-o para me mostrar a **versão visual** e validar a lógica, mas **sempre entregue também o PlantUML e a tabela de elementos**, que é o que eu levo ao Modelio.
- **Se você gerar XMI para importar no Modelio:** avise que é experimental. Eu prefiro montar no Modelio e usar o seu texto como roteiro.
- **Rastreabilidade:** em cada diagrama, cite os **IDs dos requisitos** (RF, RNF, RN) que ele cobre. Ex.: "Caso de uso Reservar vaga: RF24, RNF08".
- **Marcações:** `[SUGESTÃO]` para o que não veio dos documentos; `[FASE 2]`/`[FASE 3]` para o que fica fora da fase 1.
- **Tamanho:** diagramas legíveis. Se passar de ~15 elementos, **quebre em mais de um diagrama** (por módulo) em vez de fazer um monstro.

---

## 10. Ambiguidades e pontos em aberto (pergunte-me ou sinalize)

- **Reembolso:** o texto diz que a *inscrição* pode ter reembolsos, mas a tabela de reembolsos referencia o *pedido*. Qual associação modelar? (Sugestão: Reembolso → Pedido, e a Inscrição alcança via Pedido.)
- **Reserva vs. Pedido:** a Reserva é uma entidade própria ou um estado do Pedido? Os documentos têm as duas coisas (tabela `reservas` e situação `RESERVADO`).
- **Quantidade por compra:** um ingresso por compra é sugestão, não decisão fechada.
- **Papéis:** administrador da Seeds fica em Perfil (papel), enquanto administrador e credenciamento da organização ficam em MembroOrganizacao.
- **Nível da conta (bronze/prata/ouro):** o Perfil já tem o campo, mas a regra completa está na fase 2 e a decisão está em aberto (RN13).
- **Nome do produto, CNPJ e nota fiscal, chargeback e canal de suporte:** decisões da etapa 0 ainda abertas. Não afetam o modelo de classes, mas podem aparecer como nota.
- **Dois pontos que a documentação do Mercado Pago não deixa claros** e serão confirmados nos testes: se `marketplace_fee` é tratado em **reais** (e não porcentagem) e se o MP **devolve a própria tarifa** em reembolso. Se o diagrama depender disso, sinalize.
- **O que os diagramas devem mostrar de segurança** (JWT, perfis, "dado pertence a quem pede")? Sugestão: nota na sequência e restrições no diagrama de classes, sem diagrama próprio.

---

## 11. Instrução inicial (comece por aqui)

1. Confirme em 3 a 5 linhas o que entendeu do projeto.
2. Liste as **perguntas** que precisa que eu responda antes de começar (use a seção 10 como ponto de partida, mas só pergunte o que realmente bloqueia a **parte** que vou te pedir).
3. Depois que eu responder, entregue o diagrama no formato da seção 9.
4. Ao terminar cada diagrama, liste **o que você supôs** e **o que eu devo conferir com o time ou com o Marino**.

**A parte que quero que você faça agora é:** _(preencha: A, B, C, ...)_
