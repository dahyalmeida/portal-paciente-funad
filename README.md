# MANUAL TÉCNICO DE INSTALAÇÃO
**Projeto:** Portal do Paciente - FUNAD
**Desenvolvedor:** Dahyany Almeida
**Versão:** 1.0

## 1. Visão Geral
Este é um sistema web para gestão de agendamentos de consultas (CRUD), desenvolvido como teste prático. O sistema permite cadastro de pacientes, login seguro, agendamento de consultas, edição e cancelamento, além da geração de relatórios de impressão.

## 2. Tecnologias Utilizadas
* **Back-end:** PHP (Nativo/Estruturado) com PDO.
* **Banco de Dados:** MySQL (MariaDB).
* **Front-end:** HTML5, CSS3 (Design Responsivo e Moderno).
* **Interatividade:** JavaScript (Vanilla JS) para máscaras e validações.
* **Ambiente de Desenvolvimento:** XAMPP (Apache Server).

## 3. Requisitos do Sistema
Para executar a aplicação, é necessário um ambiente servidor local.
* **Servidor:** XAMPP, WAMP ou Docker.
* **PHP Version:** 7.4 ou superior.
* **Navegador:** Google Chrome, Edge ou Firefox.

## 4. Instalação e Configuração

### Passo 1: Arquivos
1.  Baixe o projeto ou clone o repositório.
2.  Copie a pasta do projeto para o diretório raiz do seu servidor web (ex: `C:\xampp\htdocs\portal-paciente-funad`).

### Passo 2: Banco de Dados
1.  Certifique-se de que o serviço MySQL esteja rodando no XAMPP.
2.  Acesse o **PHPMyAdmin** (geralmente em `http://localhost/phpmyadmin`).
3.  Crie um novo banco de dados com o nome exato: `funad`.
4.  Selecione este banco criado e vá na aba **"Importar"**.
5.  Selecione o arquivo `funad.sql` (disponível na raiz deste projeto) e clique em Executar.

### Passo 3: Conexão
O arquivo `conexao.php` já está configurado para o padrão do XAMPP:
* **Host:** localhost
* **User:** root
* **Password:** (vazio)
* **DB Name:** funad

*Caso seu ambiente tenha senha no MySQL, edite o arquivo `conexao.php`.*

## 5. Como Acessar
Abra o navegador e digite o endereço:
`http://localhost/portal-paciente-funad`

### Credenciais de Teste (Sugestão)
Para testar sem precisar criar um novo cadastro, utilize:
* **CPF:** 111.222.333-44
* **Senha:** 12345678

---
**Observação:** O sistema conta com recursos de impressão. Para testar os relatórios, certifique-se de que o bloqueador de pop-ups do navegador esteja desativado para o localhost.

