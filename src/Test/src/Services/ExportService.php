<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportService implements Interfaces\ExportServiceInterface, HandlerInterface
{
    private $container;
    private $dm;
    private $options;
    private $translator;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');  
        $this->translator = $this->container->get(\Config\AppConstant::Translator);      
    }

    public function isHandler($dto, $options = []){
        return true;
    }

    public function exportCandidateExamResult($params, &$messages, &$outDTO) {
        $doExamResultService = $this->container->get(DoExamResultServiceInterface::class);
        $ret = $doExamResultService->getExamResult($params, $messages, $outDTO);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');
        
        $writer = new Xlsx($spreadsheet);
 
        $filename = 'c:\\name-of-the-generated-file.xlsx';
        $writer->save($filename);;
    }
}
