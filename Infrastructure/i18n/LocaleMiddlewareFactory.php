<?php
namespace Infrastructure\i18n;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\UrlHelper;

/**
 * Configuration for setting a default locale should look like the following:
 *
 * <code>
 * 'i18n' => [
 *     'default_locale' => 'de_DE',
 * ]
 * </code>
 */
class LocaleMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];
        
        return new LocaleMiddleware(
            $container->get(UrlHelper::class),
            $config['i18n']['default_locale'] ?? null
        );
    }
}