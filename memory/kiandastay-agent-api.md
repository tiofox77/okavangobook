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
- Tokens geríveis pelo painel admin **Tokens API** (criar, editar escopos, revogar);
  o escopo `*` = acesso total a todos os escopos, presentes e futuros.

## Fases seguintes

- Menus, preços, disponibilidade, reservas, leads, hóspedes, promoções, pagamentos,
  comunicações, analytics, saúde e deploy controlado.

Snapshots datados: [[kiandastay-agent-api-v1-implementado-2026-08-26]] ·
[[kiandastay-agent-api-v2-locations-2026-09-01]]
