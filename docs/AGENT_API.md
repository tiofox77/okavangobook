# KiandaStay Agent API v1

Base de produção sugerida:

```text
https://kiandastay.vip/api/agent/v1
```

Esta API é separada da API pública `/api/v1`. Foi desenhada para um agente gerir conteúdo e propriedades sem acesso a shell, SQL ou Artisan arbitrário.

## Segurança

- `Authorization: Bearer kstay__...`
- Token guardado apenas como SHA-256, com validade configurável até 365 dias; 30–90 dias continua recomendado.
- Escopos explícitos e lista opcional de IPs autorizados.
- Toda escrita exige `X-Reason` e `Idempotency-Key`.
- Repetir a mesma chave e payload devolve a primeira resposta com `Idempotency-Replayed: true`.
- Reutilizar a chave com outro payload devolve `409`.
- Publicar uma página exige primeiro `/preview`; o token de preview expira em 30 minutos e é de uso único.
- Publicar uma propriedade exige `properties:publish` e `X-Confirm-Critical: true`; use antes `dry_run: true`.
- A auditoria regista actor, rota, IP, motivo, idempotência, antes/depois, status e data.
- Não existem endpoints para shell, SQL, Artisan livre ou instalação arbitrária.

## Emitir token

```bash
php artisan agent-token:create content-agent \
  --scope=site:read \
  --scope=pages:read \
  --scope=pages:write \
  --scope=properties:read \
  --scope=logs:read \
  --ip=203.0.113.10 \
  --days=30
```

O valor simples só é mostrado uma vez. Para revogar, preencha `revoked_at` no registo de `agent_tokens` através do painel administrativo (UI prevista) ou de manutenção controlada.

Comando operacional compatível com integrações:

```bash
php artisan agent:token "OpenClaw KiandaStay" --scopes='*' --ip=203.0.113.10 --days=365
```

## Cabeçalhos

Leitura:

```http
Authorization: Bearer kstay__...
Accept: application/json
```

Escrita:

```http
Authorization: Bearer kstay__...
Accept: application/json
Content-Type: application/json
X-Reason: Atualizar hero conforme campanha de Agosto
Idempotency-Key: page-home-hero-20260825-001
```

## Rotas implementadas

### Identidade e site

```http
GET   /me
GET   /site/status
GET   /site/settings
PATCH /site/settings
```

`/me` devolve escopos, expiração, IPs, tipos de blocos React e matriz de capacidades (`available`/`planned`).

Exemplo de dry-run das configurações:

```json
{
  "settings": {
    "app_description": "Acomodações verificadas em Angola",
    "default_language": "en"
  },
  "dry_run": true
}
```

As configurações graváveis são uma allowlist; segredos nunca são devolvidos nem alteráveis por esta rota.

### Páginas e blocos React

```http
GET   /pages
POST  /pages
GET   /pages/{slug}
PATCH /pages/{slug}
POST  /pages/{slug}/preview
POST  /pages/{slug}/publish
POST  /pages/{slug}/archive
```

Contrato de página:

```json
{
  "title": "Mussulo",
  "slug": "mussulo",
  "blocks": [
    {
      "type": "hero",
      "props": {
        "headline": "Descubra o Mussulo",
        "image": "/storage/agent/mussulo.webp"
      }
    },
    {
      "type": "property_grid",
      "props": {
        "filters": { "province": "Luanda" },
        "limit": 6
      }
    }
  ],
  "seo": {
    "meta_title": "Mussulo: hotéis e resorts",
    "description": "Alojamentos verificados no Mussulo.",
    "canonical": "https://kiandastay.vip/mussulo",
    "open_graph": {},
    "schema": {}
  }
}
```

Tipos permitidos: `hero`, `gallery`, `cta`, `faq`, `property_grid`, `location`, `rich_text`.

Fluxo de publicação:

1. `PATCH` com `dry_run: true` para comparar o resultado.
2. `PATCH` real com nova `Idempotency-Key`.
3. `POST /preview` para obter `preview_token`.
4. `POST /publish` com `{ "preview_token": "..." }` em até 30 minutos.

### Propriedades

```http
GET   /properties
GET   /properties/{id}
PATCH /properties/{id}
```

Aceita dados editoriais, regras, fotos, coordenadas, contactos e estado. Ativar uma propriedade requer `properties:publish`, dry-run prévio e `X-Confirm-Critical: true`.

`images` aceita exclusivamente um array de URLs absolutas HTTP/HTTPS:

```json
{
  "images": [
    "https://kiandastay.vip/uploads/agent/properties/1/exterior.jpg",
    "https://www.example.com/hotel/quarto.jpg"
  ]
}
```

Objetos, IDs e caminhos relativos são rejeitados com HTTP 422 e `errors.images.N`. Para media gerida e ordenável, use as rotas próprias abaixo.

