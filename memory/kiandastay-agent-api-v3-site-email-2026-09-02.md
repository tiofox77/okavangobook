# KiandaStay Agent API — SEO/contactos, email SMTP e mapa verificado — 2026-09-02

Snapshot das capacidades adicionadas desde [[kiandastay-agent-api-v2-locations-2026-09-01]].
Documentação canónica: [`docs/AGENT_API.md`](../docs/AGENT_API.md).

## Definições do site — SEO e contactos (`site:read` / `site:write`)

```http
GET   /api/agent/v1/site/settings     site:read
PATCH /api/agent/v1/site/settings     site:write (+ agent.write)
```

Corpo: `{"settings": {chave: valor, …}, "dry_run": false}`. Chaves permitidas:

| Grupo | Chaves |
|---|---|
| Identidade e SEO | `app_name`, `app_description`, `app_keywords`, `meta_description`, `meta_keywords`, `app_currency` |
| Contactos | `contact_email`, `contact_phone`, `contact_address` |
| Redes sociais | `social_facebook`, `social_instagram`, `social_twitter`, `social_youtube` |
| Idioma / fuso | `default_language`, `app_language`, `app_timezone` |

- Validação por chave: `contact_email` tem de ser email válido; `social_*` exige
  endereço completo (`https://…`); limites de comprimento — `app_name` 120,
  `app_description` 500, `meta_description` 320, `app_keywords`/`meta_keywords` 500,
  `contact_email` 190, `contact_phone` 60, `contact_address` 300.
- Chaves fora da lista são silenciosamente ignoradas; se **nenhuma** for permitida → 422.
- **Nunca acessíveis por esta via** (decisão de segurança): `api_key`, `bank_*`,
  `maintenance_mode`, `debug_mode`.
- Auditoria: `site.settings.updated` com antes/depois.

## Email pelo SMTP do site (`email:send`) — módulo novo

```http
GET  /api/agent/v1/site/email/config    email:send
POST /api/agent/v1/site/email           email:send (+ agent.write)
```

- `config` devolve mailer, host, porta, encriptação, se username/password estão
  definidos, o remetente (`from`), o `contact_email` do site e os limites.
  **Nunca devolve a palavra-passe.**
- `send` aceita: `to` (string com vírgulas ou lista), `subject` (≤200),
  `body` (≤20000), `html` (bool), `reply_to`, `cc` (lista), `dry_run`.
- Salvaguardas anti-abuso: **5 destinatários por pedido** (não é motor de campanhas),
  **30 envios/hora por token** (`RateLimiter`, devolve 429 com `retry_after_seconds`),
  sem anexos.
- Auditoria: `email.sent` (inclui `dry_run`) e `email.failed` (com o erro do SMTP,
  resposta 502). O `X-Reason` do pedido fica registado.
- A configuração SMTP vem do `.env` da aplicação (não é editável pela API).

## Mapa das rotas — verificado 46/46 (2026-09-02)

40 rotas sob `/api/agent/v1`, todas exercidas com token de acesso total:

| Módulo | Rotas | Notas da verificação |
|---|---|---|
| Identidade | `GET /me` | devolve escopos efetivos |
| Site | `GET /site/status`, `GET+PATCH /site/settings` | `dry_run` e validação 422 confirmados |
| Email | `GET /site/email/config`, `POST /site/email` | limites 5/pedido e 30/hora confirmados |
| Páginas | `GET /pages`, `GET/POST/PATCH /pages[/{slug}]`, `preview`, `publish`, `archive` | `publish` exige o `preview_token` devolvido pelo `preview` (409/422 sem ele) |
| Propriedades | `GET/POST /properties`, `GET/PATCH/DELETE /properties/{id}` | criação devolve 201 |
| Tipos de quarto | `GET/POST`, `PATCH/DELETE /{roomTypeId}`, `POST reorder` | DELETE exige `X-Confirm-Critical` (409) |
| Media de propriedades | `GET/POST`, `PATCH/DELETE /{mediaId}`, `POST reorder` | DELETE exige `X-Confirm-Critical` (409) |
| Destinos | `GET/POST /locations`, `GET/PATCH/DELETE /locations/{id\|slug}` | PATCH altera a `province` |
| Galeria de destinos | `GET/POST /locations/{id\|slug}/media`, `DELETE .../{mediaId}` | imagem e vídeo (YouTube) aceites |
| Media global | `POST /media` | 422 sem ficheiro |
| Logs | `GET /logs/agent` | auditoria acessível |

Contratos transversais confirmados no mesmo teste:

- **Sem escopo → 403** (`{"message":"Escopo insuficiente.","required_scope":"…"}`).
- **Sem `X-Reason`/`Idempotency-Key` em escrita → 422**.
- **Operações críticas → 409** até enviar `X-Confirm-Critical: true` (DELETE de
  destinos, tipos de quarto e media; publicação de páginas via preview_token).
- **Rate limit global**: `throttle:agent-api` = 120 pedidos/minuto por token (429).
- Token sem escopo relevante bloqueia mesmo que a rota exista — os 403 observados
  em produção resultaram de escopos em falta, não de rotas inexistentes.

## Erros comuns já diagnosticados (para não repetir)

- Caminhos de galeria **inventados** (`/locations/{id}/gallery`, `/video`, `/images`)
  devolvem 404. O correto é **`/locations/{id|slug}/media`** com `type: image|video`.
- 403 no `PATCH /locations/{slug}` com `GET` a funcionar = falta `locations:write`
  (marcar o **grupo** `locations` no editor de escopos, não só o `:read`).
- Erro de TLS do lado do agente: o servidor está correto (Let's Encrypt válido,
  cadeia completa de 3 certificados, TLS 1.2/1.3, `Verify return code: 0`).
  Investigar CA bundle/proxy no ambiente do agente.
