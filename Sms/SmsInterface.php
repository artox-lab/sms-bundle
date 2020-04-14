<?php

/**
 * Inteface: SMS message
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\Sms;

interface SmsInterface
{

    /**
     * Returns message
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Returns phone
     *
     * @return string
     */
    public function getPhoneNumber(): string;

}
