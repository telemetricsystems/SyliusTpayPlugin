# Overview
---
The plugin integrates [Tpay Poland payments](https://www.tpay.pl/) with Sylius based applications. After the installation you should be able to create a payment method for Tpay gateway and enable its payments in your web store.


## Note

Tpay operates the payment service provider service in various countries under the same brand ([RO](https://www.tpay.ro/), [PL](https://www.tpay.pl/), [IN](https://www.tpay.in/), [AR](https://www.tpaylatam.com/ar/), [BR](https://www.tpay.com.br/) just to name a few). Unfortunately, they use different platforms and this plugin it does not work for Tpay in Romania, for example.


# Installation
---

```bash
$ composer require telemetricsystems/sylius-tpay-plugin "dev-master"
```

Add plugin dependencies to your config/bundles.php file:

```php
return [
    Ts\SyliusTpayPlugin\TsSyliusTpayPlugin::class => ['all' => true],
]
```
## Customization
----

### Available services you can [decorate](https://symfony.com/doc/current/service_container/service_decoration.html) and forms you can [extend](http://symfony.com/doc/current/form/create_form_type_extension.html)

Run the below command to see what Symfony services are shared with this plugin:
```bash
$ bin/console debug:container ts.sylius_tpay_plugin
```

## Testing
----

```bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar install
$ yarn install
$ yarn encore dev
$ php bin/console sylius:install --env test
$ php bin/console server:start --env test
$ open http://localhost:8000
$ bin/behat features/*
$ bin/phpspec run
```

## License

---

This plugin's source code is completely free and released under the terms of the MIT license.

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)
