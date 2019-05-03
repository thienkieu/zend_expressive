<?php 

declare(strict_types=1);

namespace Infrastructure\Validator;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class ValidatorRequest implements ValidatorRequestInterface{
    
    /**
     * @var $adapters ValidatorAdapterInterface[]
     */
    private  $adapters;

    private $container;

    public function __construct($container, array $adapters = [])
    {
        $this->container = $container;
        $this->adapters = $adapters;
    }
    
    public function valid(ServerRequestInterface $request, ResponseInterface & $messageResponse): bool
    {
        foreach($this->adapters as $adapter) {
            $adapterInstance = new $adapter($this->container);
            if ($adapterInstance->isHandle($request)) {
                return $adapterInstance->valid($request, $messageResponse);
            }
        }

        return true;
    }
}