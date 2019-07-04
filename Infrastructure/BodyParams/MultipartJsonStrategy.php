<?php
/**
 * @see       https://github.com/zendframework/zend-expressive-helpers for the canonical source repository
 * @copyright Copyright (c) 2015-2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive-helpers/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Infrastructure\BodyParams;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Helper\Exception\MalformedRequestBodyException;
use Zend\Expressive\Helper\BodyParams\StrategyInterface;

use function array_shift;
use function explode;
use function json_decode;
use function json_last_error;
use function json_last_error_msg;
use function preg_match;
use function sprintf;
use function trim;

use const JSON_ERROR_NONE;

class MultipartJsonStrategy implements StrategyInterface
{
    public function match(string $contentType) : bool
    {
        return 1 === preg_match('#^multipart/form-data($|[ ;])#', $contentType);
    }

    /**
     * {@inheritDoc}
     *
     * @throws MalformedRequestBodyException
     */
    public function parse(ServerRequestInterface $request) : ServerRequestInterface
    {
        $rawBody = $request->getParsedBody();

        if (empty($rawBody) || empty($rawBody[\Config\AppConstant::RequestDataFieldName])) {
            return $request
                ->withAttribute('rawBody', $rawBody)
                ->withParsedBody(new \stdClass());
        }

        $parsedBody = json_decode((string)$rawBody[\Config\AppConstant::RequestDataFieldName], false);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new MalformedRequestBodyException(sprintf(
                'Error when parsing JSON request body: %s',
                json_last_error_msg()
            ));
        }

        return $request
            ->withAttribute('rawBody', $rawBody[\Config\AppConstant::RequestDataFieldName])
            ->withParsedBody($parsedBody);
    }
}
