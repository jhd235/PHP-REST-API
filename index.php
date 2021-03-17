<?php

//TODO csv loader
//class loader to detect fields & create table structures
//load csv
//identify field names
//create table struct
//import data into table


//TODO REST web service url idDiscount nameDiscount
//web service classes
//

function my_autoloader($class) {
    include 'classes/' . $class . '.php';
}

spl_autoload_register('my_autoloader');
$fileName = "data/data.csv";
$tableName = "table";
$loaderCSV = new loaderCSV();
$translit = new translit();
$data = [];

$data = $loaderCSV->parseCSV($fileName);
$fields = $data[0];
//print_r($fields);
for ($i = 0; $i < count($fields); $i++){
    $fields[$i] = $translit->tran($fields[$i]);
}
//print "<br>";
//print_r($fields);

$string = "ЫЕ ,. +";
$tran = $translit->tran($string);
//print "<br>";
//print_r($tran);



$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();
print "<br>";
print $instance->createdbtable($tableName, $fields);

array_shift($data);
print_r($instance->randomStatus());
print "<br>";
print_r($instance->fillTable($data));
/*print "<br>";
print $instance->isTableFilled();*/


