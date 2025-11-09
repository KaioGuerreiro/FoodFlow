# FoodFlow - MVP

Sistema de Gerenciamento de Restaurante

Este repositório contém o projeto FoodFlow, uma aplicação web completa para gerenciamento de restaurante, construída com React + Vite e estilizada com Tailwind CSS.

## ✨ Funcionalidades do MVP

### 🎯 Dashboard

- Visualização de estatísticas em tempo real
- Contador de usuários e pratos cadastrados
- Cálculo automático do valor médio dos pratos
- Acesso rápido às principais funcionalidades
- Visualização dos últimos registros

### 👥 Gerenciamento de Usuários

- ✅ Cadastro de novos usuários
- ✅ Listagem completa com busca
- ✅ Edição inline de dados
- ✅ Exclusão com confirmação
- ✅ Formatação automática de CPF
- ✅ Validação de campos obrigatórios

### 🍴 Gerenciamento de Cardápio

- ✅ Cadastro de pratos
- ✅ Listagem em cards responsivos
- ✅ Edição de pratos existentes
- ✅ Exclusão com confirmação
- ✅ Busca por nome ou ingredientes
- ✅ Formatação de valores monetários

### 💾 Persistência de Dados

- Armazenamento local usando localStorage
- Dados mantidos entre sessões
- Sem necessidade de backend

## 📂 Estrutura do repositório

```text
FoodFlow/
├─ my-app/                 # Aplicação web (React + Vite)
│  ├─ src/
│  │  ├─ App.jsx              # Componente principal com roteamento
│  │  ├─ Dashboard.jsx        # Dashboard com estatísticas
│  │  ├─ CadastroUsuario.jsx  # Formulário de cadastro de usuários
│  │  ├─ ListaUsuarios.jsx    # Lista e gerenciamento de usuários
│  │  ├─ CadastroPrato.jsx    # Formulário de cadastro de pratos
│  │  ├─ ListaPratos.jsx      # Lista e gerenciamento de pratos
│  │  └─ index.css
│  ├─ public/
│  ├─ package.json
│  ├─ postcss.config.js
│  └─ tailwind.config.js
├─ Diagram de Caso de Uso.png
├─ Diagrama Classe.png
├─ Diagrama Entidade Relacionamento.png
└─ Modelo de documento de requisitos.pdf
```

## 🚀 Como executar

1. Acesse a pasta do app web:

```powershell
cd .\my-app
```

2. Instale as dependências:

```powershell
npm install
```

3. Rode o servidor de desenvolvimento:

```powershell
npm run dev
```

4. Abra no navegador:

- <http://localhost:5173/>

## 🎨 Tecnologias Utilizadas

- **React 19** - Biblioteca JavaScript para interfaces
- **Vite 7** - Build tool e dev server
- **Tailwind CSS 4** - Framework CSS utilitário
- **LocalStorage API** - Persistência de dados local

## 📱 Recursos da Interface

- ✨ Design moderno e responsivo
- 🎨 Gradientes coloridos para cada seção
- 🔍 Sistema de busca em tempo real
- ✏️ Edição inline de registros
- 📊 Cards de estatísticas no dashboard
- 🔔 Confirmações para ações destrutivas
- 📱 Totalmente responsivo para mobile e desktop

## 🧩 Funcionalidades

- Cadastro de Usuário (nome, data de nascimento, CPF, endereço)
- Cadastro de Prato (nome, ingredientes, valor)
- Página inicial com navegação simples
- Layout responsivo com Tailwind CSS

## 🎨 Observações sobre Tailwind CSS (v4)

- Este projeto usa Tailwind CSS v4 com PostCSS.
- Certifique-se de ter no `postcss.config.js`:

```js
export default {
  plugins: {
    "@tailwindcss/postcss": {},
    autoprefixer: {},
  },
};
```

- E no `src/index.css`:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

Se encontrar o erro do PostCSS dizendo que o plugin mudou, instale o pacote:

```powershell
npm install -D @tailwindcss/postcss
```

## 📜 Scripts úteis (my-app/package.json)

- `dev`: inicia o Vite em modo desenvolvimento
- `build`: build de produção
- `preview`: pré-visualização do build
- `lint`: executa o ESLint

## 🧭 Páginas principais (src/)

- `CadastroUsuario.jsx` — formulário de cadastro de usuário
- `CadastroPrato.jsx` — formulário de cadastro de prato
- `App.jsx` — navegação simples entre as páginas

## 📎 Artefatos de análise e requisitos

- Diagramas e documento de requisitos estão na raiz do repositório:
  - `Diagram de Caso de Uso.png`
  - `Diagrama Classe.png`
  - `Diagrama Entidade Relacionamento.png`
  - `Modelo de documento de requisitos.pdf`

## 👤 Autor

- Kaio Guerreiro
