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
    private $dataParser; 

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');  
        $this->translator = $this->container->get(\Config\AppConstant::Translator);
    }

    public function isHandler($dto, $options = []){
        return true;
    }

    protected function addGeneralInfo(&$sheet, $sheetName, $examResultDTO) {
        $sheet->setShowGridlines(false);
        $sheet->setTitle($sheetName);
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $this->setCellValue($sheet, 'A1', 'CRM Online Test System - Export Exam Result', [], true);
        $cellStyles = [
            'font' => [
                'bold' => true,
                'size' => 15
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $this->setCellStyle($sheet, 'A1:D1', $cellStyles);
        $sheet->mergeCells('A1:D1');

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                ],
            ]
        ];
        $sheet->getStyle('A2:D2')->applyFromArray($styleArray);

        $this->setCellValue($sheet, 'A3', 'Candidate Id:', [], true);
        $this->setCellValue($sheet, 'B3', $examResultDTO->getCandidate()->getObjectId());
        $cellStyles = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $this->setCellStyle($sheet, 'B3', $cellStyles);

        $this->setCellValue($sheet, 'A4', 'Candidate Name:', [], true);
        $this->setCellValue($sheet, 'B4', $examResultDTO->getCandidate()->getName());
        

        $this->setCellValue($sheet, 'A5', 'Candidate Email:', [], true);
        $this->setCellValue($sheet, 'B5', $examResultDTO->getCandidate()->getEmail());

        $this->setCellValue($sheet, 'C3', 'Exam Name:', [], true);
        $this->setCellValue($sheet, 'D3', $examResultDTO->getTitle());

        $this->setCellValue($sheet, 'C4', 'Exam Date:', [], true);
        $this->setCellValue($sheet, 'D4', $examResultDTO->getStartDate());


    }

    protected function exportSummary(&$sheet, $examResultDTO) {
        $this->addGeneralInfo($sheet, 'Summary', $examResultDTO);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(30);

        $boderStyles = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $this->setCellStyle($sheet, 'B3', $boderStyles);
        $this->setCellStyle($sheet, 'D3', $boderStyles);

        $boderStyles = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                ],
            ],
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $this->setCellStyle($sheet, 'A8:D8', $boderStyles);
        $this->setCellValue($sheet, 'A8', 'Result Summary', [], true);
        $sheet->mergeCells('A8:D8');

        $this->setCellStyle($sheet, 'A9:C9', $boderStyles);
        $this->setCellValue($sheet, 'A9', 'Section', [], true);
        $sheet->mergeCells('A9:C9');

        $this->setCellStyle($sheet, 'D9', $boderStyles);
        $this->setCellValue($sheet, 'D9', 'Mark', [], true);

        $resultSummary = $examResultDTO->getResultSummary();
        $startIndex = 10;
        $sum = 0;
        foreach($resultSummary as $resultSummaryItem) {
            $this->setCellValue($sheet, 'A'.$startIndex, $resultSummaryItem->getName());
            $this->setCellValue($sheet, 'D'.$startIndex, $resultSummaryItem->getCandidateMark().'/'.$resultSummaryItem->getMark());
            $boderStyleValue = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $this->setCellStyle($sheet, 'D'.$startIndex, $boderStyleValue);
            $sum += $resultSummaryItem->getCandidateMark();
            $startIndex += 1;
        }

        $this->setCellValue($sheet, 'A'.$startIndex, 'Sum');
        $this->setCellValue($sheet, 'D'.$startIndex, $sum);
        $this->setCellStyle($sheet, 'A'.$startIndex.':D'.$startIndex, $boderStyles);
            
        $boderStyles = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                ],
            ]
        ];

        $this->setCellStyle($sheet, 'A10:D'.$startIndex, $boderStyles);

    }

    protected function exportDetails(&$sheet, $examResultDTO) {
        $this->addGeneralInfo($sheet, 'Details', $examResultDTO);
        $sections = $examResultDTO->getTest()->getSections();
        $startIndex = 8;
        foreach($sections as $section) {
            $this->setCellValue($sheet, 'A'.$startIndex, $section->getName());
            $this->setCellValue($sheet, 'B'.$startIndex, $section->getDescription());
            $styleArray = [
                'font' => [
                    'bold' => true,
                    'size' => 13
                ],
                'alignment' => [
                    'wrapText' => TRUE,
                ],
            ];
            $this->setCellStyle($sheet, 'A'.$startIndex.':B'.$startIndex, $styleArray);
            
            $sheet->getColumnDimension('B')->setWidth(120);
            $questions = $section->getQuestions();
            $questionIndex = 1;

            foreach($questions as $question) {
                $questionInfo = $question->getQuestionInfo();
                switch($questionInfo->getType()) {
                    case \Config\AppConstant::Reading :
                        $startIndex = $this->exportReadingQuestion($sheet, $questionInfo, $questionIndex, $startIndex +1);
                    break;
                    case \Config\AppConstant::Writing :
                        $startIndex = $this->exportWritingQuestion($sheet, $questionInfo, $questionIndex, $startIndex +1);
                    break;
                    case \Config\AppConstant::Listening :
                        $startIndex = $this->exportListeningQuestion($sheet, $questionInfo, $questionIndex, $startIndex +1);
                    break;
                }

                $questionIndex += 1;                
            }

            $startIndex += 2;
        }
        
    }

    protected function exportReadingQuestion($sheet, $question, $questionIndex, $startRow) {
        return $this->exportGeneralQuestion($sheet, $question, $questionIndex, $startRow);
    }

    protected function exportListeningQuestion($sheet, $question, $questionIndex, $startRow) {
        $this->exportComment($sheet, 'B'.$startRow, 'Path '.$questionIndex, '');
        $sheet->getCell('B'.$startRow)->getHyperlink()->setUrl($question->getPath());
        $startRow +1;
        $startRow = $this->exportSubQuestion($sheet, $question->getSubQuestions(), $startRow+1);

        return $startRow;
    }

    protected function exportWritingQuestion($sheet, $question, $questionIndex, $startRow, $isExportAnswer = true) {
        $startRow =  $this->exportGeneralQuestion($sheet, $question, $questionIndex, $startRow);
        if (!$isExportAnswer) return $startRow+1;

        $this->exportComment($sheet, 'B'.$startRow, 'Candidate Answer: ', $question->getAnswer());
        $startRow += 1;

        $this->exportComment($sheet, 'B'.$startRow, 'Comment: ', $question->getComment());
        $startRow += 1;

        return $startRow;
    }

    protected function exportComment($sheet, $cell, $label, $text) {
        $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText(); 
        $boldText = $richText->createTextRun($label);
        $boldText->getFont()->setBold(true);
        $richText->createText($text);
        $sheet->getCell($cell)->setValue($richText);

        $styleArray = [
            'alignment' => [
                'wrapText' => TRUE,
            ],
        ];
        $this->setCellStyle($sheet, $cell, $styleArray);   
    }


    protected function exportGeneralQuestion($sheet, $questionInfo, $questionIndex, $startRow) {
        $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText(); 
        $boldText = $richText->createTextRun($questionIndex.') ');
        $boldText->getFont()->setBold(true);
        
        $content = $this->toRichTextFromHTML($questionInfo->getContent());
        $this->addRichTextToRichText($content, $richText);
        
        $sheet->getCell('B'.$startRow)->setValue($richText);

        $styleArray = [
            'alignment' => [
                'wrapText' => TRUE,
                'indent' => 0,
            ],
        ];
        $this->setCellStyle($sheet, 'B'.$startRow, $styleArray);                
        $startRow = $this->exportSubQuestion($sheet, $questionInfo->getSubQuestions(), $startRow+1);

        return $startRow+1;
    }

    protected function exportSubQuestion(&$sheet, $subQuestions, $startRow) {
        $subQuestionIndex = 1;
        foreach($subQuestions as $subQuestion) {
            $styleArray = [
                'alignment' => [
                    'indent' => 2,
                    'wrapText' => TRUE,
                ],
            ];
            $this->setCellStyle($sheet, 'B'.$startRow, $styleArray);

            $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText(); 
            $boldText = $richText->createTextRun($subQuestionIndex.'. ');
            $boldText->getFont()->setBold(true);
          
            $content = $this->toRichTextFromHTML($subQuestion->getContent());
            $this->addRichTextToRichText($content, $richText);

            $sheet->getCell('B'.$startRow)->setValue($richText);

            $startRow = $this->exportAnswer($sheet, $subQuestion->getAnswers(), $startRow+1);                    
            $subQuestionIndex += 1;
        }

        return $startRow;
    }

    protected function exportAnswer(&$sheet, $answers, $startRow) {
        $answerIndex = 65;
        foreach($answers as $answer) {
            $styleArray = [
                'alignment' => [
                    'indent' => 4,
                    'wrapText' => true,
                ],
            ];

            if ($answer->getIsCorrect()) {
                $styleArray['fill'] = [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '90ee90'
                    ]
                ];
            }
            $this->setCellStyle($sheet, 'B'.$startRow, $styleArray);


            $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText(); 
            $boldText = $richText->createTextRun(chr($answerIndex).'. ');
            $boldText->getFont()->setBold(true);

            $content = $this->toRichTextFromHTML($answer->getContent());
            $this->addRichTextToRichText($content, $richText);
            $sheet->getCell('B'.$startRow)->setValue($richText);
           
            if ($answer->getIsUserChoice()) {
                $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText(); 
                $boldText = $richText->createTextRun(mb_convert_encoding('&#8730;', 'UTF-8', 'HTML-ENTITIES'));
                $boldText->getFont()->setBold(true);
                $sheet->getCell('A'.$startRow)->setValue($richText);
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ]

                ];
                $this->setCellStyle($sheet, 'A'.$startRow, $styleArray);
            }
            $answerIndex += 1;
            $startRow += 1;
        }
        
        return $startRow+1;
    }

    protected function setCellStyle(&$sheet, $cells, $styles) {
        $sheet->getStyle($cells)->applyFromArray($styles);
    }

    protected function setCellValue(&$sheet, $cells, $value, $params = [], $isTranslate = false) {
        echo $cells.chr(13);
        if ($isTranslate == true) {
            $value = $this->translator->translate($value, $params);
        }

        $sheet->setCellValue($cells, $value);
    }

    protected function toRichTextFromHTML($html) {
        $wizard = new \PhpOffice\PhpSpreadsheet\Helper\Html();
        return $wizard->toRichTextObject($html);
    }

    protected function addRichTextToRichText($source, &$des) {
        $iTextElements = $source->getRichTextElements();
        foreach($iTextElements as $iTextElement) {
            $des->addText($iTextElement);
        }
    }

    public function exportCandidateExamResult($params, &$messages, &$writer, &$candidateName) {
        $doExamResultService = $this->container->get(DoExamResultServiceInterface::class);
        $ok = $doExamResultService->getExamResult($params, $messages, $outDTO);
        if (!$ok) {
            return false;
        }

        $candidateName = $outDTO->getCandidate()->getName();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->exportSummary($sheet, $outDTO);
        
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $sheet = $spreadsheet->getActiveSheet();
        $this->exportDetails($sheet, $outDTO);


        $writer = new Xlsx($spreadsheet);
        return true;
    }

    public function exportQuestion($dto, &$messages, &$writer) {
        $questionService = $this->container->get(Question\QuestionServiceInterface::class);
        $questionsResult = $questionService->getQuestions($dto, 1, 0);
        
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("c:\\questionTemplate.xlsx"); 

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($this->translator->translate('Questions'));
        
        $questions = $questionsResult['questions'];
        $questionIndex = 1;
        $startIndex = 5;
        
        foreach($questions as $question) {
            $startIndex += 1;
            //QuestionIndex
            $startColumnIndex = 66;
            $this->setCellValue($sheet, chr($startColumnIndex).$startIndex, $questionIndex);
            $startColumnIndex += 1;

            //Type
            $this->setCellValue($sheet, chr($startColumnIndex).$startIndex, $question->getType());
            $startColumnIndex += 1;

            //SubType
            $this->setCellValue($sheet, chr($startColumnIndex).$startIndex, $question->getSubType());
            $startColumnIndex += 1;

            //Source
            $this->setCellValue($sheet, chr($startColumnIndex).$startIndex, $question->getSource());
            $startColumnIndex += 1;

            //ImageFile/Audio
            //$this->setCellValue($sheet, chr($startColumnIndex).$startIndex, $question->getSource());
            $startColumnIndex += 1;

            //Repeat
            $repeat = '';
            if ($question->getType() === \Config\AppConstant::Listening) $repeat = $question->getRepeat();
            $this->setCellValue($sheet, chr($startColumnIndex).$startIndex, $repeat);
            $startColumnIndex += 1;

            //Content
            $this->setCellValue($sheet, chr($startColumnIndex).$startIndex, $question->getContent());
            $startColumnIndex += 1;

            $subQuestions = $question->getSubQuestions();
            foreach($subQuestions as $subQuestion) {
                $startColumnIndexSubQuestion = $startColumnIndex;

                //Question content
                $this->setCellValue($sheet, chr($startColumnIndexSubQuestion).$startIndex, $subQuestion->getContent());
                $startColumnIndexSubQuestion += 1;

                //Correct answer
                $correctAnswerColumn = chr($startColumnIndexSubQuestion).$startIndex;
                $startColumnIndexSubQuestion += 1;

                $isCorrectIndex = -1;
                $answers = $subQuestion->getAnswers();
                $answerindex = 1;
                
                $startColumnIndexAnswer = $startColumnIndexSubQuestion;
                foreach($answers as $answer) {
                    //Answer
                    $this->setCellValue($sheet, chr($startColumnIndexAnswer).$startIndex, $answer->getContent());
                    $startColumnIndexAnswer += 1;
                    
                    if ($answer->getIsCorrect()) {
                        $isCorrectIndex = $answerindex;
                    }

                    $answerindex +=1;
                    
                }

                $this->setCellValue($sheet, $correctAnswerColumn, $isCorrectIndex);
                $startIndex += 1;
            }
           
            
            $questionIndex += 1;                
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save('c:\\questions.xlsx');
        return true;
    }

    
}
