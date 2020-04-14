<?php

/**
 * Configuration of bundle
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\DependencyInjection;

use ArtoxLab\Bundle\SmsBundle\DependencyInjection\Factory\Provider\ProviderFactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use function method_exists;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Map of providers factory
     *
     * @var ProviderFactoryInterface[]
     */
    private $providerFactoryMap;

    /**
     * Configuration constructor.
     *
     * @param array $providerFactoryMap Map of providers factory
     */
    public function __construct(array $providerFactoryMap)
    {
        $this->providerFactoryMap = $providerFactoryMap;
    }

    /**
     * Generates the configuration tree builder
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('artox_lab_sms');
        if (true === method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older.
            $rootNode = $treeBuilder->root('artox_lab_sms');
        }

        $this->buildProviderConfiguration($rootNode);

        return $treeBuilder;
    }

    /**
     * Build provider configuration
     *
     * @param ArrayNodeDefinition $nodeDefinition Array node
     *
     * @return void
     */
    private function buildProviderConfiguration(ArrayNodeDefinition $nodeDefinition): void
    {
        $nd = $nodeDefinition
            ->fixXmlConfig('provider', 'providers')
            ->children()
            ->arrayNode('providers')
            ->arrayPrototype()
            ->performNoDeepMerging();

        foreach ($this->providerFactoryMap as $providerName => $providerFactory) {
            $providerFactory->buildConfiguration(
                $nd->children()->arrayNode($providerName)
            );
        }
    }

}
