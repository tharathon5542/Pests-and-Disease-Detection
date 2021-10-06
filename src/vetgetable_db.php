<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    // select farmer data from database
    $sql_select_vetgetable_data = "SELECT * FROM vetgetable";
    $query_vetgetable_data = mysqli_query($conn,$sql_select_vetgetable_data);
 
?>