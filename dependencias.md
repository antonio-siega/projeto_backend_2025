
Este projeto utiliza o Composer para gerenciamento de dependências e o SDK oficial do Mercado Pago para PHP para integração com pagamentos.


# O Composer é o gerenciador de dependências do PHP.

Ele é responsável por baixar, atualizar e carregar automaticamente as bibliotecas usadas no projeto.

- Gerencia bibliotecas externas

- Cria o arquivo vendor/autoload.php (é o arquivo que carrega as classes das bibliotecas instaladas)

## Como instalar o Composer (Windows):

1 - Acesse: https://getcomposer.org/download/;

2 - Baixe o Composer-Setup.exe;

3 - Durante a instalação, selecione o PHP do XAMPP;

4 - Verifique no terminal se instalou corretamente com o comando "composer --version" (se aparecer a versão do Composer então está tudo certo).

# Mercado Pago SDK (PHP)

O projeto utiliza o SDK oficial do Mercado Pago para:

- Criar pagamentos;

- Gerar links de pagamento;

- Consultar status de pagamentos;

- Processar notificações do mercado pago (webhook).


## Instalação do Mercado Pago SDK

Dentro da pasta raiz do projeto, execute o comando no terminal:

"composer require mercadopago/dx-php"

Após isso, o Composer irá:

- Criar a pasta vendor/

- Gerar o arquivo vendor/autoload.php

- Instalar todas as dependências necessárias.

### Sempre que um arquivo PHP utilizar o Mercado Pago SDK, é obrigatório incluir o arquivo vendor/autoload.php.