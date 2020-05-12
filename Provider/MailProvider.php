<?php

/**
 * Mail provider
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\Provider;

use ArtoxLab\Bundle\SmsBundle\Sms\SmsInterface;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class MailProvider implements ProviderInterface
{

    /**
     * Host of mailer
     *
     * @var string
     */
    private $host;

    /**
     * Port of mailer
     *
     * @var integer
     */
    private $port;

    /**
     * Encryption type
     *
     * @var string|null
     */
    private $encryption;

    /**
     * Username
     *
     * @var string
     */
    private $username = '';

    /**
     * Password
     *
     * @var string
     */
    private $password = '';

    /**
     * Sender
     *
     * @var string
     */
    private $sender;

    /**
     * Recipients of message
     *
     * @var string
     */
    private $recipients;

    /**
     * Mailer
     *
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * MailProvider constructor.
     */
    public function __construct()
    {
        $transport = new Swift_SmtpTransport(
            $this->getHost(),
            $this->getPort(),
            $this->getEncryption()
        );
        $transport->setUsername($this->getUsername());
        $transport->setPassword($this->getPassword());

        $this->mailer = new Swift_Mailer($transport);
    }

    /**
     * Host of mailer
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Host of mailer
     *
     * @param string $host Host
     *
     * @return $this
     */
    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Username of mailer
     *
     * @param string $username Username
     *
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Password of mailer
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
     * Sender
     *
     * @return string
     */
    public function getSender(): string
    {
        return $this->sender;
    }

    /**
     * Sender
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
     * Recipients of message
     *
     * @return array
     */
    public function getRecipients(): array
    {
        return explode(
            ',',
            $this->recipients
        );
    }

    /**
     * Recipients of message
     *
     * @param string $recipients Recipients of message
     *
     * @return $this
     */
    public function setRecipients(string $recipients): self
    {
        $this->recipients = $recipients;

        return $this;
    }

    /**
     * Port of mailer
     *
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * Port of mailer
     *
     * @param int $port Port of mailer
     *
     * @return $this
     */
    public function setPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Encryption type
     *
     * @return string|null
     */
    public function getEncryption(): ?string
    {
        return $this->encryption;
    }

    /**
     * Encryption type
     *
     * @param string|null $encryption Encryption type
     *
     * @return $this
     */
    public function setEncryption($encryption): self
    {
        $this->encryption = $encryption;

        return $this;
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
        $message = (new Swift_Message())
            ->setFrom($this->getSender())
            ->setTo($this->getRecipients())
            ->setSubject('New message from sms-bundle')
            ->setBody(
                sprintf(
                    '<p>Recipient: %s</p><p>Message: %s</p>',
                    $sms->getPhoneNumber(),
                    $sms->getMessage()
                ),
                'text/html'
            );

        $this->mailer->send($message);

        return true;
    }

}
