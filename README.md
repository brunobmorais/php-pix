# Pix @BMoraisCode

[![Maintainer](http://img.shields.io/badge/maintainer-@brunobmorais-blue.svg?style=flat-square)](https://linkedin.com/in/brunobmorais)
[![Source Code](http://img.shields.io/badge/source-bmorais/pix-blue.svg?style=flat-square)](https://github.com/brunobmorais/php-pix)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/bmorais/pix.svg?style=flat-square)](https://packagist.org/packages/bmorais/pix)
[![Latest Version](https://img.shields.io/github/release/brunobmorais/php-pix.svg?style=flat-square)](https://github.com/brunobmorais/php-pix/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Quality Score](https://img.shields.io/scrutinizer/g/brunobmorais/php-pix.svg?style=flat-square)](https://scrutinizer-ci.com/g/brunobmorais/php-pix)
[![Total Downloads](https://img.shields.io/packagist/dt/bmorais/pix.svg?style=flat-square)](https://packagist.org/packages/bmorais/pix)

###### **Pix** is the newest electronic payment method created by **Banco Central do Brasil**. You can find all the details on the [official page](https://www.bcb.gov.br/estabilidadefinanceira/pix) of Pix

O **Pix** é o mais novo método de pagamento eletrônico criado pelo **Banco Central do Brasil**. Você encontra todos os detalhes na [página oficial](https://www.bcb.gov.br/estabilidadefinanceira/pix) do Pix.

## About BMoraisCode

###### BMoraisCode is a set of small and optimized PHP components for common tasks. Held by Bruno Morais. With them you perform routine tasks with fewer lines, writing less and doing much more.

BMoraisCode é um conjunto de pequenos e otimizados componentes PHP para tarefas comuns. Mantido por Bruno Morais. Com eles você executa tarefas rotineiras com poucas linhas, escrevendo menos e fazendo muito mais.

### Highlights

- Easy to set up (Fácil de configurar)
- Create safe models (Crie de modelos seguros)
- Composer ready (Pronto para o composer)
- PSR-2 compliant (Compatível com PSR-2)

## Installation

###### Pix is available via Composer

Pix is disponível via Composer:

```bash
"bmorais/pix": "1.0.*"
```

###### or run

or execute

```bash
composer require bmorais/pix
```

## Documentation

###### For details on how to use the Data Layer, see the sample folder with details in the component directory

Para mais detalhes sobre como usar o PIX, veja a pasta de exemplo com detalhes no diretório do componente

```php
$defaultPíxKey = "CHAVEPIX";
$defaultDescription = mb_strimwidth("DESCRICAO PIX", 0, 19, ""); //ex: Pagamento do pedido
$defaultMerchantName = "NOME PESSOA PIX";
$defaultMerchantCity = "NOME CIDADE";
$defaultTxid = "IDUNICO";
$order = "";
$amount = "100,00";

// Instancia principal do payload Pix
$obPayload =
    (new Pix)
        ->setPixKey($defaultPíxKey)
        ->setDescription($defaultDescription . $order)
        ->setMerchantName($defaultMerchantName)
        ->setMerchantCity($defaultMerchantCity)
        ->setAmount($amount)
        ->setTxid($defaultTxid);

// Código de pagamento Pix
$payload = $obPayload->getPayload();
// Instancia do Qr Code
$objQrcode = new QrCode($payload);
// Imagem do Qr Code
$qrcode = (new Output\Png)->output($objQrcode, 400);
```

## Contributing

Please see [CONTRIBUTING](https://github.com/brunobmorais/php-pix/blob/master/CONTRIBUTING.md) for details.

## Support

###### Security: If you discover any security related issues, please email contato@bmorais.com instead of using the issue tracker.

Se você descobrir algum problema relacionado à segurança, envie um e-mail para contato@bmorais.com em vez de usar o
rastreador de problemas.

Thank you

## Credits

- [Bruno Morais](https://github.com/brunobmorais) (Developer)

## License

The MIT License (MIT). Please see [License File](https://github.com/brunobmorais/php-pix/blob/master/LICENSE) for more
information.