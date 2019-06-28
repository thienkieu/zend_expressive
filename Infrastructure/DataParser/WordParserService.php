<?php
namespace Infrastructure\DataParser;

use Infrastructure\Interfaces\HandlerInterface;

class WordParserService implements DataParserInterface, HandlerInterface
{
    protected $formatAdapter;
    protected $container;
    protected $dm;
    protected $options;
    protected $translator;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->translator = $this->container->get(\Config\AppConstant::Translator);    
        
        if (!isset($this->options['format'])) {
            $this->formatAdapter = new HtmlFormatAdapter();
        } else {
            $this->formatAdapter = $this->options['format'];
        }
    }

    public function setFormart(FormatAdapterInterface $format) {
        $this->formatAdapter = $format;
    }

    public function isHandler($param, $options = []) {
        if($options[DataParserInterface::FileTypeKey] === 'word') {
            return true;
        }

        return false;
    }

    public function parseData($obj, $options = []) {

    }
}
