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

    public function getLinks($fileName){
        $res = [];
        $translit = new translit();
        $data = $this->parseCSV($fileName);
        for ($k = 0; $k < count($data); $k++){
            for ($p = 0; $p < count($data[$k]); $p++){
                $res[$k][$p] = $translit->tran($data[$k][$p]);
            }

        }
        print "<br><br>";
        for ($m = 0; $m < count($res); $m++){
            print "<a href= #>";
            print "https://webSiteName.org/?discountName=";print_r($res[$m][1]);print "&page=";print_r($res[$m][0]);
            print "</a>";print "<br>";

        }

    }

}
