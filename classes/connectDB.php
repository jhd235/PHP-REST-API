<?php


// Singleton to connect db.
class ConnectDb {
    // Hold the class instance.
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $user = 'rest';
    private $pass = '2XoYYz5E^6k0';
    private $name = 'rest';

    // The db connection is established in the private constructor.
    private function __construct()
    {
        $this->conn = new PDO("mysql:host={$this->host};
    dbname={$this->name}", $this->user,$this->pass,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    }

    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new ConnectDb();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    function createdbtable($table,$fields)
    {
        $conn = $this->getConnection();
        $sql = "CREATE TABLE IF NOT EXISTS `$table` (";
        for ($i = 0; $i < count($fields); $i++){
            switch ($i) {
                case 0:
                    $sql .= "`$fields[$i]` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, ";
                    break;
                case 1:
                    $sql .= "`$fields[$i]` VARCHAR(30) NOT NULL,";
                    break;
                case 2:
                    $sql .= "`$fields[$i]` INT(6) NOT NULL,";
                    break;
                case 3:
                    $sql .= "`$fields[$i]` INT(6) NOT NULL,";
                    break;
                case 4:
                    $sql .= "`$fields[$i]` TINYINT(1) NOT NULL";
                    break;
            }
        }
        $sql .= ") CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
        if($conn->exec($sql) !== false) { return 1; }
    }
}