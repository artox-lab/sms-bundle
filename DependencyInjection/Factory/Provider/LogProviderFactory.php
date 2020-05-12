<?php

/**
 * Factory: Log
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\DependencyInjection\Factory\Provider;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;

class LogProviderFactory extends AbstractProviderFactory
{

    /**
     * Name of provider
     *
     * @return string
     */
    public function getName(): string
    {
        return 'log';
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
        return (new ChildDefinition('artox_lab_sms.prototype.provider.log'));
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
    }

}
