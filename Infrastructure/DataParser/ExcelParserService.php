<?php
namespace Infrastructure\DataParser;

use Infrastructure\Interfaces\HandlerInterface;

class ExcelParserService implements DataParserInterface, HandlerInterface, \Iterator 
{
    protected $formatAdapter;
    protected $container;
    protected $dm;
    protected $options;
    protected $translator;
    protected $rowItertor = null;
    protected $excelFormatAdapter;
    
    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->translator = $this->container->get(\Config\AppConstant::Translator);    
        $this->excelFormatAdapter = new ExcelFormatAdapter();

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
        if($options[DataParserInterface::FileTypeKey] === 'excel') {
            return true;
        }

        return false;
    }

    public function toData($dataInFormat, $options = []) {
        $this->excelFormatAdapter->fromOtherFormat($dataInFormat, $this->formatAdapter, $options);
    }

    public function parseData($obj, $options = []) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(false);
        $spreadsheet = $reader->load($obj->file);
        $rowItertor = $spreadsheet->getActiveSheet()->getRowIterator();
        $rowIndex = 0;
        $startIndex = $options['rowIndexStart'];
        while($rowItertor->valid()) {
            $rowData = $rowItertor->current();                
            if ($rowIndex < $startIndex) {
                $rowItertor->next();
                $rowIndex++;
                continue;
            }

            $this->rowItertor = $rowItertor;
            break;
        }
    }


    protected function getValue() {
        $ret = [];
        $rowData = $this->rowItertor->current();
        $cellIterator = $rowData->getCellIterator();
        foreach ($cellIterator as $cell) {
            
           
            $cellValue = $cell->getValue();
            $cellText = $cellValue;
            if ($cellValue instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
                
                $textElements = $cellValue->getRichTextElements();
                $elementText = '';
                foreach ($textElements as $element) {
                    $text = $element->getText();
                    $font = $element->getFont(); 
                    if ($font) {
                        $text = $this->builFormat($text, $font);
                    }

                    $elementText = sprintf('%s%s', $elementText, $text);
                }
                $cellText = $elementText ;
            } else {
                $font = $cell->getStyle()->getFont();
                $cellText = $this->builFormat($cellText, $font);
            }
            
            $cellText = $this->formatAdapter->buildFormat($cellText, FormatType::LineBreak);
            $ret[] = $cellText;
        }
        return $ret;
    }

    protected function builFormat($value, $font) {
        if ($font->getBold()) {
            $value = $this->formatAdapter->buildFormat($value, FormatType::Bold);
        }
        if ($font->getItalic()) {
            $value = $this->formatAdapter->buildFormat($value, FormatType::Italic);
        }
        if ($font->getUnderline() !== \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_NONE) {
            $value = $this->formatAdapter->buildFormat($value, FormatType::Underline);
        }
        if ($font->getStrikethrough()) {
            $value = $this->formatAdapter->buildFormat($value, FormatType::Strike);
        }
        
        return $value;
    }

    public function current() {
        return $this->getValue();   
    }

    public function key() {
        return $this->rowItertor->key();
    }

    public function next() {
        return $this->rowItertor->next();
    }

    public function valid() {
        return $this->rowItertor->valid();
    }

    public function rewind() {
        return $this->rowItertor->rewind();
    }
}
