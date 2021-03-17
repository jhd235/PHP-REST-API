<?php


class loaderCSV
{
    public function parseCSV($fileName)
    {
        $result = [];
        $row = 0;
        if (($handle = fopen($fileName, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                for ($i = 0; $i < count($data); $i++) {
                    $result[$row] = $data;
                }
                $row++;
            }
        }
        fclose($handle);
        return $result;
    }
}
