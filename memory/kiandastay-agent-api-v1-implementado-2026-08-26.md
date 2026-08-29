# KiandaStay Agent API v1 — estado em 2026-08-26

Implementado no projeto:

- autenticação Bearer `kstay__`, escopos, allowlist de IP e validade configurável;
- propriedades com leitura/escrita, `dry_run` e publicação crítica confirmada;
- upload público em `/uploads/agent`, galeria por propriedade, capa, metadados e ordenação;
- `images` definido como array de URLs absolutas;
- auditoria antes/depois com actor, rota, IP, motivo, idempotência e status;
- endpoints de páginas, media, site, identidade e logs;
- nenhum endpoint de shell, SQL ou Artisan arbitrário.

Documentação canónica: [`docs/AGENT_API.md`](../docs/AGENT_API.md).
