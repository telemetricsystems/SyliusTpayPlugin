<?php

declare(strict_types=1);

namespace Ts\SyliusTpayPlugin\Action;

use ArrayAccess;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Notify;

final class NotifyAction implements ActionInterface, ApiAwareInterface
{
    use GatewayAwareTrait, ApiAwareTrait;

    public function execute($request): void
    {
        //TO DO!!!
        dd($request);
    }

    public function supports($request): bool
    {
        return $request instanceof Notify && $request->getModel() instanceof ArrayAccess;
    }
}
