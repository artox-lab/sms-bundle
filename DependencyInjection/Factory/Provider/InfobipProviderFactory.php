<?php

/**
 * Factory: Infobip
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\DependencyInjection\Factory\Provider;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;

class InfobipProviderFactory extends AbstractProviderFactory
{

    /**
     * Name of provider
     *
     * @return string
     */
    public function getName(): string
    {
        return 'infobip';
    }

    /**
     * Definition of provider
     *
     * @param array $config Configuration
     *
     * @return ChildDefinition
     */
    public function getDefinition(array $config): ChildDefinition
    {
        return (new ChildDefinition('artox_lab_sms.prototype.provider.infobip'))
            ->addMethodCall('setToken', [$config['token']])
            ->addMethodCall('setSender', [$config['sender']]);
    }

    /**
     * Build configuration
     *
     * @param ArrayNodeDefinition $arrayNodeDefinition Array node
     *
     * @return void
     */
    public function buildConfiguration(ArrayNodeDefinition $arrayNodeDefinition): void
    {
        $arrayNodeDefinition
            ->children()
            ->scalarNode('token')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('sender')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->end();
    }

}
