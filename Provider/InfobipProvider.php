<?php

/**
 * Infobip provider
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\Provider;

use ArtoxLab\Bundle\SmsBundle\Exception\InfobipException;
use ArtoxLab\Bundle\SmsBundle\Provider\Traits\RetryApiCallTrait;
use ArtoxLab\Bundle\SmsBundle\Sms\SmsInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;

class InfobipProvider implements ProviderInterface
{
    use RetryApiCallTrait;

    public const API_URL = 'https://api.infobip.com';

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
     * InfobipProvider constructor.
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
     * Send message
     *
     * @param SmsInterface $sms Sms message
     *
     * @throws GuzzleException
     * @throws InfobipException
     *
     * @return ResponseInterface|null
     */
    public function send(SmsInterface $sms): ?ResponseInterface
    {
        $requestUrl = sprintf('%s/sms/2/text/advanced', self::API_URL);

        $response     = $this->client->request(
            'POST',
            $requestUrl,
            [
                'auth' => [
                    $this->getLogin(),
                    $this->getPassword(),
                ],
                'json' => [
                    'messages' => [
                        [
                            'from'         => $this->getSender(),
                            'destinations' => [
                                [
                                    'to' => $sms->getPhoneNumber(),
                                ],
                            ],
                            'text'         => $sms->getMessage(),
                        ],
                    ],
                ],
            ]
        );
        $jsonResponse = json_decode($response->getBody()->getContents(), true);

        if (200 !== $response->getStatusCode()) {
            throw new InfobipException(json_encode($jsonResponse));
        }

        return $response;
    }

}
