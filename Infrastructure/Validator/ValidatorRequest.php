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

    public function __construct(array $adapters = [])
    {
        $this->adapters = $adapters;
    }

    
    public function valid(ServerRequestInterface $request, ResponseInterface & $messageResponse): bool
    {
        foreach($this->adapters as $adapter) {
            if ($adapter->isHandle($request)) {
                return $adapter->valid($request, $messageResponse);
            }
        }

        return true;
    }
}