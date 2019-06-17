<?php 

declare(strict_types=1);

namespace Infrastructure\Validator;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ValidatorAdapterInterface {
    public function isHandleValid($routerName, $request) : bool;
    public function valid(ServerRequestInterface $request, ResponseInterface & $messageResponse): bool;
}