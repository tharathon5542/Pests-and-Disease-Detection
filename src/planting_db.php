<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    // select farmer data from database
    $farmer_code = $_SESSION['farmerCode'];
    $sql_select_farmer_data = "SELECT * FROM farmer WHERE F_CODE = $farmer_code";
    $query_farmer_data = mysqli_query($conn,$sql_select_farmer_data);
    $result_farmer_data = mysqli_fetch_assoc($query_farmer_data);

    // select vetgetable data from database
    $sql_select_vetgetable = "SELECT V_CODE,V_THAINAME FROM vetgetable";
    $query_vetgetable_data = mysqli_query($conn,$sql_select_vetgetable);

    // select plant data from database
    $sql_select_plant = "SELECT P_CODE,P_FARM_CODE,V_CODE,F_CODE,P_NUM,P_STATUS,DATE(P_DATE) AS P_DATE,DATE(P_HARVEST) AS P_HARVEST FROM plant WHERE F_CODE = $farmer_code ORDER BY P_CODE DESC";
    $query_select_plant = mysqli_query($conn,$sql_select_plant);

    // on add planting
    if(isset($_POST['add_planting'])){
        
        // get value from html tag
        $v_code = mysqli_real_escape_string($conn,$_POST['selectVetgetable']);
        $p_num = mysqli_real_escape_string($conn,$_POST['inputPlantnum']);
        $p_farm_code = mysqli_real_escape_string($conn,$_POST['inputPlantFarmCode']);
        $p_date = mysqli_real_escape_string($conn,$_POST['inputPlantDate']);
        $p_harvest = mysqli_real_escape_string($conn,$_POST['inputHarvestDate']);
        $p_status = mysqli_real_escape_string($conn,$_POST['selectPlantstatus']);

        // insert data to planting data
        $sql_insert_planting_data = "INSERT INTO plant (V_CODE, F_CODE, P_NUM, P_FARM_CODE, P_DATE, P_HARVEST, P_STATUS) VALUES('$v_code','$farmer_code','$p_num','$p_farm_code','$p_date','$p_harvest','$p_status')";
        if(mysqli_query($conn,$sql_insert_planting_data)){
            $_SESSION['success'] = "เพิ่มข้อมูลการปลูกสำเร็จ";
        }else{
            $_SESSION['errors'] = "เพิ่มข้อมูลการปลูกไม่สำเร็จ : " . mysqli_error($conn);
        }
        header('location: ../planting.php');    
    }

    // // on del planting
    if(isset($_POST['del_planting'])){

        // create variable for vetgetable code
        $p_code = $_POST['del_planting'];
        
        // delete planting data from database
        $sql_del_planting_data = "DELETE FROM plant WHERE P_CODE = $p_code";
        mysqli_query($conn,$sql_del_planting_data);
        $_SESSION['warning'] = "ลบข้อมูลการปลูกสำเร็จ รหัส : " . $p_code;
        header('location: ../planting.php');
    }
?>