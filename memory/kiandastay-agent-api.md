# KiandaStay Agent API — ficha interna

Implementação canónica e exemplos: [`docs/AGENT_API.md`](../docs/AGENT_API.md).

- Base: `https://kiandastay.vip/api/agent/v1`
- Bearer: `kstay__...`, hash em repouso, expiração máxima 90 dias, allowlist de IP.
- Escrita: `X-Reason` + `Idempotency-Key` obrigatórios.
- Críticos: dry-run/preview e confirmação explícita.
- Auditoria: actor, rota, IP, motivo, antes/depois, status e data.
- Sem shell, SQL ou Artisan arbitrário.
- Fase inicial implementada: identidade, site, páginas/blocos/SEO, propriedades, media e logs.
- Fases seguintes: menus, preços, disponibilidade, reservas, leads, hóspedes, promoções, pagamentos, comunicações, analytics, saúde e deploy controlado.

