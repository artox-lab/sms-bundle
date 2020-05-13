<?php

/**
 * Interface: provider
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\Provider;

use ArtoxLab\Bundle\SmsBundle\Sms\SmsInterface;
use Psr\Http\Message\ResponseInterface;

interface ProviderInterface
{

    /**
     * Send message
     *
     * @param SmsInterface $sms Sms message
     *
     * @return ResponseInterface|null
     */
    public function send(SmsInterface $sms): ?ResponseInterface;

}
