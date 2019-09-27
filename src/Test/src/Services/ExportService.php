<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ExportService implements Interfaces\ExportServiceInterface, HandlerInterface
{
    private $container;
    private $dm;
    private $options;
    private $translator;
    private $dataParser; 
    private $templateFolder;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');  
        $this->translator = $this->container->get(\Config\AppConstant::Translator);
        $appConfig = $container->get(\Config\AppConstant::AppConfig);
        $this->templateFolder = $appConfig['exportTemplateFolder'];
    }

    public function isHandler($dto, $options = []){
        return true;
    }

    protected function addGeneralInfo(&$sheet, $sheetName, $examResultDTO) {
        $sheet->setShowGridlines(false);
        $sheet->setTitle($sheetName);
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $this->setCellValue($sheet, 'A1', 'Online Test System - Export Exam Result', [], true);
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

        $this->setCellValue($sheet, 'A3', 'Candidate ID:', [], true);
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

        $this->setCellValue($sheet, 'C3', 'Exam Title:', [], true);
        $this->setCellValue($sheet, 'D3', $examResultDTO->getTitle());

        $this->setCellValue($sheet, 'C4', 'Exam Date:', [], true);
        $this->setCellValue($sheet, 'D4', $examResultDTO->getStartDate()->format('m/d/Y'));


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
        $this->setCellValue($sheet, 'A9', 'Skills', [], true);
        $sheet->mergeCells('A9:C9');

        $this->setCellStyle($sheet, 'D9', $boderStyles);
        $this->setCellValue($sheet, 'D9', 'Mark', [], true);

        $resultSummary = $examResultDTO->getResultSummary();
        $startIndex = 10;
        $sum = 0;
        $totalMark = 0;
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
            $totalMark += $resultSummaryItem->getMark();
            $startIndex += 1;
        }

        $this->setCellValue($sheet, 'A'.$startIndex, 'Final Score');
        $this->setCellValue($sheet, 'D'.$startIndex, $sum.'/'.$totalMark);
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
            
            $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
            $content = $this->toRichTextFromHTML($section->getDescription());
            $this->addBoldRichTextToRichText($content, $richText);
            $sheet->getCell('B'.$startIndex)->setValue($richText);

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
                    case \Config\AppConstant::Other :
                        $startIndex = $this->exportWritingQuestion($sheet, $questionInfo, $questionIndex, $startIndex +1);
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
        $this->exportComment($sheet, 'B'.$startRow, 'Click here to listen the audio'.$questionIndex, '');
        $sheet->getCell('B'.$startRow)->getHyperlink()->setUrl($question->getPath());
        $styleArray = [
            'borders' => [
                'outline' => [
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => 'FFFF0000'],
                ],
            ],
        ];
        $this->setCellStyle($sheet, 'B'.$startRow, $styleArray);
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
        if ($isTranslate == true) {
            $value = $this->translator->translate($value, $params);
        }

        $sheet->setCellValue($cells, $value);
    }

    protected function toRichTextFromHTML($html) {
        $wizard = new \PhpOffice\PhpSpreadsheet\Helper\Html();
        return $wizard->toRichTextObject($html);
    }

    protected function addBoldRichTextToRichText($source, &$des) {
        $iTextElements = $source->getRichTextElements();
        foreach($iTextElements as $iTextElement) {
            $iTextElement->getFont()->setBold(true);
            $des->addText($iTextElement);
        }
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

    public function getImageNameFromText($text) {

    }

    public function extractImages($text) {
        $ok = preg_match_all( '@src="([^"]+)"@' , $text, $match );
        if ($ok) {
            return $match[1];
        }

        return '';
    }

    public function setBorderCell(&$sheet, $cell) {
        $boderStyles = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '90EE90'],
                ]
            ]
        ];

        $this->setCellStyle($sheet, $cell, $boderStyles);
    }

    private function getRealPath($path) {
		$path = \Infrastructure\CommonFunction::replaceHost($path);
		$path = str_replace('%HOST%/\\', '', $path);
        $path = str_replace('%HOST%/', '', $path);

        $path = realpath($path);

        return $path;
    }

    public function exportImportQuestionTemplate(& $writer) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(false);
        $spreadsheet = $reader->load($this->templateFolder.\Config\AppConstant::DS."importQuestionTemplate.xlsx"); 

        $spreadsheet->setActiveSheetIndex(1);
        $sheet = $spreadsheet->getActiveSheet();

        $sourceService  = $this->container->get(Interfaces\SourceServiceInterface::class);
        $sourceDocuments = $sourceService->getSources('', $messsages, 1, 2000);
        $sources = $sourceDocuments['sources'];
        
        $sourceIndex = 2;
        foreach($sources as $source) {
            $this->setCellValue($sheet, 'D'.$sourceIndex, $source->getName());
            $sourceIndex++;
        }

        $typeService  = $this->container->get(Interfaces\TypeServiceInterface::class);
        $typeDocuments = $typeService->getTypes('', $messsages);
        $types = $typeDocuments->type;
        $typeIndex = 2;
        $subTypeIndex = 2;
        foreach($types as $type) {
            $this->setCellValue($sheet, 'B'.$typeIndex, $type->getName());
            foreach($type->getSubTypes() as $subType) {
                $this->setCellValue($sheet, 'C'.$subTypeIndex, $subType->getName());
                $subTypeIndex++;
            }
            
            $typeIndex++;
        }

        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $validation = $sheet->setDataValidation('C6:C1048576',
            (new DataValidation())
                ->setType(DataValidation::TYPE_LIST)
                ->setShowDropDown(true)
                ->setFormula1('Data!$B$2:$B$'.($typeIndex-1))
        );
        
        $validation = $sheet->setDataValidation('D6:D1048576',
            (new DataValidation())
                ->setType(DataValidation::TYPE_LIST)
                ->setShowDropDown(true)
                ->setFormula1('Data!$C$2:$C$'.($subTypeIndex-1))
        );
        
        $validation = $sheet->setDataValidation('E6:E1048576',
            (new DataValidation())
                ->setType(DataValidation::TYPE_LIST)
                ->setShowDropDown(true)
                ->setFormula1('Data!$D$2:$D$'.($sourceIndex-1))
        );

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        return true;
    }

    public function exportQuestion($dto, &$messages, &$writer) {
        $mediaFolder = \Config\AppConstant::MediaQuestionFolder . \Config\AppConstant::DS.'Export'.\Config\AppConstant::DS.date('Ymdhis');
        if (!file_exists($mediaFolder)) {
            mkdir($mediaFolder, 0777, true);
        }
        
        $zip = new \ZipArchive();
        if (! $zip->open($mediaFolder.\Config\AppConstant::DS.'question.zip', \ZipArchive::CREATE) === TRUE) {
            return false;
        }

        $questionService = $this->container->get(Question\QuestionServiceInterface::class);
        $questionsResult = $questionService->getQuestions($dto, 1, 0, true);
        
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($this->templateFolder.\Config\AppConstant::DS."questionTemplate.xlsx"); 

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($this->translator->translate('Questions'));
        
        $questions = $questionsResult['questions'];
        $questionIndex = 1;
        $startIndex = 5; 
        $maxColumHeader = 78;  
        $headerIndex = 5;   
        foreach($questions as $question) {
            $startIndex += 1;
            //QuestionIndex
            $startColumnIndex = 66;
            $this->setCellValue($sheet, chr($startColumnIndex).$startIndex, $questionIndex);
            //$this->setBorderCell($sheet, chr($startColumnIndex).$startIndex);
            $startColumnIndex += 1;

            //Type
            $this->setCellValue($sheet, chr($startColumnIndex).$startIndex, $question->getType());
            //$sheet->getStyle(chr($startColumnIndex).$startIndex)->getFont()->setBold(true);
            //$this->setBorderCell($sheet, chr($startColumnIndex).$startIndex);
            $startColumnIndex += 1;

            //SubType
            $this->setCellValue($sheet, chr($startColumnIndex).$startIndex, $question->getSubType());
            //$this->setBorderCell($sheet, chr($startColumnIndex).$startIndex);
            $startColumnIndex += 1;

            //Source
            $this->setCellValue($sheet, chr($startColumnIndex).$startIndex, $question->getSource());
            $sheet->getStyle(chr($startColumnIndex).$startIndex)->getAlignment()->setWrapText(true);
            //$this->setBorderCell($sheet, chr($startColumnIndex).$startIndex);
            $startColumnIndex += 1;

            //ImageFile/Audio
            if ($question->getType() === \Config\AppConstant::Listening) {
				$path = $this->getRealPath($question->getPath());
				if ($path !== false && strpos($path, 'http://') === false) {
                //\Infrastructure\CommonFunction::moveFileToFolder($path, $mediaFolder);
					$zip->addFile($path, basename($path));
					$this->setCellValue($sheet, chr($startColumnIndex).$startIndex, \basename($path));
				}
                //$sheet->getStyle(chr($startColumnIndex).$startIndex)->getAlignment()->setWrapText(true);
                //$sheet->getCell(chr($startColumnIndex).$startIndex)->getHyperlink()->setUrl($path);
            } 
            if ($question->getType() === \Config\AppConstant::Reading) {
                $images = \Infrastructure\CommonFunction::extractImages($question->getContent());
                if ($images) {
                    $baseImageName = [];
                    foreach($images as $image) {
                        $path = $this->getRealPath($image);
						if ($path !== false && strpos($path, 'http://') === false) {
                        //\Infrastructure\CommonFunction::moveFileToFolder($path, $mediaFolder);
							$zip->addFile($path, \basename($path));
							$baseImageName[] = basename($path);
						}
                    }
                    
                    $this->setCellValue($sheet, chr($startColumnIndex).$startIndex, \implode(',',$baseImageName));
                }
                //$sheet->getStyle(chr($startColumnIndex).$startIndex)->getAlignment()->setWrapText(true);
                //$sheet->getCell(chr($startColumnIndex).$startIndex)->getHyperlink()->setUrl($images);
            }
            //$this->setCellValue($sheet, chr($startColumnIndex).$startIndex, $question->getSource());
            //$this->setBorderCell($sheet, chr($startColumnIndex).$startIndex);
            $startColumnIndex += 1;

            //Repeat
            $repeat = '';
            if ($question->getType() === \Config\AppConstant::Listening) $repeat = $question->getRepeat();
            $this->setCellValue($sheet, chr($startColumnIndex).$startIndex, $repeat);
            //$this->setBorderCell($sheet, chr($startColumnIndex).$startIndex);
            $startColumnIndex += 1;

            //Content
            if ($question->getType() === \Config\AppConstant::Reading) {
                $content = preg_replace_callback('(<img.*src="(.*?)".*/>)', function ($matches) {
                    if (count($matches) > 0) {
                        $image = $this->getRealPath($matches[1]);
                        if ($image) {
                            return '['.basename($image).']';
                        }                        
                    }
                    return '';
                }, $question->getContent());
                $this->setCellValue($sheet, chr($startColumnIndex).$startIndex, $this->toRichTextFromHTML($content));
            } else {
                $this->setCellValue($sheet, chr($startColumnIndex).$startIndex, $this->toRichTextFromHTML($question->getContent()));
            }
            
            $sheet->getStyle(chr($startColumnIndex).$startIndex)->getAlignment()->setWrapText(true);
            //$this->setBorderCell($sheet, chr($startColumnIndex).$startIndex);
            $startColumnIndex += 1;

            $subQuestions = $question->getSubQuestions();
            
            foreach($subQuestions as $subQuestion) {
                $startColumnIndexSubQuestion = $startColumnIndex;

                //Question content
                $this->setCellValue($sheet, chr($startColumnIndexSubQuestion).$startIndex, $this->toRichTextFromHTML($subQuestion->getContent()));
                $sheet->getStyle(chr($startColumnIndexSubQuestion).$startIndex)->getAlignment()->setWrapText(true);
                //$this->setBorderCell($sheet, chr($startColumnIndexSubQuestion).$startIndex);
                $startColumnIndexSubQuestion += 1;

                //Correct answer
                $correctAnswerColumn = chr($startColumnIndexSubQuestion).$startIndex;
                $startColumnIndexSubQuestion += 1;

                $isCorrectIndex = '';
                $answers = $subQuestion->getAnswers();
                $answerindex = 1;
                
                $startColumnIndexAnswer = $startColumnIndexSubQuestion;
                foreach($answers as $answer) {
                    //Answer
                    if ($startColumnIndexAnswer > $maxColumHeader) {
                        $this->setCellValue($sheet, chr($startColumnIndexAnswer).$headerIndex, 'Answer '.($startColumnIndexAnswer - $maxColumHeader + 4));
                        $sheet->getColumnDimension(chr($startColumnIndexAnswer))->setWidth(30);
                    }
                    $this->setCellValue($sheet, chr($startColumnIndexAnswer).$startIndex, $this->toRichTextFromHTML($answer->getContent()));
                    $sheet->getStyle(chr($startColumnIndexAnswer).$startIndex)->getAlignment()->setWrapText(true);
                    //$this->setBorderCell($sheet, chr($startColumnIndexAnswer).$startIndex);
                    $startColumnIndexAnswer += 1;
                    
                    if ($answer->getIsCorrect()) {
                        $isCorrectIndex = $answerindex;
                    }

                    $answerindex +=1;                    
                }

                $this->setCellValue($sheet, $correctAnswerColumn, $isCorrectIndex);
                //$this->setBorderCell($sheet, $correctAnswerColumn);
                $startIndex += 1;
            }

            if ($subQuestions) {
                $startIndex += -1;
            }
           
            
            $questionIndex += 1;                
        }
       
        if ($startIndex > 5) {
            $this->setBorderCell($sheet, 'B5:'.chr($maxColumHeader).$startIndex);
        }

        $writer = new Xlsx($spreadsheet);
        
        $writer->save($mediaFolder.\Config\AppConstant::DS.'questions.xlsx');
        $zip->addFile($mediaFolder.\Config\AppConstant::DS.'questions.xlsx', 'questions.xlsx');
        $zip->close();
        return \realpath($mediaFolder).\Config\AppConstant::DS.'question.zip';
    }


    public function exportPin($examId, &$messages, &$writer) {
        $examService = $this->container->get(ExamServiceInterface::class);
        $ok = $examService->getExam($examId, $outDTO, $messages);
        if (!$ok) {
            return false;
        }

        $candidates = $outDTO->getCandidates();
        
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($this->templateFolder.\Config\AppConstant::DS."pinTemplate.xlsx"); 
        $sheet = $spreadsheet->getActiveSheet();
        $startIndex = 2; 
        $index = 1; 
        $boldStyles = [
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];

        $titleStyles = [
            'font' => [
                'bold' => true,
                'size' => 17,
                'color' => ['rgb' => '000000'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '90EE90'],
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '90EE90',
                ]
            ],
        ];

        foreach($candidates as $candidate) {
            $this->setCellValue($sheet, "B$startIndex", 'LogiGear Online Test - Candidate PIN');
            $sheet->getRowDimension($startIndex)->setRowHeight(40);
            $this->setCellStyle($sheet, "B$startIndex:F$startIndex", $titleStyles);
            $sheet->mergeCells("B$startIndex:F$startIndex");
            $startIndex += 2;

            $this->setCellValue($sheet, 'B'.$startIndex, 'Exam Title:');
            $this->setCellValue($sheet, 'D'.$startIndex, $outDTO->getTitle());
            $sheet->mergeCells("B$startIndex:C$startIndex");
            $this->setCellStyle($sheet, "B$startIndex:D$startIndex", $boldStyles);
            $startIndex += 1;
            
            $this->setCellValue($sheet, 'B'.$startIndex, 'Exam Date:');
            $this->setCellValue($sheet, 'D'.$startIndex, $outDTO->getStartDate()->format('M-d-Y'));
            $sheet->mergeCells("B$startIndex:C$startIndex");
            $this->setCellStyle($sheet, "B$startIndex:D$startIndex", $boldStyles);
            $startIndex += 1;

            $this->setCellValue($sheet, 'B'.$startIndex, 'Time:');
            $this->setCellValue($sheet, 'D'.$startIndex, $outDTO->getTime().' minutes');
            $this->setCellStyle($sheet, "B$startIndex:D$startIndex", $boldStyles);
            $startIndex += 2;

            $this->setCellValue($sheet, 'B'.$startIndex, '#');
            $this->setCellValue($sheet, 'C'.$startIndex, 'Candidate ID');
            $this->setCellValue($sheet, 'D'.$startIndex, 'Email');
            $this->setCellValue($sheet, 'E'.$startIndex, 'Candidate Name');
            $this->setCellValue($sheet, 'F'.$startIndex, 'PIN');
            $sheet->getRowDimension($startIndex)->setRowHeight(20);
            $cellStyles = [
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $this->setCellStyle($sheet, 'B'.$startIndex.':F'.$startIndex, $cellStyles);

            $startIndex += 1;

            $this->setCellValue($sheet, 'B'.$startIndex, '1');
            $this->setCellValue($sheet, 'C'.$startIndex, $candidate->getObjectId());
            $this->setCellValue($sheet, 'D'.$startIndex, $candidate->getEmail());
            $this->setCellValue($sheet, 'E'.$startIndex, $candidate->getName());
            $this->setCellValue($sheet, 'F'.$startIndex, $candidate->getPin());
            $this->setBorderCell($sheet, 'B'.($startIndex-1).':F'.($startIndex));

            $startIndex += 2; 
            $index += 1;  
        }
       
        $writer = new Xlsx($spreadsheet);
        return true;
    }

    public function exportExamSummary($filterCriterial, &$messages, &$writer) {
        $examService = $this->container->get(ExamServiceInterface::class);
        $ok = $examService->getExams($filterCriterial, $exams, $messages, $pageNumber = 1, $itemPerPage = 1000);
        if (!$ok) {
            return false;
        }

        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setShowGridlines(false);
        $boldStyles = [
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        
        $rowIndex = 8; 
        $index = 1; 
        $maxColumn = 0;
        $sheet->getColumnDimension('A')->setWidth(2);
        $sheet->getRowDimension('1')->setRowHeight(5);

        $examDTOs = $exams->exams;
        foreach($examDTOs as $outDTO) {
            
            $candidates = $outDTO->getCandidates();
            $this->setCellValue($sheet, 'B'.($rowIndex -3), 'Exam Title:');
            $this->setCellValue($sheet, 'D'.($rowIndex -3), $outDTO->getTitle()); 
            $this->setCellStyle($sheet, 'B'.($rowIndex -3).':'.'D'.($rowIndex -3), $boldStyles);

            $this->setCellValue($sheet, 'B'.($rowIndex -2), 'Exam Date:');
            $this->setCellValue($sheet, 'D'.($rowIndex -2), $outDTO->getStartDate()->format('M-d-Y'));
            $this->setCellStyle($sheet, 'B'.($rowIndex -2).':'.'D'.($rowIndex -2), $boldStyles);
            
            $rowHeaderTitle = $rowIndex - 1;
            $maxColumnEachExam = 0;
            $totalMarks = [];
            $startRowTotalMark = $rowIndex;
            foreach($candidates as $candidate) {
                $resultSummaries = $candidate->getResultSummary();
                $columnIndex = 66;
               
                $this->setCellValue($sheet, chr($columnIndex).$rowHeaderTitle, 'No'); 
                $this->setCellStyle($sheet, chr($columnIndex).$rowHeaderTitle, $boldStyles);
                $this->setCellValue($sheet, chr($columnIndex).$rowIndex, $index);
                $columnIndex += 1;

                $sheet->getColumnDimension(chr($columnIndex))->setWidth(15);
                $this->setCellValue($sheet, chr($columnIndex).$rowHeaderTitle, 'Candidate ID');
                $this->setCellStyle($sheet, chr($columnIndex).$rowHeaderTitle, $boldStyles); 
                $this->setCellValue($sheet, chr($columnIndex).$rowIndex, $candidate->getObjectId());
                $columnIndex += 1;

                $sheet->getColumnDimension(chr($columnIndex))->setWidth(30);
                $this->setCellValue($sheet, chr($columnIndex).$rowHeaderTitle, 'Candidate Name'); 
                $this->setCellStyle($sheet, chr($columnIndex).$rowHeaderTitle, $boldStyles);
                $this->setCellValue($sheet, chr($columnIndex).$rowIndex, $candidate->getName());
                $columnIndex += 1;

                $sheet->getColumnDimension(chr($columnIndex))->setWidth(30);
                $this->setCellValue($sheet, chr($columnIndex).$rowHeaderTitle, 'Email'); 
                $this->setCellStyle($sheet, chr($columnIndex).$rowHeaderTitle, $boldStyles);
                $this->setCellValue($sheet, chr($columnIndex).$rowIndex, $candidate->getEmail());
                $columnIndex += 1;


                $totalMark = 0;
                $totalCandiateMark = 0;
                
                foreach($resultSummaries as $resultItem) {
                    $sheet->getColumnDimension(chr($columnIndex))->setWidth(20);
                    $this->setCellValue($sheet, chr($columnIndex).$rowHeaderTitle, $resultItem->getName());                
                    $this->setCellStyle($sheet, chr($columnIndex).$rowHeaderTitle, $boldStyles);
                    $this->setCellValue($sheet, chr($columnIndex).$rowIndex, $resultItem->getCandidateMark().'/'.$resultItem->getMark());                

                    $totalCandiateMark += $resultItem->getCandidateMark();
                    $totalMark += $resultItem->getMark();
                    $columnIndex += 1;
                    if ($maxColumnEachExam < $columnIndex) $maxColumnEachExam = $columnIndex;
                }
                
                if ($columnIndex > $maxColumn) $maxColumn = $columnIndex;
                if ($maxColumnEachExam < $columnIndex) $maxColumnEachExam = $columnIndex;

                $totalMarks[] = $totalCandiateMark.'/'.$totalMark;
                $rowIndex += 1;
                $index += 1;

            }
        
            if ($maxColumnEachExam > 0) {
                $this->setCellValue($sheet, chr($maxColumnEachExam).$rowHeaderTitle, 'Total Mark');
                $sheet->getColumnDimension(chr($maxColumnEachExam))->setWidth(15); 
                $this->setCellStyle($sheet, chr($maxColumnEachExam).$rowHeaderTitle, $boldStyles);
            

                foreach($totalMarks as $item) {
                    $this->setCellValue($sheet, chr($maxColumnEachExam).$startRowTotalMark, $item); 
                    $startRowTotalMark +=1;
                }     

                $this->setBorderCell($sheet, 'B'.$rowHeaderTitle.':'.chr($maxColumnEachExam).($rowIndex -1));
            }
            
            $rowIndex += 6; 
            $index = 1;

        }
        $this->setHeaderStyle($sheet, "B2:".chr($maxColumn).'2');
        $this->setCellValue($sheet, "B2", 'Exam Result Summary');

        $sheet->setTitle('Summary');
        $writer = new Xlsx($spreadsheet);
        return true;

    }

    protected function setHeaderStyle(&$sheet, $range) {
        $headerStyles = [
            'font' => [
                'bold' => true,
                'size' => 17,
                'color' => ['rgb' => '000000'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '90EE90'],
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '90EE90',
                ]
            ],
        ];

        $sheet->getRowDimension('B1')->setRowHeight(40);
        $sheet->mergeCells($range);
        $this->setCellStyle($sheet, $range, $headerStyles);
    }

}
