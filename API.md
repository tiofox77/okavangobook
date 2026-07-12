# OkavangoBook — API REST v1

API JSON para integração com sistemas externos (motores de reserva, PMS, CRMs, etc.).

- **Base URL:** `https://SEU-DOMINIO/api/v1`
- **Formato:** JSON (envie `Accept: application/json`)
- **Autenticação (escrita):** cabeçalho `X-API-Key: <chave>` — gere a chave no painel **Admin → Definições → API Key**.
- **Rate limiting:** leitura 120 req/min, escrita 60 req/min por IP.

---

## Autenticação

Endpoints de **leitura (GET)** de hotéis/localizações são públicos.
Endpoints de **escrita** (criar reservas, gerir webhooks) exigem a API key:

```
X-API-Key: okb_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

Sem chave válida → `401 Unauthorized`. Se a API key não estiver configurada → `503`.

---

## Endpoints

### `GET /api/v1/status`
Verificação de saúde. Devolve `{ "status": "ok", "version": "v1", "time": "..." }`.

### `GET /api/v1/hotels`
Lista/pesquisa hotéis (paginado). **Parâmetros (query):**

| Param | Descrição |
|-------|-----------|
| `q` | Texto (nome do hotel, localidade ou província) |
| `province` | Filtra por província (ex.: `Luanda`) |
| `property_type` | `hotel`, `resort` ou `hospedaria` (aceita array) |
| `stars` | 1–5 (aceita array) |
| `min_price`, `max_price` | Faixa de preço (AKZ) |
| `sort` | `price_asc`, `price_desc`, `rating`, `stars` |
| `per_page` | Máx. 100 (default 20) |

```bash
curl "https://SEU-DOMINIO/api/v1/hotels?province=Luanda&sort=price_asc&per_page=10"
```

### `GET /api/v1/hotels/{slug}`
Detalhe de um hotel (inclui `room_types`). Aceita slug ou id.

### `GET /api/v1/locations`
Lista destinos/localizações. Param opcional `province`.

### `POST /api/v1/bookings` 🔒
Cria uma reserva. **Corpo (JSON):**

```json
{
  "hotel_id": 1,
  "room_type_id": 1,
  "check_in": "2026-08-01",
  "check_out": "2026-08-03",
  "guests": 2,
  "customer_name": "Ana Silva",
  "customer_email": "ana@exemplo.com",
  "customer_phone": "+244900000000",
  "special_requests": "Andar alto",
  "total_price": 42268
}
```

`total_price` é opcional (calculado a partir do tipo de quarto se omitido).
Resposta `201`:

```json
{ "data": { "id": 3, "confirmation_code": "OKB-OCBAPQOQ", "status": "pending", ... } }
```

### `GET /api/v1/bookings/{code}` 🔒
Consulta o estado de uma reserva pelo `confirmation_code` (ou id).

---

## Webhooks (eventos → o seu sistema)

Registe URLs que recebem eventos em tempo real (ex.: nova reserva).

### `POST /api/v1/webhooks` 🔒
```json
{
  "url": "https://seu-sistema.com/hooks/okavango",
  "events": ["reservation.created", "reservation.status_changed"],
  "name": "Meu PMS"
}
```
Resposta inclui o **`secret`** (mostrado apenas uma vez) usado para assinar os payloads.

**Eventos disponíveis:** `reservation.created`, `reservation.status_changed`, `reservation.cancelled` (ou `"*"` para todos).

### `GET /api/v1/webhooks` 🔒 — lista os webhooks e eventos disponíveis.
### `DELETE /api/v1/webhooks/{id}` 🔒 — remove um webhook.

### Payload entregue (POST para o seu URL)
```json
{
  "event": "reservation.created",
  "timestamp": "2026-07-12T15:50:36+00:00",
  "data": { "id": 3, "confirmation_code": "OKB-...", "hotel": {...}, ... }
}
```
Cabeçalhos: `X-Webhook-Event` e `X-Webhook-Signature: sha256=<hmac>`.

### Verificar a assinatura (exemplo)
```php
$expected = 'sha256=' . hash_hmac('sha256', $rawBody, $webhookSecret);
if (!hash_equals($expected, $request->header('X-Webhook-Signature'))) {
    abort(401);
}
```
Um webhook é desativado automaticamente após 10 falhas consecutivas de entrega.

---

## Códigos de estado
`200` OK · `201` Criado · `401` API key inválida · `404` Não encontrado · `422` Validação · `429` Rate limit · `503` API não configurada.
