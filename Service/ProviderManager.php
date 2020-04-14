<?php

/**
 * Provider manager
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\Service;

use ArtoxLab\Bundle\SmsBundle\Provider\ProviderInterface;
use OutOfBoundsException;

class ProviderManager
{
    /**
     * Map of providers
     *
     * @var ProviderInterface[]
     */
    protected $providers;

    /**
     * Add provider
     *
     * @param string            $providerName Provider name
     * @param ProviderInterface $provider     Provider
     *
     * @return void
     */
    public function addProvider(string $providerName, ProviderInterface $provider): void
    {
        $this->providers[$providerName] = $provider;
    }

    /**
     * Get provider
     *
     * @param string $providerName Provider name
     *
     * @return ProviderInterface
     */
    public function getProvider(string $providerName): ProviderInterface
    {
        if (false === isset($this->providers[$providerName])) {
            throw new OutOfBoundsException(sprintf('Could not find provider "%s"', $providerName));
        }

        return $this->providers[$providerName];
    }

}
