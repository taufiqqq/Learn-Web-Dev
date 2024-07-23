<?php
 header("Content-type: text/plain");
 header("Access-Control-Allow-Origin: *");
 echo $_REQUEST["num1"] + $_REQUEST["num2"];
?>
