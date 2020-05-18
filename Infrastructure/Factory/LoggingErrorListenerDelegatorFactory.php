<?php
namespace Infrastructure\Factory;

use Psr\Container\ContainerInterface;
use Infrastructure\Services\Interfaces\LogInterface;
use Zend\Stratigility\Middleware\ErrorHandler;
use Infrastructure\Listenner\LogErrorListenner;

class LoggingErrorListenerDelegatorFactory
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback) : ErrorHandler
    {

        $listener = new LogErrorListenner($container->get(LogInterface::class));
        $errorHandler = $callback();
        $errorHandler->attachListener($listener);
        return $errorHandler;
    }
}