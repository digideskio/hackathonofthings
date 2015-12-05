<?php
include 'passwords.php';
class ThingSee {

    const URL_MAX_LENGTH = 2050;

    private $accountAuthUuid;
    private $accountAuthToken;
    private $timestamp;

    private $url;
    private $email;
    private $password;
    private $deviceUuid;

    private $headers;

    public function __construct(){
        $passwords = new Passwords();
        $this->url = $passwords->getUrl();
        $this->email = $passwords->getEmail();
        $this->password = $passwords->getPassword();
        $this->deviceUuid = $passwords->getDeviceUuid();
    }

    public function getToken() {

        $data = array("email" => $this->email, "password" => $this->password);
        $data_string = json_encode($data);
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $result = json_decode(curl_exec($ch));
        $this->accountAuthToken = $result->accountAuthToken;
        $this->accountAuthUuid = $result->accountAuthUuid;
        $this->timestamp = $result->timestamp;
        curl_close($ch);
    }

    public function getData() {
        $ch = curl_init("http://api.thingsee.com/v2//events/".$this->deviceUuid);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->accountAuthToken
        ));

        $result = curl_exec($ch);
        var_dump($result);
    }
}
