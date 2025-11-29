# 💼 VagasWeb - Sistema de Gerenciamento de Vagas

Sistema web desenvolvido em **PHP Nativo** e **MySQL** para a disciplina de **WEB II**. A aplicação permite o gerenciamento completo de ofertas de emprego, candidaturas e usuários, com uma interface moderna.

![Imagem Da pagina inicial](https://github.com/PedroCoelhoIF/VagasWeb/blob/main/demo/img/ImagemDemo.png?raw=true)

---

## 🚀 Funcionalidades

### 🎨 Interface & Design
- **Dark Mode Moderno:** Design focado em conforto visual com paleta de cores escuras e detalhes em Neon (Roxo/Verde).
- **Responsividade:** Layout adaptável para diferentes tamanhos de tela.
- **Feedback Visual:** Alertas de sucesso/erro e efeitos de *hover* e *glow* nos botões.

### 👤 Usuário (Candidato)
- **Cadastro e Login:** Criação de conta com upload de foto e link para LinkedIn.
- **Busca Avançada:** Pesquisa de vagas por título ou palavras-chave.
- **Filtros:** Filtragem de vagas por Categoria (ex: TI, Design, Marketing).
- **Candidatura:** Usuários logados podem se candidatar às vagas com um clique.
- **Validação:** O sistema impede candidaturas duplicadas na mesma vaga.

### 🛡️ Administrador
- **Dashboard Interativo:** Visão geral com contadores de Usuários, Vagas Ativas/Inativas e Total de Candidaturas.
- **Gestão de Vagas:** Criar, Editar, Excluir e Ativar/Desativar vagas.
- **Gestão de Categorias:** CRUD completo de categorias de emprego.
- **Visualização de Inscritos:** O admin consegue ver a lista de candidatos interessados em cada vaga (com foto e link do perfil).
- **Upload de Imagens:** Suporte para imagens de capa nas vagas.

---

## 🛠️ Tecnologias Utilizadas

- **Back-end:** PHP (Sem frameworks, código nativo).
- **Banco de Dados:** MySQL (MariaDB).
- **Front-end:** HTML5, CSS3 (Custom Properties, Flexbox).
- **Ícones:** FontAwesome 6.
- **Ambiente de Desenvolvimento:** XAMPP (Apache).

---

## ⚙️ Como Rodar o Projeto

### Pré-requisitos
Tenha o **XAMPP** instalado em sua máquina.

### Passo a Passo

1. **Clone o repositório:**
   Abra o terminal na pasta `htdocs` do seu XAMPP e rode:
   ```bash
   git clone [https://github.com/PedroCoelhoIF/VagasWeb.git](https://github.com/PedroCoelhoIF/VagasWeb.git)
