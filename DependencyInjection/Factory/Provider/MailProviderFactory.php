<?php

/**
 * Factory: Mail
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\DependencyInjection\Factory\Provider;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;

class MailProviderFactory extends AbstractProviderFactory
{

    /**
     * Name of provider
     *
     * @return string
     */
    public function getName(): string
    {
        return 'mail';
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
        return (new ChildDefinition('artox_lab_sms.prototype.provider.mail'))
            ->addMethodCall('setHost', [$config['host']])
            ->addMethodCall('setPort', [$config['port']])
            ->addMethodCall('setEncryption', [$config['encryption']])
            ->addMethodCall('setUsername', [$config['username']])
            ->addMethodCall('setPassword', [$config['password']])
            ->addMethodCall('setSender', [$config['sender']])
            ->addMethodCall('setRecipients', [$config['recipients']]);
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
            ->scalarNode('host')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->integerNode('port')
            ->isRequired()
            ->end()
            ->scalarNode('encryption')
            ->defaultNull()
            ->end()
            ->scalarNode('username')
            ->end()
            ->scalarNode('password')
            ->end()
            ->scalarNode('sender')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('recipients')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->end();
    }

}
