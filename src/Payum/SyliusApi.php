<?php

declare(strict_types=1);

namespace Ts\SyliusTpayPlugin\Payum;

final class SyliusApi
{
    public const PAYMENT_STATUS_NEW = 'new';
    public const PAYMENT_STATUS_PENDING = 'pending';
    public const PAYMENT_STATUS_CORRECT = 'correct';
    public const PAYMENT_STATUS_FAILURE = 'failure';

    private string $oauthClientId;
    private string $oauthClientSecret;
    private bool $productionMode;

    public function __construct(
        string $oauthClientId,
        string $oauthClientSecret,
        bool $productionMode = false
    ) {
        $this->oauthClientId = $oauthClientId;
        $this->oauthClientSecret = $oauthClientSecret;
        $this->productionMode = $productionMode;
    }

    public function getOauthClientId(): string
    {
        return $this->oauthClientId;
    }

    public function getOauthClientSecret(): string
    {
        return $this->oauthClientSecret;
    }

    public function isProductionMode(): bool
    {
        return $this->productionMode;
    }
}
