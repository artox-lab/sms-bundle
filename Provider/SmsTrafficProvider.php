<?php

/**
 * SmsTrafficProvider provider
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\Provider;

use ArtoxLab\Bundle\SmsBundle\Exception\SmsTrafficException;
use ArtoxLab\Bundle\SmsBundle\Provider\Traits\RetryApiCallTrait;
use ArtoxLab\Bundle\SmsBundle\Sms\SmsInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class SmsTrafficProvider implements ProviderInterface
{
    use RetryApiCallTrait;

    public const API_URL = 'https://api.smstraffic.ru';

    public const REQUEST_TIMEOUT = 120;

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
     * @return array
     */
    public function buildRequestBody(SmsInterface $sms): array
    {
        return [
            'login'      => $this->getLogin(),
            'password'   => $this->getPassword(),
            'phones'     => preg_replace('/[^\d]/', '', $sms->getPhoneNumber()),
            'message'    => $sms->getMessage(),
            'rus'        => 0,
            'originator' => $this->getSender(),
        ];
    }

    /**
     * Send message
     *
     * @param SmsInterface $sms Sms message
     *
     * @throws GuzzleException
     * @throws SmsTrafficException
     *
     * @return ResponseInterface|null
     */
    public function send(SmsInterface $sms): ?ResponseInterface
    {
        try {
            $requestUrl = sprintf('%s/multi.php', self::API_URL);

            $response = $this->client->request(
                'POST',
                $requestUrl,
                [
                    'form_params' => $this->buildRequestBody($sms),
                    'timeout'     => self::REQUEST_TIMEOUT,
                ]
            );

            $xmlResponse = $response->getBody()->getContents();

            if (200 !== $response->getStatusCode()
                || false === strpos($xmlResponse, '<result>OK</result>')
            ) {
                throw new SmsTrafficException(json_encode($xmlResponse));
            }
        } catch (Throwable $e) {
            throw new SmsTrafficException($e->getMessage());
        }

        return $response;
    }

}
