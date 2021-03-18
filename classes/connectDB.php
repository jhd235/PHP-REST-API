<?php


// Singleton to connect db.
class ConnectDb
{
    // Hold the class instance.
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    //private $host = 'srv-pleskdb34.ps.kz:3306';
    private $user = 'rest';
    //private $user = 'skykz124_rest';
    private $pass = '2XoYYz5E^6k0';
    private $name = 'rest';
    //private $name = 'skykz124_rest';

    // The db connection is established in the private constructor.
    private function __construct()
    {
        $this->conn = new PDO("mysql:host={$this->host};
    dbname={$this->name}", $this->user, $this->pass,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ConnectDb();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function createdbtable($table, $fields)
    {
        $conn = $this->getConnection();
        $sql = "CREATE TABLE IF NOT EXISTS `$table` (";
        for ($i = 0; $i < count($fields); $i++) {
            switch ($i) {
                case 0:
                    $sql .= "`$fields[$i]` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, ";
                    break;
                case 1:
                    $sql .= "`$fields[$i]` VARCHAR(200) NOT NULL,";
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
        if ($conn->exec($sql) !== false) {
            return;
        }
    }

    private function statusToTinyInt($str)
    {
        switch ($str) {
            case "On":
                return 1;
            case "Off":
                return 0;
        }
    }

    private function TinyIntToString($int)
    {
        switch ($int) {
            case 0:
                return "Off";
            case 1:
                return "On";
        }
    }

    public function fillTable($values)
    {
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
            for ($i = 0; $i < count($values); $i++) {

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

    private function inverter($int)
    {
        switch ($int) {
            case 0:
                return 1;
            case 1:
                return 0;
        }
    }

    public function randomStatus()
    {

        if ($this->isTableFilled() == 0) {
            exit("Table is empty");
        }

        $conn = $this->getConnection();
        $sql = "select `id-aktsii` from `table`";
        $ids = $conn->query($sql)->fetchAll();
        $rid = array_rand($ids, 1);
        $sqlSelect = "select `status` from `table` where `id-aktsii` = $rid";
        $status = $this->inverter($conn->query($sqlSelect)->fetchColumn());
        try {
            $sql = "UPDATE `table` SET status=$status WHERE `id-aktsii` = $rid";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
        $sqlRecord = "select * from `table` where `id-aktsii` = $rid";
        $record = $conn->query($sqlRecord)->fetch();
        $conn = null;
        $res = "";
        if (!is_null($record) && is_array($record)) {
            $record = array_unique($record);
            if (!empty($record['data-nachala-aktsii'])) {
                $record['data-nachala-aktsii'] = $this->unixToHuman($record['data-nachala-aktsii']);
            }
            if (!empty($record['data-okonchaniya'])) {
                $record['data-okonchaniya'] = $this->unixToHuman($record['data-okonchaniya']);
            }
            if (isset($record['status']) && ($record['status'] == 0 || $record['status'] == 1)) {
                $record['status'] = $this->TinyIntToString($record['status']);
            } else {$record['status'] = "Off";}

            if (!empty($record['nazvanie-aktsii'])) {
                $record['nazvanie-aktsii'] = '"'.$record['nazvanie-aktsii'].'"';
            }


            $res = implode(";", $record);
        }
        return $res;

    }

    private function unixToHuman($unixTime)
    {
        $date = new DateTime();
        return $date->setTimestamp($unixTime)->format('d-m-Y');
    }

    public function isTableFilled()
    {
        $conn = $this->getConnection();
        $sql = "select count(`id-aktsii`) from `table`";
        return $conn->query($sql)->fetchColumn();
    }

}