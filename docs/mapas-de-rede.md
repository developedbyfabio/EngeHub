# Mapas de rede (SVG) — como funciona

Este documento descreve o fluxo técnico dos **mapas de rede** no EngeHub: upload do SVG, detecção automática de **dispositivos** (mesas, impressoras, TVs, etc.), sincronização com o banco, marcação clicável no navegador e **modais por tipo**.

## Visão geral do fluxo

1. Um administrador cadastra um **mapa** (nome + arquivo **SVG**). O arquivo é salvo em `public/media/`; o registro fica na tabela `network_maps`.
2. O PHP **varre o XML do SVG** e cria ou atualiza registros na tabela **`devices`** para cada rótulo que corresponder ao padrão obrigatório.
3. Na visualização, o conteúdo do SVG é **embutido no HTML** (`{!! $svgContent !!}`), não servido apenas como `<img>`, para permitir alterar textos e anexar eventos de clique.
4. O **JavaScript** percorre o DOM do SVG, marca elementos válidos (`data-type`, `data-code`, classe `device`), e no clique abre o **modal correspondente ao tipo** e carrega dados via JSON.

Existem **duas camadas** de detecção com a **mesma regra de código**:

| Camada | Onde | Função |
|--------|------|--------|
| Servidor (PHP) | `NetworkMap::syncDevicesFromSvg()` | Persiste `devices` (por `network_map_id` + `type` + `code`). |
| Cliente (JS) | `network-map-devices-script.blade.php` | Marca o que é clicável no SVG e alterna Códigos/Nomes para `SEAT`. |

## Padrão obrigatório no SVG (TIPO-SETOR-NÚMERO)

**Regex (PHP e JavaScript):**

```regex
^(SEAT|PRINTER|TV|SCAN|PHONE|AP)-[A-Z0-9\-]+$
```

- **Tipo:** uma das palavras `SEAT`, `PRINTER`, `TV`, `SCAN`, `PHONE`, `AP`.
- **Resto:** após o primeiro `-`, segmento composto por **maiúsculas, dígitos e hífens** (ex.: setor e número: `ENGE-01`, `RH-01`).
- A string inteira é o **`full_code`** (ex.: `SEAT-ENGE-01`). O **`code`** persistido é tudo após o primeiro `-`: `ENGE-01`.

**Exemplos válidos:** `SEAT-ENGE-01`, `PRINTER-RH-01`, `TV-SALA-01`, `AP-ANDAR1-01`.  
**Exemplos inválidos:** `seat-enge-01` (minúsculas), `MES-01` (tipo não suportado), `SEAT_ENGE_01` (underscore).

## Pontos de tomada / rede no SVG (`OUTLET`)

Rótulo **curto** (sem prefixo `TIPO-`), tratado à parte:

```regex
^[A-Z]\d{2}$
```

- **Uma** letra maiúscula + **dois** dígitos: `A01`, `B02`, `C15`.
- No banco: `type = OUTLET`, `code = A01`, `full_code = OUTLET-A01`.
- **Ordem:** se o texto casar com o padrão `TIPO-…`, vale como dispositivo “completo”; **só** se não casar é que o texto é testado como ponto `OUTLET`.
- **Metadata:** `outlet_type` — `network` (Rede) ou `phone` (Telefone), configurável no modal de edição (admin).

### Referências no código

- Servidor: `app/Models/Device.php` — `TYPE_REGEX`, `OUTLET_CODE_REGEX`, `parseFullCode()`, `parseSvgLabel()`.
- Servidor: `app/Models/NetworkMap.php` — `syncDevicesFromSvg()`.
- Cliente: `resources/views/admin/network-maps/partials/network-map-devices-script.blade.php` — `DEVICE_REGEX`, `OUTLET_REGEX`, `markDeviceLeaf()`.

## Varredura no servidor (`syncDevicesFromSvg`)

Implementação: `app/Models/NetworkMap.php`.

1. Lê o SVG com `DOMDocument`.
2. Considera nós `text`, `tspan` e **folhas** dentro de cada `foreignObject` (conteúdo exportado por editores como HTML).
3. Para cada texto, `trim` e validação com `Device::parseSvgLabel()` (padrão principal ou `OUTLET`).
4. Monta a lista de dispositivos encontrados no SVG e, para cada um, usa **`firstOrCreate`** pela chave `network_map_id` + `type` + `code`: se o registro **já existe**, nada é alterado (`setor`, `observacoes`, `metadata` preservados); se **não existe**, cria só com `full_code`. Dispositivos que sumiram do SVG **não são apagados** do banco.

**Quando a varredura roda:**

- Ao **criar** um mapa (`NetworkMapController::store`).
- Ao **atualizar** o mapa (`NetworkMapController::update`), inclusive após trocar o SVG.
- Manualmente pelo botão **“Revarrear dispositivos”** (`NetworkMapController::resyncDevices`).

## Marcação clicável no navegador

