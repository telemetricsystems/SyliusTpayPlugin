<?php

declare(strict_types=1);

namespace Ts\SyliusTpayPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tpay\OpenApi\Utilities\Logger;

final class TsSyliusTpayPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function boot()
    {
        Logger::disableLogging();
    }
}