O campo `room_types` não é aceite no `PATCH /properties/{id}`. Em vez de o ignorar, a API devolve HTTP 422 e indica as rotas próprias.

### Tipos de quarto

```http
GET    /properties/{id}/room-types
POST   /properties/{id}/room-types
PATCH  /properties/{id}/room-types/{room_type_id}
DELETE /properties/{id}/room-types/{room_type_id}
POST   /properties/{id}/room-types/reorder
```

Exemplo de criação:

```json
{
  "name": "Premier Plus Suite T1",
  "description": "Suite com sala e quarto separados.",
  "adult_capacity": 2,
  "children_capacity": 2,
  "beds": 1,
  "bed_type": "King",
  "size": 71,
  "base_price": 180000,
  "rooms_count": 16,
  "amenities": ["Wi-Fi", "Ar condicionado"],
  "images": ["https://example.com/suite.jpg"],
  "source_url": "https://www.sanahotels.com/en/hotel/epic-sana-luanda/"
}
```

`capacity` é a lotação total e é calculada automaticamente quando `adult_capacity` é informado. `size` usa metros quadrados e a resposta declara `size_unit: "m2"`. `base_price` usa AKZ e exige o escopo `pricing:write`; os restantes campos de escrita exigem `properties:write`.

Todos os endpoints de escrita suportam `dry_run`. A remoção real exige `X-Confirm-Critical: true` e é bloqueada se houver preços, quartos individuais ou reservas associados. Para reordenar, envie todos e apenas os IDs:

```json
{ "room_type_ids": [7, 3, 9] }
```

### Media

```http
POST /media
```

Multipart com `file`, `alt_text`, `title` e `folder`. Formatos: JPG, PNG, WebP, GIF, SVG e PDF; máximo 10 MB. A resposta inclui URL, MIME, dimensões e tamanho.

Novos uploads ficam em `/uploads/agent/...`, diretamente acessível pelo servidor web, sem depender de `storage:link`.

### Galeria da propriedade

```http
GET    /properties/{id}/media
POST   /properties/{id}/media
PATCH  /properties/{id}/media/{media_id}
DELETE /properties/{id}/media/{media_id}
POST   /properties/{id}/media/reorder
```

O `POST` aceita exatamente uma origem:

- `file`: upload multipart direto;
- `media_id`: associa um upload feito anteriormente em `/media`;
- `url`: regista uma imagem externa HTTP/HTTPS.

Também aceita `alt_text`, `title`, `position` e `is_cover`. Definir `is_cover: true` atualiza `hotel.thumbnail` e remove a marca de capa das restantes imagens. Alterar e reordenar suporta `dry_run`. Remover exige primeiro `dry_run: true` e depois `X-Confirm-Critical: true`.

### Auditoria

```http
GET /logs/agent?actor=&subject_type=&subject_id=&from=&to=&per_page=50
```

Cada registo inclui o evento (`property.room_type.created`, `updated`, `deleted` ou `reordered`), actor/token, rota, IP, motivo, chave de idempotência, antes/depois, estado HTTP, dry-run e data/hora.

## Cliente React/TypeScript

```ts
const AGENT_API = "https://kiandastay.vip/api/agent/v1";

export async function agentRequest<T>(
  path: string,
  options: RequestInit & { reason?: string; idempotencyKey?: string } = {},
): Promise<T> {
  const headers = new Headers(options.headers);
  headers.set("Accept", "application/json");
  headers.set("Authorization", `Bearer ${process.env.KSTAY_AGENT_TOKEN}`);

  if (options.body) {
    headers.set("Content-Type", "application/json");
    headers.set("X-Reason", options.reason ?? "Atualização autorizada pelo agente");
    headers.set("Idempotency-Key", options.idempotencyKey ?? crypto.randomUUID());
  }

  const response = await fetch(`${AGENT_API}${path}`, { ...options, headers });
  const payload = await response.json();
  if (!response.ok) throw new Error(payload.message ?? `HTTP ${response.status}`);
  return payload as T;
}
```

O token nunca deve ir para o bundle do browser. Use o cliente num servidor React/Next.js, route handler ou backend-for-frontend.

## Próximos módulos (contrato reservado)

Serão implementados sem alterar o prefixo ou o contrato de segurança:

- Menus: `/menus/{handle}`.
- Preços: `/properties/{id}/pricing`, regras por data e descontos.
- Disponibilidade: `/properties/{id}/availability`, bloqueios e manutenção.
- Reservas: `/bookings`, confirmação, cancelamento e notas internas.
- Leads e hóspedes: `/leads`, `/guests` e conversão para reserva.
- Promoções: `/promotions`, `/coupons` e destaques.
- Pagamentos: comprovativos, confirmação/rejeição e leitura de estado.
- Comunicações: templates, preview e envio por handle (email/SMS/WhatsApp).
- Analytics e saúde de conteúdo.
- Sistema/deploy: apenas ações enumeradas e reversíveis; nunca shell livre.
