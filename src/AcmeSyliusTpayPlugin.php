<?php

declare(strict_types=1);

namespace Acme\SyliusTpayPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class AcmeSyliusTpayPlugin extends Bundle
{
    use SyliusPluginTrait;
}
