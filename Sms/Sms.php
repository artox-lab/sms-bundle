<?php

/**
 * SMS message class
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\Sms;

class Sms implements SmsInterface
{

    /**
     * Phone number
     *
     * @var string
     */
    private $phoneNumber;

    /**
     * Message
     *
     * @var string
     */
    private $message;

    /**
     * Sms constructor.
     *
     * @param string $phoneNumber Phone number
     * @param string $message     Message
     */
    public function __construct(string $phoneNumber, string $message)
    {
        $this->setPhoneNumber($phoneNumber);
        $this->setMessage($message);
    }

    /**
     * Returns message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set message
     *
     * @param string $message Message
     *
     * @return Sms
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Returns phone
     *
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * Set phone
     *
     * @param string $phoneNumber Phone
     *
     * @return Sms
     */
    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

}
