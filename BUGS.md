# Bugs & Melhorias — testados no site live (kiandastay.vip)

Data do teste: 2026-07 · Ambiente: produção (kiandastay.vip) via navegador
Todos os itens abaixo foram **confirmados no site live** e já estão **corrigidos na branch `feature/frontend-dark-seo-pwa`** (por implantar).

## 🔴 Críticos (afetam utilizadores agora)

1. **Filtro de preço devolve 0 resultados** — CONFIRMADO no live
   - `/search?max_price=30000` → "0 hotéis encontrados / Nenhum resultado encontrado".
   - Causa: condição de datas na tabela `prices` exige preços válidos para hoje, mas os dados têm janelas expiradas.
   - Fix: remover a condição de datas do filtro de preço (`SearchResults::applyFilters`).

2. **Filtro por avaliação de hóspedes devolve 0** — escala 0-5 tratada como 0-10
   - "Excelente (9+)" nunca encontra nada (ratings reais 0-5).
   - Fix: comparar com `rating * 2` no filtro + mostrar `/5` no cartão.

3. **Filtro de comodidades devolve 0** — opções fixas em inglês (`wifi`,`pool`) vs nomes PT guardados
   - Fix: gerar opções a partir dos dados reais; lógica E (todas as selecionadas).

4. **Botões "Encontrar Hotéis" (páginas de destino) não filtram** — parâmetro errado `selectedProvince` (ignorado) → mostram todos
   - Fix: usar `province`.

## 🟠 Correção / UX

5. **Rating mostrado como "/10"** (ex.: "4.9 /10") com rótulo **"Precisa Melhorar"** num hotel 4.9
   - Deve ser **/5** (padrão de estrelas). Fix aplicado (badge 4.9/5, sem rótulo negativo; "Novo" quando 0).

6. **Texto obsoleto "experiências únicas em 2025"** → ano dinâmico (`{{ date('Y') }}`).

7. **Estatísticas falsas** no hero ("2500+ Hotéis", "180k+ Usuários") → números reais da BD.

8. **Logótipo "KiandaStayBook"** / duplicação — normalizado.

9. **URLs de hotel usam ID** (`/hotel/5`) → **slug** (`/hotel/nome-do-hotel`) para SEO, com retrocompat (302 do ID→slug).

10. **Links mortos** (footer FAQ/Privacidade/Termos, ícones sociais, etc.) → ligados a páginas reais; páginas FAQ/Privacidade/Termos criadas.

11. **Imagens de pagamento partidas** (via.placeholder.com) → badges.

## 🟢 Melhorias novas (na branch)

- **Dark mode** real (site + admin) com toggle e persistência.
- **Multilíngue PT/EN** (padrão Inglês) com seletor 🌐 e opção no admin. (Live é só PT.)
- **SEO completo**: meta/OG/Twitter/canonical, JSON-LD (Hotel + WebSite), sitemap dinâmico, robots.
- **PWA**: logótipo, ícones, manifest, service worker, página offline.
- **Rotas em português** com redirects 301.
- **GPS "hotéis perto de si"** na home e na pesquisa.
- **API REST v1 + Webhooks** para integração (requer migração da tabela `webhooks`).
- **Páginas de erro** 404/500 personalizadas.
- **Testes automatizados** (19 testes) + 2 bugs latentes da home corrigidos (Str sem import; query a coluna inexistente `featured_image`).

## Notas de implantação

- A maioria são **ficheiros** (blades, componentes, rotas, `lang/en.json`, assets) — implantáveis por FTP.
- **Após o upload é necessário limpar caches no servidor** (`php artisan config:clear && route:clear && view:clear`); se o servidor usar `route:cache`, é preciso `route:cache` de novo.
- A **API/Webhooks** requer correr a migração `create_webhooks_table` (não é possível só por FTP).
