<?php 

declare(strict_types=1);

namespace Infrastructure\Validator;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ValidatorRequestInterface {    
    public function valid(ServerRequestInterface $request, ResponseInterface & $response) : bool;
}