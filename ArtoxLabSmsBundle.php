<?php

/**
 * Symfony bundle to send sms
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle;

use ArtoxLab\Bundle\SmsBundle\DependencyInjection\Compiler\ProviderCompilerPass;
use ArtoxLab\Bundle\SmsBundle\DependencyInjection\ArtoxLabSmsExtension;
use ArtoxLab\Bundle\SmsBundle\DependencyInjection\Factory\Provider\InfobipProviderFactory;
use ArtoxLab\Bundle\SmsBundle\DependencyInjection\Factory\Provider\LetsAdsProviderFactory;
use ArtoxLab\Bundle\SmsBundle\DependencyInjection\Factory\Provider\SmsLineProviderFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ArtoxLabSmsBundle extends Bundle
{

    /**
     * Builds the bundle
     *
     * @param ContainerBuilder $container Container Builder
     *
     * @return void
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ProviderCompilerPass());
    }

    /**
     * Returns the bundle's container extension
     *
     * @return ExtensionInterface|null
     */
    public function getContainerExtension()
    {
        $extension = new ArtoxLabSmsExtension();
        $extension->addProviderFactory(new SmsLineProviderFactory());
        $extension->addProviderFactory(new LetsAdsProviderFactory());
        $extension->addProviderFactory(new InfobipProviderFactory());

        return $extension;
    }

}
