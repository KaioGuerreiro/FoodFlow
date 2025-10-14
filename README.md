# FoodFlow

Sistema de Gerenciamento de Restaurante

Este repositório contém o projeto FoodFlow, uma aplicação web para cadastro de usuários e pratos, construída com React + Vite e estilizada com Tailwind CSS.

## 📂 Estrutura do repositório

```text
FoodFlow/
├─ my-app/                 # Aplicação web (React + Vite)
│  ├─ src/
│  │  ├─ App.jsx
│  │  ├─ CadastroUsuario.jsx
│  │  ├─ CadastroPrato.jsx
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

1. Instale as dependências:

```powershell
npm install
```

1. Rode o servidor de desenvolvimento:

```powershell
npm run dev
```

1. Abra no navegador:

- <http://localhost:5173/>

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
    '@tailwindcss/postcss': {},
    autoprefixer: {},
  },
}
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
