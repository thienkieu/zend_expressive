<?php
namespace Infrastructure\Listenner;

use Exception;
use Infrastructure\Services\Interfaces\LogInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class LogErrorListenner
{
    /**
     * Log format for messages:
     *
     * STATUS [METHOD] path: message
     */
    const LOG_FORMAT = '%d [%s] %s: %s : %s';

    private $logger;

    public function __construct(LogInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Throwable $error, ServerRequestInterface $request, ResponseInterface $response)
    {
        $content = sprintf(
            self::LOG_FORMAT,
            $response->getStatusCode(),
            (string)$response->getBody(),
            $request->getMethod(),
            (string) $request->getUri(),
            $error->getMessage(),
            $error->getTrace()
        );
        $this->logger->writeLog($content);
    }
}