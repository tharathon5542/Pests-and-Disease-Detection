<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    // delete tmp disease link vetgetable
    $sql_del_tmp_disease_link_vetgetable = "DELETE FROM tmp_disease_link_vetgetable";
    mysqli_query($conn,$sql_del_tmp_disease_link_vetgetable);

    // select disease data from database
    $sql_select_disease_data = "SELECT * FROM disease";
    $query_disease_data = mysqli_query($conn,$sql_select_disease_data);
 
?>