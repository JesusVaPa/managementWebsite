<?php
declare(strict_types=1);

namespace GivenCode\Exceptions;

use Throwable;

class RequestException extends RuntimeException
{
    private int $httpResponseCode;
    private array $httpHeaders;

    /**
     * @param string $message
     * @param int $httpResponseCode
     * @param array $httpHeaders
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $httpResponseCode = 500, array $httpHeaders = [],
                                int    $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->httpResponseCode = $httpResponseCode;
        $this->httpHeaders = $httpHeaders;
    }

    /**
     * TODO: Function documentation
     *
     * @return int
     */
    public function getHttpResponseCode(): int
    {
        return $this->httpResponseCode;
    }

    /**
     * TODO: Function documentation
     *
     * @return array
     */
    public function getHttpHeaders(): array
    {
        return $this->httpHeaders;
    }

    /**
     * TODO: Function documentation
     *
     * @param string $headerKey
     * @param string $headerValue
     * @return void
     */
    public function addHeader(string $headerKey, string $headerValue): void
    {
        $this->httpHeaders[$headerKey] = $headerValue;
    }

}