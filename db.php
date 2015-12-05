<?php

class Database {

    private $conn;

    public function __construct(){
        $passwords = new Passwords();
        $this->conn = new mysqli($passwords->getDbHost(), $passwords->getDbUser(), $passwords->getDbPassword(), $passwords->getDb());
        if ($this->conn->connect_error) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function getData() {

    }

    public function getRaces() {
        $sql = "SELECT * FROM race ORDER BY date DESC";
        $result = $this->conn->query($sql);
        $races = array();
        while ($row = $result->fetch_object()) {
            array_push($races, $row);
        }
        return $races;
    }

    public function getHorses() {
        $sql = "SELECT * FROM horse";
        $result = $this->conn->query($sql);
        $horses = array();
        while ($row = $result->fetch_object()) {
            array_push($horses, $row);
        }
        return $horses;
    }

    public function getHorseRaces($horse_id) {
        $sql = "SELECT *
                FROM race_horse rh
                LEFT JOIN race r ON (rh.race_id = r.race_id)
                WHERE rh.horse_id = ".intval($horse_id);
        $result = $this->conn->query($sql);
        $races = array();
        while ($row = $result->fetch_object()) {
            array_push($races, $row);
        }
        return $races;
    }
}

?>