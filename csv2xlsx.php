<?php
require('vendor\autoload.php');
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Csv');
$reader->setInputEncoding('UTF-8');
$objPHPExcel = $reader->load('CSV-FROM-JSON.csv');
$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
$objWriter->save('Excel-FROM-CSV-FROM-JSON.xlsx');
?>