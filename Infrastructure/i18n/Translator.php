<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Infrastructure\i18n;

/**
 * Translator.
 */
class Translator extends \Zend\I18n\Translator\Translator
{
    /**
     * Translate a message.
     *
     * @param  string $message
     * @param  string $textDomain
     * @param  string $locale
     * @return string
     */
    public function translate($message, $params = [], $textDomain = 'default', $locale = null)
    {
        $locale      = ($locale ?: $this->getLocale());
        $translation = $this->getTranslatedMessage($message, $locale, $textDomain);
        
        if ($translation !== null && $translation !== '') {
            if ($params) {
                $translation = str_replace(array_keys($params), array_values($params), $translation);
            }
            
            return $translation;
        }

        if (null !== ($fallbackLocale = $this->getFallbackLocale())
            && $locale !== $fallbackLocale
        ) {
            $translation = $this->translate($message, $textDomain, $fallbackLocale);
            if ($params) {
                $translation = str_replace(array_keys($params), array_values($params), $translation);
            }

            return $translation;
        }

        if ($params) {
            $message = str_replace(array_keys($params), array_values($params), $message);
        }
        return $message;
    }

    /**
     * Translate a plural message.
     *
     * @param  string                         $singular
     * @param  string                         $plural
     * @param  int                            $number
     * @param  string                         $textDomain
     * @param  string|null                    $locale
     * @return string
     * @throws Exception\OutOfBoundsException
     */
    public function translatePlural(
        $singular,
        $plural,
        $number,
        $params = [],
        $textDomain = 'default',
        $locale = null
    ) {
        $locale      = $locale ?: $this->getLocale();
        $translation = $this->getTranslatedMessage($singular, $locale, $textDomain);

        if ($translation === null || $translation === '') {
            if (null !== ($fallbackLocale = $this->getFallbackLocale())
                && $locale !== $fallbackLocale
            ) {
                return $this->translatePlural(
                    $singular,
                    $plural,
                    $number,
                    $textDomain,
                    $fallbackLocale
                );
            }

            $m = ($number == 1 ? $singular : $plural);
            if ($params) {
                $m = str_replace(array_keys($params), array_values($params), $m);
            }
            return $m;
        } elseif (is_string($translation)) {
            $translation = [$translation];
        }

        $index = $this->messages[$textDomain][$locale]
                      ->getPluralRule()
                      ->evaluate($number);

        if (! isset($translation[$index])) {
            throw new Exception\OutOfBoundsException(
                sprintf('Provided index %d does not exist in plural array', $index)
            );
        }
        
        $messageTranslate = $translation[$index];
        if ($params) {
            $messageTranslate = str_replace(array_keys($params), array_values($params), $messageTranslate);
        }

        return $messageTranslate;
    }
}
