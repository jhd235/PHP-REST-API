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

    public function createdbtable($table,$fields)
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
        if($conn->exec($sql) !== false) { return ; }
    }

    private function statusToTinyInt($str){
        switch ($str){
            case "On": return 1;
            case "Off": return 0;
        }
    }

    public function fillTable($values){
        if ($this->isTableFilled() != 0) {
            exit("Table contains data");
        }
        try {
            $conn = $this->getConnection($values);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // prepare sql and bind parameters
            $stmt = $conn->prepare("INSERT INTO `table` (`nazvanie-aktsii`, `data-nachala-aktsii`, `data-okonchaniya`, `status`)
  VALUES (:nazvanieAktsii, :dataNachalaAktsii, :dataOkonchaniya, :status)");
            $stmt->bindParam(':nazvanieAktsii', $nazvanieAktsii);
            $stmt->bindParam(':dataNachalaAktsii', $dataNachalaAktsii);
            $stmt->bindParam(':dataOkonchaniya', $dataOkonchaniya);
            $stmt->bindParam(':status', $status);

            // insert a row
            for ($i = 0; $i < count($values); $i++){

                    $nazvanieAktsii = $values[$i][1];
                    $dataNachalaAktsii = strtotime($values[$i][2]);
                    $dataOkonchaniya = strtotime($values[$i][3]);
                    $status = $this->statusToTinyInt($values[$i][4]);
                    $stmt->execute();
            }


            echo "New records created successfully";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    return 1;

    }

    private function inverter($int){
        switch ($int){
            case 0: return 1;
            case 1: return 0;
        }
    }

    public function randomStatus(){
        $conn = $this->getConnection();
        $sql = "select `id-aktsii` from `table`";
        $ids = $conn->query($sql)->fetchAll();
        $rid = array_rand($ids, 1);

        $this->inverter();

        $sql = "UPDATE `table` SET status= WHERE id=2";
        return
    }

    public function isTableFilled(){
        $conn = $this->getConnection();
        $sql = "select count(`id-aktsii`) from `table`";
        return $conn->query($sql)->fetchColumn();
    }

}