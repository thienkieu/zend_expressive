<?php

declare(strict_types=1);

namespace Infrastructure\DataParser;

interface DataParserInterface
{
    const FileTypeKey = "fileType";
    
    public function parseData($obj, $options = []);
    public function toData($obj, $options = []);

    public function setFormart(FormatAdapterInterface $format);
}
