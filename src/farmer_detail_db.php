<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');
    
    // on farmer detail
    if(isset($_GET['farmer_detail'])){

        // create variable for farmer code
        $farmer_code_detail = $_GET['farmer_detail'];
        
        // select farmer data from database
        $sql_select_farmer_data = "SELECT * FROM farmer WHERE F_CODE = $farmer_code_detail";
        $query_farmer_data = mysqli_query($conn,$sql_select_farmer_data);
        $result_farmer_data = mysqli_fetch_assoc($query_farmer_data);

        // select farmer planting data from database
        $sql_select_plant_data = "SELECT P_CODE,P_FARM_CODE,V_CODE,F_CODE,P_NUM,date(P_DATE) AS P_DATE , date(P_HARVEST) AS P_HARVEST , P_STATUS FROM plant WHERE F_CODE = $farmer_code_detail";
        $query_plant_data = mysqli_query($conn,$sql_select_plant_data);

        // select farmer notify data from database
        $sql_select_notify_data = "SELECT NO_CODE,F_CODE,NO_DETAIL,NO_DETAIL_TYPE,NO_DATE AS NO_DATE FROM notify WHERE F_CODE = $farmer_code_detail";
        $query_notify_data = mysqli_query($conn,$sql_select_notify_data);
     
    }

?>