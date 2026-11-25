# Contexto UML do Rooted

## Diagramas Sugeridos
- Caso de uso (descricao do diagrama de caso de uso em docx)
- Classe
- Objeto
- Pacote
- Sequência
- Comunicação
- Estado
- Atividade

Somente esses diagramas são sujeridos para que façam no projeto, usando o puml na pasta [scripts](./scripts/); e a pasta das imagens ja compiladas em [images](./images/) com uma subpasta respectivamente onde fica cada tipo de diagrama para uma organização mais polida

## Fonte de verdade

Este projeto acadêmico está encerrado para novas funcionalidades. Os diagramas devem
reproduzir o PHP, as views e o esquema MySQL atuais; não devem usar especificações
antigas, funcionalidades planejadas ou suposições como se fossem implementadas.
Para executar o sistema, consulte o [README](../../README.md); para o resumo do
comportamento atual, consulte [CONTEXTO-PROJETO.md](../CONTEXTO-PROJETO.md).

## Escopo técnico

- Aplicação PHP com `HomeController`, modelos em `src/model`, views em `src/view`,
  sessão PHP e conexão PDO.
- MySQL executado em Docker, com PHP-FPM e Nginx.
- `HomeController` direciona requisições através de `?pagina=...`.
- Tabelas do sistema: `perfis`, `organizacoes`, `eventos`, `tipos_ingresso`, `reservas`,
  `inscricoes`, `checkins` e `teste_conexao`.
- Cadastro e autenticação são locais, por e-mail e senha; senhas são armazenadas com
  bcrypt. No cadastro, escolhe-se participante ou organizador; a opção define o painel
  inicial, e contas comuns podem acessar as duas áreas.
- O papel `admin` é reservado para a área Seeds; não pode ser escolhido no cadastro e
  suas rotas são bloqueadas para contas comuns.

## Limites do produto

Não há integração externa de domínio nem de autenticação. O escopo não contém
processamento de pagamentos, login social, envio de e-mail, armazenamento externo ou
chamadas a APIs de terceiros. Eventos pagos não podem ser publicados; uma reserva paga
não executa cobrança. O Bootstrap 5 carregado via CDN é apenas um recurso visual da
interface.

Não modelar pagamentos, reembolsos, transferência de inscrição, QR Code, envio de
notificações, aprovação efetiva de organizações/eventos ou leitura de QR. Elementos
visuais desabilitados ou placeholders não são casos de uso implementados.

## Comportamentos existentes relevantes

- O público consulta a vitrine, busca e filtra eventos, e vê detalhes.
- O usuário cria conta e entra com e-mail e senha; a sessão permite acessar as páginas
  pós-login e pode ser encerrada pelo logout.
- Evento gratuito gera inscrição diretamente. Evento pago gera reserva com vencimento
  armazenado em `vence_em`, sem cobrança.
- O prazo é de 15 minutos, mas não há rotina que expire reservas ou libere vagas por
  vencimento.
- O organizador cria evento em `RASCUNHO`, edita dados e vagas, e pode excluir evento
  somente quando não há inscrições. Evento gratuito pode ser publicado; evento
  beneficente muda para `EM_APROVACAO`; evento pago não pode ser publicado.
- O painel da Seeds exibe organizações e eventos beneficentes. Ações de aprovar,
  recusar ou bloquear aparecem desabilitadas e não alteram dados.
- A inscrição tem situações `ATIVA` e `CANCELADA`. O participante pode cancelar sua
  própria inscrição e a vaga é devolvida.
- O credenciamento procura inscrição por nome ou CPF e cria um registro em `checkins`.
  Check-in não é uma situação da inscrição.
- As áreas participantes e organizador estão disponíveis para contas comuns; o cadastro
  escolhe apenas o painel inicial. Admins também podem acessar essas áreas.
- A área Seeds é exclusiva da conta com papel `admin`; links são ocultados para contas
  comuns e o acesso direto às rotas também é bloqueado.
- Os painéis pós-login apresentam uma barra horizontal acima da sidebar para as áreas
  permitidas para a conta.
- As áreas de vendas/taxas e reembolsos são placeholders, sem operações ou dados reais.

## Regras para alterações

1. Usar nomes, propriedades, métodos, estados, tabelas e fluxos existentes no código.
2. No diagrama de classes, espelhar os membros declarados em PHP, inclusive nomes e
   tipos, sem criar enums ou métodos conceituais.
3. Não representar placeholders como operações funcionais.
4. Em diagramas de estado, mostrar somente transições executadas pelo código; quando o
   PHP não implementar uma transição esperada, registrar essa limitação em nota.
5. Diagramas de objetos devem usar apenas valores da carga de exemplos no código, sem
   afirmar que são o estado atual do banco.
6. Manter apenas os oito tipos listados acima; imagens devem ser geradas a partir dos
   scripts PlantUML correspondentes.
