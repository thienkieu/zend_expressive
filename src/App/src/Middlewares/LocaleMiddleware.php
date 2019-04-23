<?php
namespace App\Middlewares;

use Locale;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Helper\UrlHelper;

class LocaleMiddleware implements MiddlewareInterface
{
    private $helper;

    private $defaultLocale;
    private $fallbackLocale = 'en_US';

    const REGEX_LOCALE = '#^/(?P<locale>[a-z]{2,3}|[a-z]{2}[-_][a-zA-Z]{2})(?:/|$)#';

    public function __construct(UrlHelper $helper, string $defaultLocale = null)
    {
        $this->helper = $helper;
        if ($defaultLocale) {
            $this->defaultLocale = $defaultLocale;
        }
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        echo 'sdfsdfsdfs4535435';die;
        
        $uri = $request->getUri();

        $path = $uri->getPath();

        if (! preg_match(self::REGEX_LOCALE, $path, $matches)) {
            Locale::setDefault($this->defaultLocale ?: $this->fallbackLocale);
            return $handler->handle($request);
        }

        $locale = $matches['locale'];
        Locale::setDefault(Locale::canonicalize($locale));
        $this->helper->setBasePath($locale);

        $path = substr($path, strlen($locale) + 1);

        return $handler->handle($request->withUri(
            $uri->withPath($path ?: '/')
        ));
    }
}