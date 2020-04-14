<?php

/**
 * Interface: provider
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\DependencyInjection\Factory\Provider;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ProviderFactoryInterface
{

    /**
     * Name of provider
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Definition of provider
     *
     * @param array $config Configuration
     *
     * @return ChildDefinition
     */
    public function getDefinition(array $config): ChildDefinition;

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
    ): void;

    /**
     * Build configuration
     *
     * @param ArrayNodeDefinition $arrayNodeDefinition Array node
     *
     * @return void
     */
    public function buildConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void;

}
