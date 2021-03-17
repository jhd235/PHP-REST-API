<?php


class translit
{
    private $tranTable = array(
        "а" => "a",
        "ый" => "iy",
        "ые" => "ie",
        "б" => "b",
        "в" => "v",
        "г" => "g",
        "д" => "d",
        "е" => "e",
        "ё" => "yo",
        "ж" => "zh",
        "з" => "z",
        "и" => "i",
        "й" => "y",
        "к" => "k",
        "л" => "l",
        "м" => "m",
        "н" => "n",
        "о" => "o",
        "п" => "p",
        "р" => "r",
        "с" => "s",
        "т" => "t",
        "у" => "u",
        "ф" => "f",
        "х" => "kh",
        "ц" => "ts",
        "ч" => "ch",
        "ш" => "sh",
        "щ" => "shch",
        "ь" => "",
        "ы" => "y",
        "ъ" => "",
        "э" => "e",
        "ю" => "yu",
        "я" => "ya",
        "йо" => "yo",
        "ї" => "yi",
        "і" => "i",
        "є" => "ye",
        "ґ" => "g"
    );

    private $signsAndSpaces = array(
        "," => "-",
        "." => "-",
        "+" => "-",
        " " => "-",
        "-" => "-",
        "--" => "-",
        "---" => "-",
        "----" => "-",
    );

    private function doSignsAndSpaces($string){
        return strtr($string, $this->signsAndSpaces);
    }

    private $str;

    public function tran($string){
        $string = $this->doSignsAndSpaces($string);
        return strtr($this->convert($string), $this->tranTable);
    }
    private function convert($string){
        $this->str = mb_convert_case($string, MB_CASE_LOWER, "UTF-8");
        return $this->str;
    }
}