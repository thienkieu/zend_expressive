<?php 

declare(strict_types=1);

namespace Infrastructure;

use Zend\Diactoros\Response\JsonResponse;

class CommonFunction
{
    public static function buildResponseFormat($status, $messages=[], $data = null) {
        if ($data === null) $data = new \stdClass();
        
        return new JsonResponse([
            'isSuccess' => $status,      
            'messages'  => $messages,
            'data' => $data
        ]);
    }
}