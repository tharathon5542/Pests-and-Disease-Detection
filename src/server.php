<?php
    $sername = "student.crru.ac.th";
    $username = "601463017";
    $password = "tharathon@2298";
    $dbname = "601463017";

    // $sername = "localhost";
    // $username = "root";
    // $password = "";
    // $dbname = "pests_and_disease_detection";

    // $sername = "freedb.tech";
    // $username = "freedbtech_tharathon";
    // $password = "nexustec012";
    // $dbname = "freedbtech_plantanddiseasedetection";

    //create connection
    // Change character set to utf8
    $conn = mysqli_connect($sername, $username, $password, $dbname);
    // Change character set to utf8
    mysqli_set_charset($conn,"utf8");
    
    //check connection
    if(!$conn){
        die("Connection fail" . mysqli_connect_error());
    }

?>