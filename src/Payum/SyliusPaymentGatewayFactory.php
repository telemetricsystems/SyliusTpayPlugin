<?php

declare(strict_types=1);

namespace Acme\SyliusTpayPlugin\Payum;

use Acme\SyliusTpayPlugin\Form\Type\SyliusGatewayConfigurationType;
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

        if (false === (bool) $config['payum.api']) {
            $config['payum.default_options'] = [
                'environment' => SyliusGatewayConfigurationType::SANDBOX_ENVIRONMENT,
                'pos_id' => '',
                'oauth_client_id' => '',
                'oauth_client_secret' => '',
            ];
            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = ['environment', 'pos_id', 'oauth_client_id', 'oauth_client_secret'];

            $config['payum.api'] = static function (ArrayObject $config): array {
                $config->validateNotEmpty($config['payum.required_options']);

                return [
                    'environment' => $config['environment'],
                    'pos_id' => $config['pos_id'],
                    'oauth_client_id' => $config['oauth_client_id'],
                    'oauth_client_secret' => $config['oauth_client_secret'],
                ];
            };
        }
    }
}
