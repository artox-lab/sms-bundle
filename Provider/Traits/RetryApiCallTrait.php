<?php

/**
 * Trait: Http client retry decider
 *
 * @author Dmitry Meliukh <d.meliukh@artox.com>
 */

declare(strict_types=1);

namespace ArtoxLab\Bundle\SmsBundle\Provider\Traits;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Psr7\Response as Psr7Response;

trait RetryApiCallTrait
{

    /**
     * Http client retry decider
     *
     * @param int                   $retries   Число попыток
     * @param Psr7Request           $request   Объект запроса
     * @param Psr7Response|null     $response  Объект ответа
     * @param RequestException|null $exception Исключение
     *
     * @return bool
     */
    public function isNeedToRetryApiCall(
        int $retries,
        Psr7Request $request,
        Psr7Response $response = null,
        RequestException $exception = null
    ): bool {
        if ($retries >= static::API_CALL_RETRIES) {
            return false;
        }

        if (null === $response) {
            return true;
        }

        if (200 !== $response->getStatusCode()) {
            return true;
        }

        return false;
    }

}
