<?php

class Weather {

    private $temperature;
    private $humidity;
    private $pressure;
    private $luminance;

    public function __construct() {
        $this->temperature = new stdClass();
        $this->temperature->val = 0;
        $this->temperature->timestamp = 0;

        $this->humidity = new stdClass();
        $this->humidity->val = 0;
        $this->humidity->timestamp = 0;

        $this->pressure = new stdClass();
        $this->pressure->val = 0;
        $this->pressure->timestamp = 0;

        $this->luminance = new stdClass();
        $this->luminance->val = 0;
        $this->luminance->timestamp = 0;
    }

    public function setValue($id, $value, $timestamp) {
        if ($id == '0x00060100') { //temperature
            if ($timestamp > $this->temperature->timestamp) {
                $this->temperature->val = $value;
            }
        } else if($id == '0x00060200') { //humidity
            if ($timestamp > $this->humidity->timestamp) {
                $this->humidity->val = $value;
            }
        } else if($id == '0x00060300') { //luminance
            if ($timestamp > $this->luminance->timestamp) {
                $this->luminance->val = $value;
            }
        } else if($id == '0x00060400') { //pressure
            if ($timestamp > $this->pressure->timestamp) {
                $this->pressure->val = $value;
            }
        }
    }

    public function getTemperature() {
        return $this->temperature->val;
    }

    public function getHumidity() {
        return $this->humidity->val;
    }

    public function getPressure() {
        return $this->pressure->val;
    }

    public function getLuminance() {
        return $this->luminance->val;
    }

}
?>