# KiandaStay Agent API — ficha interna

Implementação canónica e exemplos: [`docs/AGENT_API.md`](../docs/AGENT_API.md).

- Base: `https://kiandastay.vip/api/agent/v1`
- Bearer: `kstay__...`, hash em repouso, expiração máxima 90 dias, allowlist de IP.
- Escrita: `X-Reason` + `Idempotency-Key` obrigatórios.
- Críticos: dry-run/preview e confirmação explícita (`X-Confirm-Critical: true`).
- Auditoria: actor, rota, IP, motivo, antes/depois, status e data.
- Sem shell, SQL ou Artisan arbitrário.

## Implementado

- Identidade, site, páginas/blocos/SEO, media e logs.
- **Propriedades — CRUD completo**: `GET/POST /properties`, `GET/PATCH/DELETE /properties/{id}`.
  Criação entra como rascunho; publicar exige `properties:publish` + confirmação; DELETE exige
  `properties:delete` + confirmação e é bloqueado (409) se houver reservas.
  `property_type`: `hotel|resort|hospedaria|residencial|apartment|house`.
- **Destinos (Locations) — CRUD completo**: `GET/POST /locations`, `GET/PATCH/DELETE /locations/{id|slug}`.
  Tudo o que o admin faz: `description`, `image` (URL http(s) ou caminho de storage),
  `name`, `capital`, `population`, `latitude/longitude`, `is_featured`, `is_active`,
  `slug` e **a própria `province`** (validada contra os 18 slugs). Alimenta as páginas
  públicas `/destino/{slug}`. DELETE bloqueado (409) com hotéis associados.
  Escopos: `locations:read` / `locations:write` / `locations:delete`.
- **Galeria dos destinos**: `GET/POST /locations/{id|slug}/media` e
  `DELETE .../media/{id}` — fotos e vídeos (YouTube, Vimeo ou MP4/WebM direto)
  que aparecem em `/destino/{slug}`. Escopos `locations:read` / `locations:write`.
  Também gerível pelo painel (**Localizações → botão Galeria**).
- **Definições do site — SEO e contactos**: `GET/PATCH /site/settings` (`site:write`)
  cobre `app_name`, `app_description`, `app_keywords`, `meta_description`,
  `meta_keywords`, `app_currency`, `contact_email/phone/address`, as quatro redes
  sociais (incl. `social_youtube`) e `default_language`/`app_language`/`app_timezone`.
  Validação por chave (email válido, redes com `https://`, limites de comprimento).
  **Fora do alcance por segurança**: `api_key`, dados bancários e
  `maintenance_mode`/`debug_mode`.
- **Email por SMTP** (`email:send`): `GET /site/email/config` (mailer, host, porta,
  remetente e limites — nunca a palavra-passe) e `POST /site/email` (texto ou HTML,
  `cc`, `reply_to`, `dry_run`). Limites anti-abuso: 5 destinatários por pedido,
  30 envios/hora por token, sem anexos; auditoria em `email.sent` / `email.failed`.
- Tokens geríveis pelo painel admin **Tokens API** (criar, editar escopos, revogar);
  o escopo `*` = acesso total a todos os escopos, presentes e futuros.

## Fases seguintes

- Menus, preços, disponibilidade, reservas, leads, hóspedes, promoções, pagamentos,
  comunicações, analytics, saúde e deploy controlado.

Cobertura verificada: 46/46 asserções sobre as 40 rotas (ver snapshot v3).

Snapshots datados: [[kiandastay-agent-api-v1-implementado-2026-08-26]] ·
[[kiandastay-agent-api-v2-locations-2026-09-01]] ·
[[kiandastay-agent-api-v3-site-email-2026-09-02]]
