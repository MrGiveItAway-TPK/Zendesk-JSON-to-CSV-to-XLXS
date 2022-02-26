<?php
$jsonToCSV = new JsonToCSV();
$jsonToCSV->convert('JSON_SOURCE.json', 'CSV-FROM-JSON.csv');

class JsonToCSV{

    private $columns;
    private $csv;
    private $rows;

    public function convert($file, $csvFileName = '')
    {
        $fileContent = $this->getFileContent($file);
		
        $data = json_decode($fileContent, true);

        $this->columnSearch($data); 
        $this->fixRows(); 

        $this->createCSV($csvFileName);
    }
 
    private function createCSV($csvFileName)
    {
        if (strlen($csvFileName) < 1) {
            $csvFileName = 'jsontocsv.csv';
        }
        $this->csv = fopen($csvFileName, 'w');
        $this->addHeader();
        $this->addData();

        fclose($this->csv);
    }
 
    private function addData()
    {
        foreach ($this->rows as $row) {
            fputcsv($this->csv, $row);
        }
    }
 
    private function addHeader()
    {
        fputcsv($this->csv, $this->getColumns());
    }

    private function getFileContent($file)
    {
        $fileHandle = fopen($file, 'r');
        $fileContent = fread($fileHandle, filesize($file));
        fclose($fileHandle);
        return $fileContent;
    }

    public function columnSearch($data, $parent = '', $topKey = null)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (!is_numeric($key)) {
                    if (is_array($value)) {
                        if (strlen($parent) > 0) {
                            $key = $parent.'_'.$key;
                        }
                        $this->columnSearch($value, $key, $topKey);
                    } else {
                        if (strlen($parent) > 0) {
                            $newKey = $parent.'_'.$key;
                            $this->columns[] = $newKey;

                            $this->rows[$topKey][$newKey] = strval($value); //
                        } else {
                            $this->columns[] = $key;

                            $this->rows[$topKey][$key] = strval($value); //
                        }
                    }
                } else {
                    $this->columnSearch($value, $parent, $key);
                }
            }
        }
    }
 
    public function fixRows()
    {
        foreach ($this->rows as $key => $row) {
            foreach ($this->columns as $col) {
                if (!array_key_exists($col, $row)) {
                    $this->rows[$key][$col] = '';
                }
            }
            ksort($this->rows[$key]);
        }
    }
  
    public function getColumns()
    {
        if (!empty($this->columns)) {
            $columns = array_unique($this->columns);
            sort($columns);
            return $columns;
        }
        return null;
    }
}