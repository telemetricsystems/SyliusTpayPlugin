<?php

declare(strict_types=1);

namespace Ts\SyliusTpayPlugin\Payum;

use Ts\SyliusTpayPlugin\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class SyliusPaymentGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults(
            [
                'payum.factory_name' => 'tpay',
                'payum.factory_title' => 'Tpay',
            ]
        );

        if (false === (bool)$config['payum.api']) {
            $config['payum.api'] = static function (ArrayObject $config) {
                $config->validateNotEmpty(['oauth_client_id', 'oauth_client_secret']);
                $config->validatedKeysSet(['production_mode']);

                return new SyliusApi($config['oauth_client_id'], $config['oauth_client_secret'], $config['production_mode']);
            };
        }
    }
}
