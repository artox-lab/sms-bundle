<?php

/**
 * Extension of an ArtoxLabSmsExtension
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\DependencyInjection;

use ArtoxLab\Bundle\SmsBundle\DependencyInjection\Factory\Provider\ProviderFactoryInterface;
use Exception;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ArtoxLabSmsExtension extends Extension
{
    /**
     * Map of providers
     *
     * @var ProviderFactoryInterface[]
     */
    private $providerFactoryMap = [];

    /**
     * Add provider on map
     *
     * @param ProviderFactoryInterface $providerFactory Provider
     *
     * @return void
     */
    public function addProviderFactory(ProviderFactoryInterface $providerFactory): void
    {
        $this->providerFactoryMap[$providerFactory->getName()] = $providerFactory;
    }

    /**
     * Returns extension configuration
     *
     * @param array            $config    Config
     * @param ContainerBuilder $container Container Builder
     *
     * @return ConfigurationInterface|null
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration($this->providerFactoryMap);
    }

    /**
     * Returns the recommended alias to use in XML
     *
     * @return string
     */
    public function getAlias()
    {
        return 'artox_lab_sms';
    }

    /**
     * Loads a specific configuration.
     *
     * @param array            $configs   Configs
     * @param ContainerBuilder $container Container Builder
     *
     * @throws Exception
     *
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration($this->getConfiguration($configs, $container), $configs);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');

        $this->loadProviders($config['providers'], $container);
    }

    /**
     * Setting up configuration
     *
     * @param array            $config    Config
     * @param ContainerBuilder $container Container Builder
     *
     * @return void
     */
    private function loadProviders(array $config, ContainerBuilder $container): void
    {
        foreach ($config as $providerName => $providerConfig) {
            $factoryName = key($providerConfig);
            $factory     = $this->providerFactoryMap[$factoryName];
            $definition  = $factory->getDefinition($providerConfig[$factoryName]);

            $factory->setProviderDefinition($container, $providerName, $definition);
        }
    }

}
