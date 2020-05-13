<?php

/**
 * SmsLine provider
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\Provider;

use ArtoxLab\Bundle\SmsBundle\Exception\SmsLineException;
use ArtoxLab\Bundle\SmsBundle\Provider\Traits\RetryApiCallTrait;
use ArtoxLab\Bundle\SmsBundle\Sms\SmsInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;

class SmsLineProvider implements ProviderInterface
{
    use RetryApiCallTrait;

    public const API_URL = 'https://api.smsline.by';

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
     * Generate request signature
     *
     * @param string $string Signature string
     *
     * @return string
     */
    public function getHash(string $string): string
    {
        return hash_hmac('sha256', $string, $this->getPassword());
    }

    /**
     * Build request headers
     *
     * @param string $signature Token
     *
     * @return array
     */
    public function buildRequestHeaders(string $signature): array
    {
        return [
            'Content-Type'       => 'application/json',
            'Authorization-User' => $this->getLogin(),
            'Authorization'      => 'Bearer ' . $signature,
        ];
    }

    /**
     * Build request body
     *
     * @param SmsInterface $sms Sms message
     *
     * @return array
     */
    public function buildRequestBody(SmsInterface $sms): array
    {
        return [
            'target' => $this->getSender(),
            'msisdn' => preg_replace('/[^\d]/', '', $sms->getPhoneNumber()),
            'text'   => $sms->getMessage(),
        ];
    }

    /**
     * Send message
     *
     * @param SmsInterface $sms Sms message
     *
     * @throws GuzzleException
     * @throws SmsLineException
     *
     * @return ResponseInterface|null
     */
    public function send(SmsInterface $sms): ?ResponseInterface
    {
        $requestUrl  = sprintf('%s/v3/messages/single/sms', self::API_URL);
        $requestBody = $this->buildRequestBody($sms);
        $requestBody = json_encode($requestBody);
        $signature   = $this->getHash('messagessinglesms' . $requestBody);

        $response     = $this->client->request(
            'POST',
            $requestUrl,
            [
                'headers' => $this->buildRequestHeaders($signature),
                'body'    => $requestBody,
            ]
        );
        $jsonResponse = json_decode($response->getBody()->getContents(), true);

        if (200 !== $response->getStatusCode()) {
            throw new SmsLineException(json_encode($jsonResponse));
        }

        return $response;
    }

}
