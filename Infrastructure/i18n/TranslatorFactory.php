<?php

declare(strict_types=1);

namespace Infrastructure\i18n;

use Psr\Container\ContainerInterface;
//use Zend\I18n\Translator\Translator;
use Psr\Http\Message\ServerRequestInterface;
use Locale;

class TranslatorFactory
{
    public function __invoke(ContainerInterface $container) : Translator
    {
        $basePath = dirname(dirname(dirname(__FILE__)));
        $translator = new Translator();
        $translator->setLocale(Locale::getDefault());
        $translator->addTranslationFilePattern('phparray', $basePath.'/locale/','%s.php','default');
        
        return $translator;
    }
}
