<?php


class db
{
    private $host;
    private $user;
    private $passwd;
    private $dbname;

    public function connect(){
        return new mysqli(
            $this->host,
            $this->user,
            $this->passwd,
            $this->dbname);
    }
    public function createTable(){
        $mysqli = $this->connect();
        $mysqli->query("CREATE TABLE test(id INT, label TEXT)");
    }

    public function dataToTable(){
        $stmt = $mysqli->prepare("INSERT INTO test(id, label) VALUES (?, ?)");
        $stmt->bind_param("is", $id, $label);
        // "is" means that the first parameter is bound as an integer and
        // the second as a string

        $data = [
            1 => 'PHP',
            2 => 'Java',
            3 => 'C++'
        ];
        foreach ($data as $id => $label) {
            $stmt->execute();
        }

        $result = $mysqli->query('SELECT id, label FROM test');
        var_dump($result->fetch_all(MYSQLI_ASSOC));

    }
}