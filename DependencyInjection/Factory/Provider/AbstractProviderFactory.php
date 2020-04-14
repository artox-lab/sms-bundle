<?php

/**
 * Factory: Abstract provider
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\DependencyInjection\Factory\Provider;

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class AbstractProviderFactory implements ProviderFactoryInterface
{
    public const SERVICE_TAG = 'artox_lab_sms.provider';

    /**
     * Set definition of provider
     *
     * @param ContainerBuilder $containerBuilder   Container Builder
     * @param string           $providerName       Name of provider
     * @param ChildDefinition  $providerDefinition Definition of provider
     *
     * @return void
     */
    public function setProviderDefinition(
        ContainerBuilder $containerBuilder,
        string $providerName,
        ChildDefinition $providerDefinition
    ): void {
        $providerDefinition->addTag(self::SERVICE_TAG, ['provider' => $providerName]);
        $providerId = sprintf('%s.%s', self::SERVICE_TAG, $providerName);

        $containerBuilder->setDefinition($providerId, $providerDefinition);
    }

}
