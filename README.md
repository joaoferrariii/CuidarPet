# 🐾 CuidarPet — Sistema de Gestão para Clínicas Veterinárias

> Sistema completo e moderno para gestão clínica veterinária, controle de tutores, prontuário médico digital, carteira de vacinação com monitoramento inteligente de validade e histórico financeiro de atendimentos.

---

## 📌 Sumário
- [Sobre o Projeto](#-sobre-o-projeto)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [Funcionalidades Principais](#-funcionalidades-principais)
- [Arquitetura e Estrutura de Pastas](#-arquitetura-e-estrutura-de-pastas)
- [Pré-requisitos](#-pré-requisitos)
- [Instalação e Configuração Passo a Passo](#-instalação-e-configuração-passo-a-passo)
- [Usuários e Credenciais Padrão (Seeders)](#-usuários-e-credenciais-padrão-seeders)
- [Boas Práticas e Decisões de Design](#-boas-práticas-e-decisões-de-design)
- [Comandos Úteis](#-comandos-úteis)
- [Licença](#-licença)

---

## 📖 Sobre o Projeto

O **CuidarPet** foi projetado para oferecer a médicos veterinários e equipes de clínicas uma experiência ágil, segura e intuitiva no acompanhamento de pacientes e seus tutores. 

O sistema integra o poder do ecossistema **Laravel 12** no backend com a fluidez de uma Single-Page Application (SPA) através do **Inertia.js v2** e **Vue 3 com TypeScript**, eliminando a necessidade de APIs REST desacopladas sem abrir mão de alta reatividade e componentes modernos.

---

## 🛠 Tecnologias Utilizadas

### **Backend**
- **PHP 8.2+**
- **Laravel 12** (Framework principal)
- **Inertia.js v2** (Laravel Adapter)
- **Laravel Sanctum** (Autenticação segura de sessões e tokens)
- **Tightenco Ziggy** (Roteamento tipado do Laravel acessível no frontend)
- **Eloquent ORM** (Modelagem relacional e migrations estruturadas)

### **Frontend**
- **Vue 3** (Composition API com sintaxe `<script setup>`)
- **TypeScript** (Tipagem estática em páginas e componentes)
- **Tailwind CSS v3** (Estilização responsiva utilitária)
- **Radix Vue** (Componentes primitivos acessíveis: Dialogs, Modals, Dropdowns)
- **Lucide Icons** (Ícones vetoriais modernos)
- **Vite 6** (Bundler e servidor de desenvolvimento ultra-rápido)

### **Banco de Dados**
- **MySQL / MariaDB** (Suporte nativo a SQLite e PostgreSQL via configuração do `.env`)

---

## 🚀 Funcionalidades Principais

### 1. 👥 Gestão de Tutores (Clientes)
- Listagem paginada com busca em tempo real por nome e CPF.
- Validação e higienização automática de CPF (sanitização para 11 dígitos numéricos tanto na camada de Request quanto no Model).
- Modal de cadastro e edição com fluxo de confirmação em 2 etapas.
- Exclusão segura com diálogo pop-up de confirmação.

### 2. 🐕 Prontuário Médico Digital do Pet (`/pets/{id}`)
- **Ficha Cadastral:** Espécie/Raça, idade calculada, gênero e status de castração.
- **Linha do Tempo Clínica:** Histórico de consultas, diagnósticos, observações clínicas, valores e formas de pagamento.
- **Carteira de Vacinação Inteligente:**
  - Registro de vacinas com cálculo de revacinação.
  - Indicador de status em tempo real (**"Em dia"** / **"Vencida"**), compatível com formatos de data brasileiros (`dd/mm/aaaa`) e cálculo até o fim do dia de validade (`23:59:59`).
- **Lançamento Rápido no Prontuário:** Modais inline para aplicação imediata de vacina ou registro de atendimento com confirmação prévia.
- **Impressão Formatada:** Botão exclusivo para gerar prontuário físico e carteira de vacinação com formatação dedicada para impressão (`@media print`), ocultando barras laterais e botões de ação.

### 3. 💉 Módulo de Vacinações
- Visão geral de todas as vacinações aplicadas na clínica.
- Filtros, paginação e status de cobertura de imunização.
- Exclusão com confirmação pop-up.

### 4. 🩺 Módulo de Registros de Serviços & Atendimentos
- Controle de serviços prestados (Consultas, Procedimentos Cirúrgicos, Banho e Tosa).
- Vinculação com o paciente e método de pagamento (PIX, Cartão de Crédito, Cartão de Débito, Dinheiro).

### 5. 🛡️ Segurança e Validações
- Confirmações pop-up antes de qualquer exclusão destrutiva ou inserção de dados.
- FormRequests dedicados (`ClienteRequest`, `PetRequest`, `VacinacaoRequest`, `RegistroServicoRequest`) com sanitização prévia (`prepareForValidation`).
- Prevenção contra colisões de métodos internos do Inertia (`form.data()`).

---

## 📂 Arquitetura e Estrutura de Pastas

```text
CuidarPet/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Controladores (Cliente, Pet, Vacinacao, RegistroServico)
│   │   └── Requests/             # FormRequests com regras e pré-sanitização
│   └── Models/                   # Models Eloquent (Cliente, Pet, Vacina, Vacinacao, etc.)
├── database/
│   ├── migrations/               # Estrutura relacional do banco de dados
│   └── seeders/                  # Carga de dados iniciais (Roles, Usuários, Serviços, Pets)
├── resources/
│   ├── css/                      # Estilos globais Tailwind CSS
│   └── js/
│       ├── components/           # Componentes reutilizáveis (UI, Dialog, Card, Input)
│       ├── layouts/              # Layout principal da aplicação e navegação
│       ├── pages/                # Páginas Inertia.js (Clientes, Pets, Vacinacoes, Atendimentos)
│       └── types/                # Definições de tipos TypeScript
├── routes/
│   ├── web.php                   # Rotas da aplicação autenticada
│   └── auth.php                  # Rotas de login, registro e recuperação de senha
└── public/
    └── build/                    # Bundles compilados pelo Vite para produção
```

---

## 📋 Pré-requisitos

Antes de iniciar, certifique-se de ter instalado em seu ambiente:
- [PHP](https://www.php.net/) `>= 8.2` com as extensões `pdo_mysql`, `mbstring`, `openssl`, `curl` ativas.
- [Composer](https://getcomposer.org/) `>= 2.5`
- [Node.js](https://nodejs.org/) `>= 18.0` e `npm`
- Servidor de Banco de Dados **MySQL** (ex: via **XAMPP**, Docker ou serviço local)

---

## ⚙️ Instalação e Configuração Passo a Passo

### 1. Clonar ou Acessar a Pasta do Projeto
Abra o terminal na pasta do projeto:
```bash
cd c:\xampp\htdocs\CuidarPet
```

### 2. Instalar Dependências do PHP (Composer)
```bash
composer install
```

### 3. Instalar Dependências do Frontend (Node/NPM)
```bash
npm install
```

### 4. Configurar o Arquivo de Ambiente (`.env`)
Se o arquivo `.env` ainda não existir, copie o exemplo:
```bash
cp .env.example .env
```
Gere a chave da aplicação:
```bash
php artisan key:generate
```

### 5. Configurar a Conexão com o Banco de Dados
Abra o arquivo `.env` e configure os parâmetros do seu banco de dados MySQL:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cuidarpet
DB_USERNAME=root
DB_PASSWORD=
```
> *Certifique-se de que o banco de dados `cuidarpet` foi criado no MySQL (ex: via phpMyAdmin).*

### 6. Executar as Migrations e Seeders
Execute o comando abaixo para criar todas as tabelas e carregar os dados de demonstração:
```bash
php artisan migrate:fresh --seed
```

### 7. Compilar os Assets do Frontend
Para ambiente de **desenvolvimento** com Hot Module Replacement (HMR):
```bash
npm run dev
```
Ou para compilar a versão de **produção**:
```bash
npm run build
```

### 8. Iniciar o Servidor Local do Laravel
Em outro terminal, execute:
```bash
php artisan serve
```
Acesse a aplicação no seu navegador em: **`http://localhost:8000`**

---

## 🔑 Usuários e Credenciais Padrão (Seeders)

Após rodar `php artisan migrate --seed`, você pode entrar com as contas pré-configuradas:

| Nível de Acesso | E-mail | Senha Padrão |
| :--- | :--- | :--- |
| **Administrador** | `admin@cuidarpet.com` | `password` |
| **Funcionário** | `funcionario@cuidarpet.com` | `password` |

---

## 💡 Boas Práticas e Decisões de Design

1. **Inertia v2 e useForm:**
   Para formulários com campos de data, evitou-se nomear propriedades internas reativas como `data` (ex: `data_aplicacao` ou `data_registro`), utilizando o método `.transform()` antes do envio para não conflitar com a função interna `form.data()` do Inertia.js.
2. **Sanitização de Documentos:**
   O CPF é tratado na entrada (`prepareForValidation`) e na persistência (Mutator no Model `Cliente`), garantindo armazenamento numérico de 11 dígitos, evitando erros de truncamento em campos estritos do MySQL.
3. **Modais em 2 Etapas (Two-step Dialogs):**
   Para evitar problemas de sobreposição de overlays e perda de foco do Radix Vue ao encadear modais, os formulários com confirmação utilizam transição de passos interna (`isConfirmStep`), proporcionando uma experiência de usuário limpa e sem travamentos.

---

## ⌨️ Comandos Úteis

- **Recompilar assets do frontend:**
  ```bash
  npm run build
  ```
- **Limpar caches da aplicação:**
  ```bash
  php artisan optimize:clear
  ```
- **Formatação de código PHP (Laravel Pint):**
  ```bash
  ./vendor/bin/pint
  ```
- **Executar testes automatizados (Pest):**
  ```bash
  php artisan test
  ```

---

## 📄 Licença

Este projeto é desenvolvido para fins educacionais e de gestão de clínicas veterinárias, sob a licença [MIT](https://opensource.org/licenses/MIT).
