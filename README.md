# 📚 Caderno de Erros

## Sistema Inteligente de Estudos com Questões

O **Caderno de Erros** é uma aplicação web desenvolvida em Laravel que permite criar, gerenciar e estudar questões de múltipla escolha de forma inteligente, com foco nas questões que você mais erra.
Versão em produção: https://caderno-de-erros-production.up.railway.app/login

![Tela Inicial]

<img width="507" height="630" alt="Captura de tela 2025-08-24 123855" src="https://github.com/user-attachments/assets/b51bd1d3-c67a-4496-bf79-357406d20953" />



## ✨ Funcionalidades

### 🎯 Quiz Inteligente
- Sistema de quiz com questões de múltipla escolha (A, B, C, D)
- Filtro por categorias para estudo direcionado
- Feedback imediato após responder
- Explicações detalhadas para cada questão
- Interface responsiva e intuitiva

![Quiz Interface]

<img width="507" height="630" alt="Captura de tela 2025-08-24 123855" src="https://github.com/user-attachments/assets/ecbb022c-d664-4166-a5b7-282f1bcbf02c" />



### 📝 Criação de Questões
- Interface simplificada para criação de questões
- **Funcionalidade especial**: Cole a questão inteira com as opções A), B), C), D) e o sistema separa automaticamente
- Associação com categorias
- Campo para explicação da resposta correta
- Validação de dados

![Criar Questão]

<img width="482" height="630" alt="Captura de tela 2025-08-24 123817" src="https://github.com/user-attachments/assets/4d659fc1-bd80-4ae7-9c8a-3b789029715b" />



### 🗂️ Gerenciamento
- **Excluir Questões**: Interface para remoção de questões desnecessárias
- **Categorias**: Sistema completo de categorização com CRUD
- Organização eficiente do conteúdo

![Excluir Questões]

<img width="515" height="496" alt="Captura de tela 2025-08-24 123829" src="https://github.com/user-attachments/assets/0e655e6f-b6b2-427a-96c1-0e186a54c63a" />

![Gerenciar Categorias]

<img width="527" height="468" alt="Captura de tela 2025-08-24 123839" src="https://github.com/user-attachments/assets/d4814f7d-428b-4387-ad54-0fcc47e9618a" />



### 📊 Estatísticas Inteligentes
- Questões mais erradas (para focar no estudo)
- Questões mais acertadas
- Filtros por categoria
- Contadores de acertos e erros
- Interface visual com badges coloridos

## 🚀 Tecnologias Utilizadas

- **Framework**: Laravel (PHP)
- **Frontend**: Bootstrap 5
- **JavaScript**: Vanilla JS
- **Icons**: Font Awesome
- **Database**: MySQL/PostgreSQL (compatível)

## 📋 Pré-requisitos

- PHP 8.0+
- Composer
- MySQL ou PostgreSQL
- Node.js (para assets)

## 🛠️ Instalação

1. **Clone o repositório**
```bash
git clone https://github.com/seu-usuario/caderno-de-erros.git
cd caderno-de-erros
```

2. **Instale as dependências**
```bash
composer install
npm install
```

3. **Configure o ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure o banco de dados**
Edite o arquivo `.env` com suas configurações:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=caderno_erros
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

5. **Execute as migrações**
```bash
php artisan migrate
```

6. **Compile os assets**
```bash
npm run dev
```

7. **Inicie o servidor**
```bash
php artisan serve
```

Acesse `http://localhost:8000`

## 🗃️ Estrutura do Banco de Dados

### Tabelas Principais

- **questions**: Armazena as questões
  - `id`, `question_text`, `options` (JSON), `correct_answer`, `reason`, `category_id`
  - `correct_count`, `wrong_count` (para estatísticas)

- **categories**: Categorias das questões
  - `id`, `name`, `created_at`, `updated_at`

- **user_answers**: Histórico de respostas (se implementado)
  - Para tracking de progresso individual

## 🎨 Funcionalidades Especiais

### 📋 Auto-parse de Questões
Ao criar uma nova questão, você pode colar o texto completo no formato:
```
Qual é a capital do Brasil?
A) Rio de Janeiro
B) São Paulo
C) Brasília
D) Belo Horizonte
```

O sistema automaticamente separa:
- O enunciado da questão
- As opções A, B, C e D nos campos correspondentes

### 🧠 Sistema Inteligente
- Prioriza questões com mais erros
- Estatísticas detalhadas por categoria
- Interface focada na experiência do usuário

## 🔧 Rotas Principais

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/quiz` | Interface do quiz |
| POST | `/quiz/check` | Verificar resposta |
| GET | `/questions/create` | Criar questão |
| POST | `/questions` | Salvar questão |
| GET | `/statistics` | Ver estatísticas |
| GET | `/categories` | Gerenciar categorias |

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para detalhes.

## 📧 Contato

Para dúvidas ou sugestões, abra uma issue no GitHub.

---

**Desenvolvido com ❤️ para facilitar seus estudos!**
