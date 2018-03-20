<?php

  function getConn() {
    $servername = "localhost";
    $username = "chazo";
    $password = "#dingdong*";
    $dbname = "iot_yun";

    $conn = new mysqli($servername, $username, $password, $dbname); //Create connection

    if ($conn->connect_error) { //Check connection
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
  }

?>
