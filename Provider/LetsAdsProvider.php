<?php

/**
 * LetsAds provider
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\Provider;

use ArtoxLab\Bundle\SmsBundle\Exception\LetsAdsException;
use ArtoxLab\Bundle\SmsBundle\Provider\Traits\RetryApiCallTrait;
use ArtoxLab\Bundle\SmsBundle\Sms\SmsInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class LetsAdsProvider implements ProviderInterface
{
    use RetryApiCallTrait;

    public const API_URL = 'http://letsads.com/api';

    protected const API_CALL_RETRIES = 3;

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
     * Http client
     *
     * @var ClientInterface
     */
    private $client;

    /**
     * SmsLineProvider constructor.
     */
    public function __construct()
    {
        $handlers = HandlerStack::create();
        $handlers->push(
            Middleware::retry([$this, 'isNeedToRetryApiCall'])
        );

        $this->client = new Client(['handler' => $handlers]);
    }

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
     * Build request body
     *
     * @param SmsInterface $sms Sms message
     *
     * @return string
     */
    public function buildRequestBody(SmsInterface $sms): string
    {
        $auth      = '<login>' . $this->getLogin() . '</login><password>' . $this->getPassword() . '</password>';
        $recipient = '<recipient>' . $sms->getPhoneNumber() . '</recipient>';

        $body  = '<?xml version="1.0" encoding="UTF-8"?>';
        $body .= '<request>';
        $body .= '<auth>' . $auth . '</auth>';
        $body .= sprintf(
            '<message><from>%s</from><text>%s</text>%s</message>',
            $this->getSender(),
            $sms->getMessage(),
            $recipient
        );
        $body .= '</request>';

        return $body;
    }

    /**
     * Send message
     *
     * @param SmsInterface $sms Sms message
     *
     * @throws GuzzleException
     * @throws LetsAdsException
     *
     * @return ResponseInterface|null
     */
    public function send(SmsInterface $sms): ?ResponseInterface
    {
        try {
            $requestBody = $this->buildRequestBody($sms);

            $response = $this->client->request(
                'POST',
                self::API_URL,
                ['body' => $requestBody]
            );

            $xmlResponse = $response->getBody()->getContents();

            if (200 !== $response->getStatusCode()) {
                throw new LetsAdsException(json_encode($xmlResponse));
            }
        } catch (Throwable $e) {
            throw new LetsAdsException($e->getMessage());
        }

        return $response;
    }

}
