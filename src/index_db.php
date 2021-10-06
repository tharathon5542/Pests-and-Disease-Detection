<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    if(isset($_GET['NoCode']) && strlen($_GET['NoCode']) > 0){
        header('location: ./detection.php?NoCode=' . $_GET['NoCode']);   
    }
    
    // select vetgetable data from database
    $sql_select_vetgetable_data = "SELECT * FROM vetgetable";
    $query_vetgetable_data = mysqli_query($conn,$sql_select_vetgetable_data);

    // select farmer notify data from database
    $sql_select_notify_data = "SELECT NO_CODE,F_CODE,NO_DETAIL,NO_DETAIL_TYPE,NO_DATE FROM notify WHERE F_CODE = " . $_SESSION['farmerCode'];
    $query_notify_data = mysqli_query($conn,$sql_select_notify_data);
    
    
?>