<?php

/**
 * Compiler pass of bundle
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\DependencyInjection\Compiler;

use ArtoxLab\Bundle\SmsBundle\DependencyInjection\Factory\Provider\AbstractProviderFactory;
use ArtoxLab\Bundle\SmsBundle\Service\ProviderManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ProviderCompilerPass implements CompilerPassInterface
{

    /**
     * Code execution at compile time
     *
     * @param ContainerBuilder $container Container Builder
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->has(ProviderManager::class)) {
            return;
        }

        $manager   = $container->findDefinition(ProviderManager::class);
        $providers = $container->findTaggedServiceIds(AbstractProviderFactory::SERVICE_TAG);

        foreach ($providers as $id => $tags) {
            foreach ($tags as $attribute) {
                $manager->addMethodCall(
                    'addProvider',
                    [
                        $attribute['provider'],
                        new Reference($id),
                    ]
                );
            }
        }
    }

}