Implementação: `resources/views/admin/network-maps/partials/network-map-devices-script.blade.php`.

Além do regex, o JS exige que o elemento seja **folha** (`el.children.length === 0`), para não marcar `<text>` que só agrupa `<tspan>`.

**Atributos por dispositivo:**

- `data-type` — ex.: `SEAT`
- `data-code` — ex.: `ENGE-01` (trecho após o primeiro `-`)
- `data-device-full` — ex.: `SEAT-ENGE-01`
- `data-original-text` — texto original do SVG (para alternar com nomes)
- Classe `device`, `cursor: pointer`, `pointer-events: auto`

A inicialização pode rodar **duas vezes** (incluindo `setTimeout` curto) para SVG que ainda estabiliza no DOM.

## Clique → painel lateral por tipo → API

- Clique com captura: `e.target.closest('[data-code].device')` dentro de `#mapaContainer`.
- `openDevicePanel(type, code)` faz `switch`, carrega o mesmo JSON da API e preenche **`#deviceSidePanel`** (painel fixo à direita do mapa, sem overlay). **Editar** abre o modal de edição existente (admin).

**Endpoints JSON (mesmo formato `device` com `details` e `metadata`):**

| Contexto | GET |
|----------|-----|
| Admin (mapa específico) | `GET /admin/network-maps/{id}/devices/{type}/{code}` |
| Filiais (mapa ativo selecionado) | `GET /filiais/network-maps/{id}/devices/{type}/{code}` |
| URL secreta / integração | `GET /api/map-devices/{type}/{code}` (usa o **primeiro mapa ativo**) |

**Atualização (somente admin com permissão):**  
`PUT /admin/network-maps/{id}/devices/{type}/{code}` — corpo com `setor`, `observacoes`, `metadata` (campos dependem do `type`).

Painel de visualização: `resources/views/admin/network-maps/partials/device-side-panel.blade.php`. Modais de **edição** apenas: `device-modals.blade.php` (quando `$canEditDevicesEffective`).

## Códigos versus nomes no mapa (SEAT)

O backend envia `deviceLabels`: mapa **`full_code` → nome do colaborador** (campo em `metadata`, ex.: `collaborator_name`).

- **Códigos:** mostra `data-original-text` / `full_code`.
- **Nomes:** para elementos `SEAT`, substitui o texto visível pelo nome quando existir em `deviceLabels`.

O clique continua usando `data-type` + `data-code`; o texto exibido é só visual.

## Busca de colaboradores (SEAT)

Na barra do mapa (admin, Filiais e URL secreta): campo **Buscar colaborador**, com debounce e **Enter** para disparar.

- Considera apenas `.device[data-type="SEAT"]`.
- Correspondência **parcial** e **case insensitive** sobre o **nome** do colaborador: prioridade `deviceLabels[full_code]` (metadata); se vazio, em modo **Nomes** usa o texto do elemento apenas quando difere do código original (não pesquisa pelo código da mesa).
- Vários resultados: indicador `Nome (1/N)` e botões **anterior** / **próximo**.
- Ao focar um resultado: zoom **125%**, centralização no viewport (transform do `#svgWrapper`) e destaque visual (`device-search-highlight`). Não se usa `scrollIntoView` no elemento (evita rolar a página/modal e esconder a barra de controles).
- Nova pesquisa ou campo vazio: limpa destaque e estados; sem match: mensagem **Nenhum resultado encontrado**.

## Pan e zoom

- O wrapper `#svgWrapper` usa `transform: translate(...) scale(...)`.
- O arrastar **não** inicia em `mousedown` sobre `[data-code].device`, para não conflitar com o clique.

## Tabelas e modelos principais

- `network_maps`: nome, arquivo, caminho, `is_active`.
- `devices`: `network_map_id`, `type`, `code`, `full_code`, `setor`, `observacoes`, `metadata` (JSON), timestamps. Índice único lógico: mapa + tipo + código.

Modelos: `App\Models\NetworkMap`, `App\Models\Device`.  
O modelo `Seat` e a tabela `seats` podem existir por legado; o fluxo atual de mapa é **`devices`**.

## Dicas para quem produz o SVG

1. Use rótulos no formato **`TIPO-SETOR-NUMERO`** em `text`/`tspan` (ou em folhas dentro de `foreignObject`).
2. Um tipo por rótulo; o código após o primeiro `-` pode conter hífens (`ENGE-01`).
3. Depois de alterar o desenho, use **Revarrear dispositivos** no admin para incluir códigos novos no banco.
4. Códigos **removidos** só do SVG **não** apagam automaticamente linhas em `devices`.

## API auxiliar

- `GET /api/map-devices/seats/occupied` — lista `full_code` de `SEAT` com colaborador preenchido no mapa ativo.

---

*Documento alinhado ao comportamento do código no repositório EngeHub. Em caso de divergência, prevalece o código-fonte.*
