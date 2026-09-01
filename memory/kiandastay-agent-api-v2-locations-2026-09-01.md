# KiandaStay Agent API — Destinos (Locations) e CRUD completo — estado em 2026-09-01

Snapshot das capacidades adicionadas desde [[kiandastay-agent-api-v1-implementado-2026-08-26]].
Documentação canónica: [`docs/AGENT_API.md`](../docs/AGENT_API.md).

## Destinos (Locations) — módulo novo, CRUD completo

Paridade com o admin (`/admin/locations`); alimenta as páginas públicas `/destino/{slug}`.

```http
GET    /api/agent/v1/locations                 locations:read
POST   /api/agent/v1/locations                 locations:write   (+ agent.write)
GET    /api/agent/v1/locations/{id|slug}       locations:read
PATCH  /api/agent/v1/locations/{id|slug}       locations:write   (+ agent.write)
DELETE /api/agent/v1/locations/{id|slug}       locations:delete  (+ agent.write, crítico)
```

- Resolve por **id numérico ou slug** (`/locations/luanda`).
- **Campos de escrita** (allowlist; campo desconhecido → 422 explicativo):
  `name`, `province`, `description` (≤5000), `image`, `capital`, `population`,
  `latitude` (−90..90), `longitude` (−180..180), `is_featured`, `is_active`, `slug`, `dry_run`.
- `province` valida contra os 18 slugs: `bengo benguela bie cabinda cuando-cubango
  cuanza-norte cuanza-sul cunene huambo huila luanda lunda-norte lunda-sul malanje
  moxico namibe uige zaire`. **Editar a província de um destino existente é permitido.**
- `image`: URL absoluta http(s) **ou** caminho de storage (`locations/luanda.jpg`);
  esquemas perigosos (ex.: `javascript:`) → 422.
- `POST`: obrigatórios `name` + `province`; `slug` gerado do nome se omitido (único);
  `is_active` default `true`.
- `DELETE`: exige `X-Confirm-Critical: true` (suporta `dry_run`); **409 se o destino
  tiver hotéis associados** — mover/eliminar os hotéis primeiro ou `is_active=false`.
- Filtros no index: `q` (nome/província/descrição), `province`, `featured`, `active`, `per_page` (≤100).
- Resposta inclui `province_name`, `image_url` validada, `hotels_count`, `url` pública.
- Auditoria: `location.created` / `location.updated` / `location.deleted` / `location.delete_blocked`.

### Exemplo — editar descrição+imagem de Luanda

```bash
curl -X PATCH https://kiandastay.vip/api/agent/v1/locations/luanda \
  -H "Authorization: Bearer kstay__TOKEN" \
  -H "X-Reason: atualizar página de Luanda" \
  -H "Idempotency-Key: luanda-2026-09-01-001" \
  -H "Content-Type: application/json" \
  -d '{"description":"Capital vibrante…","image":"https://kiandastay.vip/storage/locations/commons/luanda.jpg"}'
```

Verificado E2E (18/18): a alteração reflete imediatamente em `/destino/luanda`.

## Propriedades — CRUD completado (desde v1)

- `POST /properties` (`properties:write`): cria **rascunho** (`is_active=false`);
  obrigatórios `name`, `address`, `location_id`; publicar já exige
  `properties:publish` + `X-Confirm-Critical` após `dry_run`.
- `DELETE /properties/{id}` (`properties:delete`, novo escopo): crítico com
  confirmação; **409 se houver reservas** (FKs em cascade levariam o histórico).
- `property_type` alargado: `hotel|resort|hospedaria|residencial|apartment|house`
  (enum da BD migrado; `residencial` tem badge/filtros próprios no site).

## Gestão de tokens

- Painel admin **Tokens API** (`/admin/agent-tokens`): criar, editar escopos por
  checkboxes, revogar/reativar. Escopo `*` = acesso total (todos os escopos,
  presentes e futuros) — o painel mostra badge "Acesso total" e o editor marca
  tudo automaticamente.

## Referência pública útil

- `GET /api/v1/locations?q=…&limit=…` (sem auth) — ids/slugs para `location_id`
  ao criar propriedades e para o autocomplete do site.
