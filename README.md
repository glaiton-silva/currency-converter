
# Conversor de Moedas

Este é um projeto Laravel para conversão de moeda com funcionalidades adicionais de envio de e-mails e histórico de cotações. O projeto utiliza a API AwesomeAPI para obter as taxas de conversão de moedas.

## Funcionalidades

- **Autenticação de Usuário**: Permite que os usuários se registrem, façam login e acessem funcionalidades protegidas.
- **Conversão de Moeda**: Permite converter valores de BRL para outras moedas com base nas taxas de câmbio fornecidas pela API AwesomeAPI.
- **Cálculo de Taxas**: Aplica taxas específicas para métodos de pagamento e valores de conversão.
- **Envio de E-mails**: Envia os detalhes da cotação por e-mail.
- **Histórico de Cotações**: Exibe um histórico das cotações realizadas pelo usuário.
- **Feedback em Tempo Real**: Utiliza SweetAlert2 para fornecer feedback ao usuário sobre o status das operações de conversão e envio de e-mails.

## Requisitos

- PHP >= 7.3
- Composer
- Laravel 8
- MySQL
- Node.js e npm

## Instalação

1. Clone o repositório:

    ```bash
    git clone https://github.com/glaiton-silva/currency-converter.git
    cd currency-converter
    ```

2. Instale as dependências do PHP:

    ```bash
    composer install
    ```

3. Instale as dependências do Node.js:

    ```bash
    npm install
    ```

4. Compile os assets:

    ```bash
    npm run dev
    ```

5. Copie o arquivo `.env.example` para `.env` e configure suas variáveis de ambiente:

    ```bash
    cp .env.example .env
    ```

6. Gere a chave da aplicação:

    ```bash
    php artisan key:generate
    ```

7. Configure o banco de dados no arquivo `.env`:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sua_base_de_dados
    DB_USERNAME=seu_usuario
    DB_PASSWORD=sua_senha
    ```

8. Execute as migrações e as sementes:

    ```bash
    php artisan migrate --seed
    ```

9. Inicie o servidor de desenvolvimento:

    ```bash
    php artisan serve
    ```

10. Acesse a aplicação em `http://127.0.0.1:8000`.

## Configuração de E-mails

Para configurar o envio de e-mails, edite as seguintes variáveis no arquivo `.env`:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu_usuario_mailtrap
MAIL_PASSWORD=sua_senha_mailtrap
MAIL_FROM_ADDRESS=seu_email@dominio.com
MAIL_FROM_NAME="${APP_NAME}"
```

> **Nota**: Substitua as configurações de e-mail conforme necessário para seu provedor de e-mail.

## Testes

### Testes Unitários

Os testes unitários verificam a lógica da conversão de moedas e o cálculo de taxas.

Para executar os testes unitários:

```bash
php artisan test --filter=Unit
```

### Testes de Integração

Os testes de integração validam o envio de e-mails e a integração com o banco de dados.

Para executar os testes de integração:

```bash
php artisan test --filter=Feature
```

## Licença

Este projeto é licenciado sob os termos da licença MIT.

