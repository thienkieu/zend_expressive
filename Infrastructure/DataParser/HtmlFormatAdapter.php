<?php

declare(strict_types=1);

namespace Infrastructure\DataParser;

class HtmlFormatAdapter implements FormatAdapterInterface
{
    public function buildFormat($data, $type, $options = []) {
        switch($type) {
            case FormatType::Bold :
                return $this->buildBoldFormat($data, $options);

            case FormatType::Underline :
                return $this->buildUnderlineFormat($data, $options);
                
            case FormatType::Italic :
                return $this->buildItaclicFormat($data, $options);
                
            case FormatType::Strike :
                return $this->buildStrikeFormat($data, $options);
            
            case FormatType::LineBreak :
                return $this->buildLinkBreakFormat($data, $options);
            default:
                return $data;
        }
    }

    protected function buildBoldFormat($data, $options = []) {
        return sprintf('<b>%s</b>', $data);
    }

    protected function buildUnderlineFormat($data, $options = []) {
        return sprintf('<u>%s</u>', $data);
    }

    protected function buildItaclicFormat($data, $options = []) {
        return sprintf('<i">%s</i>', $data);
    }

    protected function buildStrikeFormat($data, $options = []) {
        return sprintf('<del>%s</del>', $data);
    }

    protected function buildLinkBreakFormat($data, $options = []) {
        return str_replace(array("\n", "\r"), '<br>', $data);
    }

}
