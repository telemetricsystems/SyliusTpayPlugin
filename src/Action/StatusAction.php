<?php

declare(strict_types=1);

namespace Ts\SyliusTpayPlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetStatusInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Ts\SyliusTpayPlugin\Payum\SyliusApi;

final class StatusAction implements ActionInterface
{
    use GatewayAwareTrait, ApiAwareTrait;

    public function execute($request): void
    {
        /** @var $request GetStatusInterface */
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getFirstModel();

        $status = $payment->getDetails()['status'] ?? null;

        if (SyliusApi::PAYMENT_STATUS_PENDING === $status) {
            $request->markPending();

            return;
        }

        if (SyliusApi::PAYMENT_STATUS_CORRECT === $status) {
            $request->markCaptured();

            return;
        }

        if (SyliusApi::PAYMENT_STATUS_FAILURE === $status) {
            $request->markFailed();

            return;
        }

        $request->markUnknown();
    }

    public function supports($request): bool
    {
        return $request instanceof GetStatusInterface && $request->getFirstModel() instanceof PaymentInterface;
    }
}
