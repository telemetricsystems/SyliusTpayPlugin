<?php

declare(strict_types=1);

namespace Acme\SyliusTpayPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class SyliusGatewayConfigurationType extends AbstractType
{
    public const SANDBOX_ENVIRONMENT = 'sandbox';
    public const SECURE_ENVIRONMENT = 'secure';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'environment',
                ChoiceType::class,
                [
                    'label' => 'ts_sylius_tpay_plugin.form.environment.label',
                    'choices' => [
                        'ts_sylius_tpay_plugin.form.environment.secure' => self::SECURE_ENVIRONMENT,
                        'ts_sylius_tpay_plugin.form.environment.sandbox' => self::SANDBOX_ENVIRONMENT,
                    ],
                ]
            )
            ->add(
                'pos_id',
                TextType::class,
                [
                    'label' => 'ts_sylius_tpay_plugin.form.pos_id.label',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'ts_sylius_tpay_plugin.form.pos_id.not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'oauth_client_id',
                TextType::class,
                [
                    'label' => 'ts_sylius_tpay_plugin.form.oauth_client_id.label',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'ts_sylius_tpay_plugin.form.oauth_client_id.not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                ]
            )->add(
                'oauth_client_secret',
                TextType::class,
                [
                    'label' => 'ts_sylius_tpay_plugin.form.oauth_client_secret.label',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'ts_sylius_tpay_plugin.form.oauth_client_secret.not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                ]
            );
    }
}
