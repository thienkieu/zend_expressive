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
        return sprintf('<span style="font-weight:bold;">%s</span>', $data);
    }

    protected function buildUnderlineFormat($data, $options = []) {
        return sprintf('<span style="text-decoration:underline;">%s</span>', $data);
    }

    protected function buildItaclicFormat($data, $options = []) {
        return sprintf('<span style="font-style:italic;">%s</span>', $data);
    }

    protected function buildStrikeFormat($data, $options = []) {
        return sprintf('<span style="text-decoration:line-through;">%s</span>', $data);
    }

    protected function buildLinkBreakFormat($data, $options = []) {
        return str_replace(array("\n", "\r"), '<br>', $data);
    }
}
