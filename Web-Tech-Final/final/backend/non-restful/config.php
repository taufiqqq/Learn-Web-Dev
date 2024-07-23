<?php
class db{
    // Properties
    private $host = "localhost";
    private $dbname = "webtech";
    private $username = "webtech";
    private $password = "webtech";

    // Connect
    function connect(){
        $mysql_connect_str = "mysql:host=$this->host;dbname=$this->dbname";
        $dbConnection = new PDO($mysql_connect_str, $this->username, $this->password);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }
}
?>