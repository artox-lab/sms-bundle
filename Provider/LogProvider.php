<?php

/**
 * Log provider
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\Provider;

use ArtoxLab\Bundle\SmsBundle\Sms\SmsInterface;
use Psr\Log\LoggerInterface;

class LogProvider implements ProviderInterface
{

    /**
     * Logger
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * LogProvider constructor.
     *
     * @param LoggerInterface $logger Logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Send message
     *
     * @param SmsInterface $sms Sms message
     *
     * @return bool
     */
    public function send(SmsInterface $sms): bool
    {
        $this->logger->info(
            sprintf(
                'Recipient: %s, message: %s',
                $sms->getPhoneNumber(),
                $sms->getMessage()
            )
        );

        return true;
    }

}
