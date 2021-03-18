<?php
//TODO csv loader
//class loader to detect fields & create table structures
//load csv
//identify field names
//create table struct
//import data into table
//TODO REST web service url idDiscount nameDiscount
//web service classes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function my_autoloader($class) {
    include 'classes/' . $class . '.php';
}

spl_autoload_register('my_autoloader');
$fileName = "data/data.csv";
$tableName = "table";
$loaderCSV = new loaderCSV();
$translit = new translit();
$data = $loaderCSV->parseCSV($fileName);
$fields = $data[0];

for ($i = 0; $i < count($fields); $i++){
    $fields[$i] = $translit->tran($fields[$i]);
}

$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();
$instance->createdbtable($tableName, $fields);

array_shift($data);

if ($instance->isTableFilled() == 0) {
    print_r($instance->fillTable($data));
}

print_r($instance->randomStatus());

//$loaderCSV->getLinks($fileName);
header('Content-type: application/json');
print $loaderCSV->getLinksJSON($fileName);
