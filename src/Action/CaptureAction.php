<?php

declare(strict_types=1);

namespace Ts\SyliusTpayPlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\Capture;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Payum\Core\Security\TokenInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Tpay\OpenApi\Api\TpayApi;
use Tpay\OpenApi\Utilities\TpayException;
use Ts\SyliusTpayPlugin\Payum\SyliusApi;
use Webmozart\Assert\Assert;

final class CaptureAction implements ActionInterface, ApiAwareInterface, GenericTokenFactoryAwareInterface, GatewayAwareInterface
{
    use GatewayAwareTrait, ApiAwareTrait;

    private ?GenericTokenFactoryInterface $tokenFactory;

    public function execute($request): void
    {
        /** @var $request GetStatusInterface */
        RequestNotSupportedException::assertSupports($this, $request);

        $tpayApi = new TpayApi($this->api->getOauthClientId(), $this->api->getOauthClientSecret(), $this->api->isProductionMode(), 'read');

        /** @var TokenInterface $token */
        $token = $request->getToken();

        /** @var PaymentInterface $payment */
        $payment = $request->getModel();

        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        $paymentDetails = $payment->getDetails();

        $tpayResponse = isset($paymentDetails['transactionId']) ?
            $tpayApi->transactions()?->getTransactionById($paymentDetails['transactionId']) :
            $tpayApi->transactions()?->createTransaction($this->getRequestBody($token, $order));

        if (null !== $tpayResponse && 'success' === $tpayResponse['result']) {
            $paymentDetails = array_merge($paymentDetails, $tpayResponse);
            $payment->setDetails($paymentDetails);
            $request->setModel($payment);

            if (SyliusApi::PAYMENT_STATUS_CORRECT === $paymentDetails['status']) {
                return;
            }

            if (isset($paymentDetails['transactionPaymentUrl'])) {
                throw new HttpRedirect($paymentDetails['transactionPaymentUrl']);
            }
        }

        throw new TpayException('Unable to create transaction. Response: ' . json_encode($tpayResponse));
    }

    protected function getRequestBody(TokenInterface $token, OrderInterface $order): array
    {
        $customer = $order->getCustomer();

        Assert::isInstanceOf(
            $customer,
            CustomerInterface::class,
            sprintf('Make sure the first model is the %s instance.', CustomerInterface::class)
        );

        $notifyToken = $this->tokenFactory?->createNotifyToken($token->getGatewayName(), $token->getDetails());

        return [
            'amount' => $order->getTotal() / 100,
            'description' => 'Zamówienie ' . $order->getNumber(),
            'hiddenDescription' => 'order_' . $order->getId(),
            'lang' => $this->getFallbackLocaleCode($order->getLocaleCode() ?? 'pl_PL'),
            'payer' => [
                'name' => $customer->getFullName(),
                'email' => $customer->getEmail(),
            ],
            'callbacks' => [
                'notification' => [
                    'url' => $notifyToken?->getTargetUrl(),
                ],
                'payerUrls' => [
                    'success' => $token->getTargetUrl(),
                    'error' => $token->getTargetUrl(),
                ],
            ],
            'pay' => [
                'groupId' => 150,
            ],
        ];
    }

    public function supports($request): bool
    {
        return $request instanceof Capture && $request->getModel() instanceof PaymentInterface;
    }

    public function setGenericTokenFactory(GenericTokenFactoryInterface $genericTokenFactory = null): void
    {
        $this->tokenFactory = $genericTokenFactory;
    }

    protected function getFallbackLocaleCode(string $localeCode): string
    {
        return strtolower(explode('_', $localeCode)[0]);
    }
}
