# EPIC SANA Luanda — correção técnica

## Dados verificados recebidos

- Propriedade ID 1, slug `epic-sana-luanda`.
- Cinco estrelas, Rua da Missão, Baía de Luanda.
- Website e imagens provenientes do domínio oficial SANA Hotels.

## Correções implementadas na plataforma

- URLs externas válidas deixam de ser trocadas antecipadamente por placeholder.
- Uploads novos são servidos diretamente em `/uploads/agent/...`.
- Resource de propriedade devolve `thumbnail`, `images`, contactos e media relacionados.
- `images` aceita array de URLs absolutas e devolve erros 422 estruturados.
- Galeria possui endpoints para listar, anexar, editar, remover e reordenar.
- Capa da galeria sincroniza `hotel.thumbnail`.

## Operação protegida

- Escritas exigem `X-Reason` e `Idempotency-Key`.
- Remoções e publicação exigem confirmação crítica após dry-run.
- Ações externas de comunicação continuam dependentes de autorização humana explícita.
