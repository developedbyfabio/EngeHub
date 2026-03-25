# Mapas de Rede — Padrão de Mesas/Assentos e UI

Documento de referência do módulo **Mapas de Rede** do EngeHub: reconhecimento de mesas no SVG, onde é usado e resumo da UI (modais, admin vs link secreto). Use este arquivo para retomar o trabalho no futuro.

---

## 1. Padrão para reconhecimento de mesa/assento no SVG

### Regex

```regex
/^[A-Z]+\d{2}$/
```

- **`[A-Z]+`** — uma ou mais letras **maiúsculas**
- **`\d{2}`** — exatamente **dois dígitos**
- O texto do nó deve corresponder **somente** a isso (após `trim`), sem espaços ou outros caracteres.

### Exemplos

| Válidos (são mesa) | Inválidos (não são mesa) |
|--------------------|--------------------------|
| A01, B02, D01      | a01 (minúscula)          |
| RH04, ADM01, AB12  | A1 (só um dígito)        |
| V17                 | A001 (três dígitos)      |
|                     | 01A (número antes)       |
|                     | A01-01 (contém hífen)    |

---

## 2. Onde o padrão é usado

### 2.1 Backend (PHP) — varredura do SVG

- **Arquivo:** `app/Models/NetworkMap.php`
- **Método:** lógica de varredura (ex.: `syncSeatsFromSvg()` ou equivalente).
- **Comportamento:**
  - Considera apenas nós **`<text>`** e **`<tspan>`** no SVG (via DOM/XPath).
  - Para cada nó: `trim($node->textContent)` e `preg_match($regex, $text)`.
  - Se bater, o código é tratado como mesa (cria/atualiza registro de Seat no banco).
- **Quando roda:** ao cadastrar/atualizar o mapa e ao clicar em **“Revarrear mesas”** na tela de Ver mapa.

### 2.2 Frontend (JavaScript) — admin e link secreto

- **Arquivos:**  
  - `resources/views/admin/network-maps/show.blade.php`  
  - `resources/views/secret-url/home.blade.php`
- **Comportamento:**
  - Mesmo regex aplicado ao `textContent` (após trim) de:
    - elementos **`<text>`** e **`<tspan>`** que sejam **folha** (`children.length === 0`);
    - elementos **folha** dentro de **`<foreignObject>`** (SVGs exportados pelo draw.io).
  - Se o texto bater e o elemento for folha: atributos `data-seat`, `data-original-text` e classe `seat`; elemento fica clicável e pode trocar rótulo (código vs nome).

---

## 3. Resumo rápido

- **Padrão:** uma ou mais letras maiúsculas + exatamente dois dígitos → **`/^[A-Z]+\d{2}$/`**.
- **No SVG:** textos em **`<text>`**, **`<tspan>`** ou em elementos folha dentro de **`<foreignObject>`**.
- Para ser tratado como mesa, o texto do nó (após trim) deve ser **exatamente** nesse formato (ex.: A01, RH04, ADM01).

---

## 4. UI — Admin “Ver mapa” vs Link secreto “Mapa de Rede”

### 4.1 Admin (`admin/network-maps/{id}` — Ver mapa)

- **Controles:** um **box separado** (acima do mapa) com:
  - **Rótulos no mapa:** botões “Códigos” e “Nomes”.
  - **Zoom:** − / 100% / + / Reset.
- **Mapa:** outro **box separado** (altura fixa, ex.: 70vh), só a área do SVG (pan/zoom).
- **Ao clicar na mesa:**
  1. Abre **modal de visualização** (só leitura) com dados da mesa e botão **“Editar”**.
  2. “Editar” abre o **modal de edição** (formulário: setor, observações, colaborador nome, computador, 2 pontos de rede).
- **Fechar modal de visualização:** clique no X, no botão “Fechar” ou fora do modal (overlay). A função `closeSeatViewModal` está exposta em `window` para os `onclick` do HTML funcionarem (o script roda dentro de uma IIFE).

### 4.2 Link secreto (`/s/{slug}` — aba “Mapa de Rede”)

- **Controles:** box separado com **Legenda** (Ocupado/Disponível) e **Zoom** (− / % / + / Reset).
- **Mapa:** box separado com a área do SVG (pan/zoom).
- **Ao clicar na mesa:** abre **modal somente leitura** (sem botão Editar). Dados vêm da API pública (ex.: `/api/seats/{code}`).
- **Modal:** título e botão X em **preto** sobre fundo claro (`text-gray-900`, header `bg-amber-50`). Conteúdo centralizado, `max-width: 28rem`, overlay com blur/opacidade.

### 4.3 Modais — boas práticas já aplicadas

- **Tamanho:** modal de visualização com `max-width: 28rem`; modal de edição com `max-width: 42rem`; centralizados (flex + `mx-auto`).
- **Overlay:** fundo com `bg-gray-900/60` e `backdrop-blur-sm` (ou equivalente) para focar no modal.
- **Altura:** `max-height: 85vh` e scroll interno quando necessário.
- **Página secreta:** ao abrir o modal usar `modal.style.display = 'flex'`; ao fechar `display = 'none'` para a centralização funcionar.

---

## 5. Arquivos principais

| Função                    | Arquivo |
|---------------------------|--------|
| Varredura SVG (backend)   | `app/Models/NetworkMap.php` |
| Admin — Ver mapa, modais  | `resources/views/admin/network-maps/show.blade.php` |
| Link secreto — mapa/modal | `resources/views/secret-url/home.blade.php` |
| API mesa (modal secreto)  | `app/Http/Controllers/Api/SeatApiController.php` |
| Rotas admin (get/update seat, resync) | `routes/web.php` (grupo admin) |

---

## 6. Possíveis evoluções (para amanhã ou depois)

- Ajustar ou estender o regex (ex.: permitir mais dígitos ou prefixos) em **um único lugar** e refletir no PHP e no JS.
- Garantir que a varredura no backend também considere conteúdo dentro de `foreignObject` se necessário.
- Testes E2E: fluxo admin (Ver mapa → clicar mesa → ver → Editar → salvar → nome no mapa) e link secreto (abrir mapa → clicar mesa → modal só leitura).

---

*Última atualização: contexto da sessão de desenvolvimento EngeHub — uso para retomar trabalho no dia seguinte.*
