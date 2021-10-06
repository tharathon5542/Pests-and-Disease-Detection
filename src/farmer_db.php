<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    // select farmer data from database
    $sql_select_farmer_data = "SELECT * FROM farmer WHERE USER_ROLE = 'user'";
    $query_farmer_data = mysqli_query($conn,$sql_select_farmer_data);
 
?>