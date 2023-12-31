# Coding: Caixa Eletrônico - v1.0.0

## Descrição

O programa no padrão de stdin e stdout que simula algumas funcionalidades referentes a um caixa eletrônico

## Funcionalidades implementadas

- Abastecimento do caixa eletrônico (adicionar notas no caixa)
- Saque
- Listar Operações de Saque

## Pré-requisitos

Para execução do programa é necessário ter instalado no ambiente os softwares abaixo nas versões  descritas ou superiores:
 -  PHP v8.1
 - Composer v2.4 
 - Docker v4 (opcional)

## Instalação e execução

Para facilitar execução do programa foi implementado um arquivo Dockerfile que fica responsável por realizar o build do
programa, após o build basta executar o comando de execução docker run apontando para a imagem criada no momento do
build

### Via Docker

Na raiz do projeto, segue os passos abaixo

#### Build

``` bash
    docker build -t nome-da-imagem .
```

#### Run

``` bash
    docker run -it locaweb-caixa-eletronico
```

### Via Cmd/Bash

Caso não seja possivel executar via **Docker**, pode se executar o programa via linha de comando, na raiz do projeto executar o comando abaixo.

#### Instalação de dependencias via Composer

``` bash
    composer install
```

#### Execução

``` bash
    php index.php
```

## Testes
Foi utilizado do PHPUnit para realizar a criação e execução de testes de unidade, para executar os testes são necessário executar os comandos abaixo.

``` bash
    ./vendor/bin/phpunit
```

## Utilização

O programa foi desenvolvido para receber em suas operações um **Json String** para realizar o processamento, abaixo segue exemplo da estrutura esperada.

#### Opção 1 - Abastecimento do caixa eletrônico (adicionar notas no caixa)

``` json
    /// Faz o abastacimento do caixa 
    {"caixa":{"caixaDisponivel":false,"notas":{"notasDez":10,"notasVinte":10,"notasCinquenta":10,"notasCem":10}}}
    
    /// Faz o abastacimento do caixa, liberandoo para utilização(atenção para o parametro caixaDisponivel = true)
    {"caixa":{"caixaDisponivel":true,"notas":{"notasDez":10,"notasVinte":10,"notasCinquenta":10,"notasCem":10}}}
```

#### Opção 2 - Saque

``` json
    /// Faz o saque do caixa 
    {"saque":{"valor":50,"horario":"2023-07-24T10:00:00.000Z"}}
```

Cada operação traz como resposta um **Json** semelhante abaixo

``` json
    /// Caso de sucesso da operação 
    {
    "caixaDisponivel": true,
    "notas": {
        "notasDez": 10,
        "notasVinte": 10,
        "notasCinquenta": 10,
        "notasCem": 10
    }
  }

  /// Caso de falha da operação, o atributo "erros" será retornado sendo ele um array com a mensagem de descrição do erro

  {
      "caixaDisponivel": true,
      "notas": {
          "notasDez": 10,
          "notasVinte": 10,
          "notasCinquenta": 10,
          "notasCem": 10
      },
      "erros": [
          "caixa-em-uso"
      ]
  }

```

## Autores

- Bruno Feliciano de
  Godoi - [Github](https://github.com/brunogodoif) - [Linkedin](https://www.linkedin.com/in/bruno-feliciano-de-godoi/) - [OutlookMail](malito:brunofgodoi@outlook.com.br)

## Licença

MIT
