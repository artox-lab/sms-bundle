<?php

/**
 * Slack provider
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\Provider;

use ArtoxLab\Bundle\SmsBundle\Exception\SlackException;
use ArtoxLab\Bundle\SmsBundle\Provider\Traits\RetryApiCallTrait;
use ArtoxLab\Bundle\SmsBundle\Sms\SmsInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class SlackProvider implements ProviderInterface
{

    use RetryApiCallTrait;

    public const API_URL = 'https://slack.com/api/chat.postMessage';

    protected const API_CALL_RETRIES = 3;

    /**
     * Authentication token bearing required scopes
     *
     * @var string
     */
    private $token;

    /**
     * Channel, private group, or IM channel to send message to
     *
     * @link https://api.slack.com/methods/chat.postMessage#channels
     *
     * @var string
     */
    private $channel;

    /**
     * Http client
     *
     * @var ClientInterface
     */
    private $client;

    /**
     * SlackProvider constructor.
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
     * Authentication token bearing required scopes
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set authentication token
     *
     * @param string $token Authentication token
     *
     * @return $this
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Channel, private group, or IM channel to send message to
     *
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * Set channel
     *
     * @param string $channel Channel
     *
     * @return $this
     */
    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Build request headers
     *
     * @return array
     */
    public function buildRequestHeaders(): array
    {
        return [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $this->getToken(),
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
            'channel' => $this->getChannel(),
            'text'    => sprintf(
                'Recipient: `%s`, message: `%s`',
                $sms->getPhoneNumber(),
                $sms->getMessage()
            ),
        ];
    }

    /**
     * Send message
     *
     * @param SmsInterface $sms Sms message
     *
     * @throws GuzzleException
     * @throws SlackException
     *
     * @return ResponseInterface|null
     */
    public function send(SmsInterface $sms): ?ResponseInterface
    {
        try {
            $response     = $this->client->request(
                'POST',
                self::API_URL,
                [
                    'headers' => $this->buildRequestHeaders(),
                    'json'    => $this->buildRequestBody($sms),
                ]
            );
            $jsonResponse = json_decode($response->getBody()->getContents(), true);

            if (true !== $jsonResponse['ok']) {
                throw new SlackException(json_encode($jsonResponse));
            }
        } catch (Throwable $e) {
            throw new SlackException($e->getMessage());
        }

        return $response;
    }

}
