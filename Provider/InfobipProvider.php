<?php

/**
 * Infobip provider
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\Provider;

use ArtoxLab\Bundle\SmsBundle\Exception\InfobipException;
use ArtoxLab\Bundle\SmsBundle\Sms\SmsInterface;
use Exception;
use infobip\api\client\SendSingleTextualSms;
use infobip\api\configuration\BasicAuthConfiguration;
use infobip\api\model\sms\mt\send\textual\SMSTextualRequest;

class InfobipProvider implements ProviderInterface
{

    /**
     * Login
     *
     * @var string
     */
    private $login;

    /**
     * Password
     *
     * @var string
     */
    private $password;

    /**
     * Sender
     *
     * @var string
     */
    private $sender;

    /**
     * Set login
     *
     * @param string $login Login
     *
     * @return $this
     */
    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Login of provider
     *
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password Password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Password of provider
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set sender
     *
     * @param string $sender Sender
     *
     * @return $this
     */
    public function setSender(string $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Sender
     *
     * @return string
     */
    public function getSender(): string
    {
        return $this->sender;
    }

    /**
     * Send message
     *
     * @param SmsInterface $sms Sms message
     *
     * @throws InfobipException
     *
     * @return bool
     */
    public function send(SmsInterface $sms): bool
    {
        try {
            $auth = new BasicAuthConfiguration($this->getLogin(), $this->getPassword());

            $client = new SendSingleTextualSms($auth);

            $requestBody = new SMSTextualRequest();
            $requestBody->setFrom($this->getSender());
            $requestBody->setTo([$sms->getPhoneNumber()]);
            $requestBody->setText($sms->getMessage());

            $client->execute($requestBody);
        } catch (Exception $e) {
            throw new InfobipException($e->getMessage());
        }

        return true;
    }

}
