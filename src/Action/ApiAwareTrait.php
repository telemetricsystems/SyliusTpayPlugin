<?php

declare(strict_types=1);

namespace Ts\SyliusTpayPlugin\Action;

use Payum\Core\Exception\UnsupportedApiException;
use Ts\SyliusTpayPlugin\Payum\SyliusApi;

trait ApiAwareTrait
{
    private SyliusApi $api;

    public function setApi($api): void
    {
        if (!$api instanceof SyliusApi) {
            throw new UnsupportedApiException('Not supported. Expected an instance of ' . SyliusApi::class);
        }

        $this->api = $api;
    }
}
