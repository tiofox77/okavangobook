# Okavango Book - Planejamento da Versão 1.0 (MVP)

## Objetivos
Desenvolver um sistema mínimo viável que permita aos usuários buscar hospedagens e visualizar resultados, similar ao Trivago, mas com funcionalidades essenciais.

## Estrutura de Banco de Dados

### Tabelas Principais
1. **users** - Usuários do sistema
   - id, name, email, password, etc.

2. **locations** - Localizações/Destinos
   - id, name, state, country, description, image

3. **hotels** - Hotéis/Hospedagens
   - id, name, description, address, location_id, stars, thumbnail, coordinates
   - amenities (JSON), check_in_time, check_out_time

4. **room_types** - Tipos de quartos
   - id, hotel_id, name, description, capacity, size

5. **prices** - Preços
   - id, hotel_id, room_type_id, provider, price, currency, link

6. **searches** - Histórico de buscas
   - id, user_id, location_id, check_in, check_out, guests, created_at

## Componentes Principais

### Páginas
1. **Homepage** - Página inicial com formulário de busca
2. **Search Results** - Listagem de resultados da busca
3. **Hotel Details** - Detalhes do hotel selecionado
4. **Authentication** - Login, registro e recuperação de senha

### Componentes Livewire
1. **SearchForm** - Formulário de busca principal
2. **HotelsList** - Lista de hotéis nos resultados
3. **FilterSidebar** - Barra lateral com filtros básicos
4. **HotelCard** - Card de exibição do hotel
5. **PriceComparison** - Comparação simples de preços

## Fluxo do Usuário
1. Usuário acessa a página inicial
2. Insere localidade, datas e número de hóspedes
3. Sistema busca hotéis disponíveis
4. Usuário visualiza lista de resultados
5. Usuário pode clicar em um hotel para ver detalhes
6. Na página de detalhes, usuário vê informações completas e preços
7. Usuário é redirecionado para o site da reserva ao clicar em "Reservar"

## Cronograma de Desenvolvimento

### Semana 1: Configuração e Banco de Dados
- Configurar Laravel com Livewire e Alpine.js
- Criar migrations e models
- Implementar sistema de autenticação

### Semana 2: Páginas e Componentes Básicos
- Criar página inicial e componente de busca
- Desenvolver página de resultados e componentes de listagem
- Implementar página de detalhes do hotel

### Semana 3: Lógica de Busca e Filtragem
- Implementar lógica de busca por localidade e datas
- Criar sistema de filtragem básica
- Desenvolver comparação simples de preços

### Semana 4: UI/UX e Finalização
- Refinar interface com Tailwind CSS
- Implementar responsividade
- Testes e correções de bugs
- Deploy da versão MVP

## Métricas de Sucesso
- Sistema permite buscar hotéis por localidade e datas
- Resultados são exibidos corretamente com informações básicas
- Página de detalhes mostra informações completas do hotel
- Usuários podem navegar entre as páginas sem erros
- Tempo de carregamento da busca inferior a 3 segundos

## Dados Iniciais
Para a versão MVP, podemos criar seeders com dados fictícios para:
- 10 localidades populares
- 50 hotéis distribuídos entre essas localidades
- 3-5 tipos de quarto por hotel
- 2-3 provedores de preço por hotel

Este planejamento servirá como guia para o desenvolvimento da Versão 1.0 do Okavango Book.
