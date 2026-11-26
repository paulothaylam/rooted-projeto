# Contexto UML do Rooted

## Diagramas Implementados

São **21 diagramas** PlantUML em `scripts/`, organizados por tipo em `images/`:
- **Caso de uso** (3): `01`, `02`, `03`
- **Classes** (2): `04` (domínio), `05` (objetos/exemplos)
- **Pacotes** (1): `06`
- **Sequência** (5): `07` a `11`
- **Comunicação** (1): `12`
- **Estado** (4): `13` a `16`
- **Atividade** (5): `17` a `21`

Limite: **apenas estes 8 tipos** solicitados; sem diagramas extras.

---

## Fonte de Verdade

> **O código atual (PHP + MySQL) é a única fonte de verdade.** Diagramas devem espelhar exatamente o que existe em `src/`; não usar specs antigas, funcionalidades planejadas ou suposições.

- **Models** (`src/model/`): 5 classes reais
  - `Database` — conexão PDO estática
  - `Perfil` — tabela `perfis`
  - `Evento` — tabelas `organizacoes` + `eventos`
  - `Compra` — tabelas `tipos_ingresso`, `reservas`, `inscricoes` (god class)
  - `TesteConexao` — teste simples

- **Controller** (`src/controller/HomeController.php`): Front Controller único (`mostrar()` despacha por `?pagina=...`), 20 ações

- **Views** (`src/view/pages/`): 14 páginas PHP puras

- **Banco** (MySQL em Docker): 8 tabelas (`perfis`, `organizacoes`, `eventos`, `tipos_ingresso`, `reservas`, `inscricoes`, `checkins`, `teste_conexao`)

---

## Engenharia Reversa Realizada (2026-11-25)

### Correções Críticas Aplicadas

| Diagrama | Antes (Incorreto) | Depois (Corrigido — Código Real) |
|----------|-------------------|----------------------------------|
| `04-classes-dominio` | 9 classes (incl. `Organizacao`, `TipoIngresso`, `Reserva`, `Inscricao`, `Checkin`) | **5 classes reais**: `Database`, `Perfil`, `Evento`, `Compra`, `TesteConexao` |
| `05-classes-enumeracoes` → `05-objetos-exemplos` | Título "Classes/Enumerações" | **Diagrama de objetos** (dados de exemplo das tabelas) |
| `06-pacotes` | Package `model` com 9 classes | **4 classes de domínio** + nota: `Compra` gerencia 3 tabelas |
| `13-estados-inscricao` | `Inscricao::criar`, `Inscricao::cancelar` | `Compra::reservar(gratuito=true)`, `Compra::cancelarInscricao()` |
| `14-estados-evento` | Transição `PUBLICADO → [*]` por data | Removida (não implementada); padronizado `<<não implementado>>` |
| `15-estados-reserva` | `Reserva::criar` | `Compra::reservar(gratuito=false)` |
| `16-estados-organizacao` | `Organizacao::criarPadrao`, transições `APROVADA→BLOQUEADA/RECUSADA` | Criação direta no `HomeController::criarEvento()`; botões Seeds = placeholders sem transição real |
| `09-sequencia-criar-publicar-evento` | Classes `Organizacao`, `TipoIngresso` separadas | INSERT direto no controller + `Compra::cadastrarTiposPadrao()` |
| `11-sequencia-painel-seeds-gestao` | Classes `Organizacao`, `TipoIngresso` | Queries diretas no controller |
| `12-comunicacao-principal` | 9 participantes | **4 classes reais**: `Perfil`, `Evento`, `Compra`, `Database` |

### Padronização Visual

- **Estados "não implementados"**: seta tracejada cinza (`-[#gray]`) + stereotype `<<não implementado>>` + nota explicativa (diagramas 13–16)
- **Classes inexistentes removidas**: `Organizacao`, `TipoIngresso`, `Reserva`, `Inscricao`, `Checkin` como classes separadas — **não existem no PHP**
- **Check-in**: feito direto no `HomeController` (sem model dedicada); tabela `checkins` criada on-demand

---

## Regras de Modelagem (Obrigatórias)

1. **Nomes, propriedades, métodos, estados, tabelas, fluxos** = exatamente como no código PHP/MySQL
2. **Diagrama de classes**: espelhar membros declarados em PHP (nomes, tipos, visibilidade); **não criar enums ou métodos conceituais**
3. **Placeholders visuais** (botões desabilitados Seeds, abas vendas/taxas/reembolsos) = **não modelar como operações funcionais**
4. **Diagramas de estado**: só transições executadas pelo código; quando PHP não implementa transição esperada → nota `<<não implementado>>`
5. **Diagramas de objetos**: só valores da carga de exemplos no código (`cadastrarExemplos()`); não afirmar que são estado atual do banco
6. **Manter apenas os 8 tipos listados**; imagens geradas a partir dos `.puml` correspondentes

---

## Estrutura de Pastas

```
docs/diagramas/
├── scripts/          # 21 arquivos .puml (fonte)
└── images/
    ├── casos-de-uso/   (01, 02, 03)
    ├── classes/        (04, 05-objetos-exemplos)
    ├── pacotes/        (06)
    ├── sequencia/      (07 a 11)
    ├── comunicacao/    (12)
    ├── estados/        (13 a 16)
    └── atividade/      (17 a 21)
```

---

## Geração de Imagens

```bash
cd docs/diagramas/scripts
plantuml -o ../images *.puml
# Depois mover cada PNG para a subpasta correspondente em ../images/
```

---

## Referências Comportamentais

- **README.md** — execução, escopo, estrutura MVC
- **docs/CONTEXTO-PROJETO.md** — limites, comportamentos, regras detalhadas
- **Este arquivo** — contexto de modelagem UML

---

> **Última sincronização**: 2026-11-25 — Engenharia reversa completa aplicada. Todos os 21 diagramas alinhados ao código PHP real em `src/`.