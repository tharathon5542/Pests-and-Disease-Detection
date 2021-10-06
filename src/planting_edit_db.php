<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    // create variable for plant code
    if(isset($_GET['pcode'])){   
        $_SESSION['pcode'] = $_GET['pcode'];   
    }

    // select plant data from database
    $sql_select_plant = "SELECT P_CODE,P_FARM_CODE,V_CODE,F_CODE,P_NUM,P_STATUS,DATE(P_DATE) AS P_DATE,DATE(P_HARVEST) AS P_HARVEST FROM plant WHERE P_CODE = " . $_SESSION['pcode'];
    $query_select_plant = mysqli_query($conn,$sql_select_plant);
    $result_select_plant = mysqli_fetch_assoc($query_select_plant);
    if(!$result_select_plant){
        header("location: planting.php");
    }

    // select vetgetable data from database
    $sql_select_vetgetable = "SELECT V_CODE,V_THAINAME FROM vetgetable";
    $query_vetgetable_data = mysqli_query($conn,$sql_select_vetgetable);

    // on edit plant
    if(isset($_POST['edit_planting'])){

        // get value from html tag
        $v_code = mysqli_real_escape_string($conn,$_POST['selectVetgetable']);
        $p_num = mysqli_real_escape_string($conn,$_POST['inputPlantnum']);
        $p_farm_code = mysqli_real_escape_string($conn,$_POST['inputPlantFarmCode']);
        $p_date = mysqli_real_escape_string($conn,$_POST['inputPlantDate']);
        $p_harvest = mysqli_real_escape_string($conn,$_POST['inputHarvestDate']);
        $p_status = mysqli_real_escape_string($conn,$_POST['selectPlantstatus']);

        // update plant data
        $sql_update_plant = "UPDATE plant SET V_CODE = '$v_code', P_NUM = '$p_num', P_FARM_CODE = '$p_farm_code', P_STATUS = '$p_status', P_DATE = '$p_date', P_HARVEST = '$p_harvest' WHERE P_CODE = " . $_SESSION['pcode'];
        mysqli_query($conn,$sql_update_plant);
        $_SESSION['success'] = "แก้ไขข้อมูลการปลูกสำเร็จ";
        header("location: ../planting.php");
    }

    // on delete plant
    if(isset($_POST['del_planting'])){
        // delete plant data
        $sql_del_plant = "DELETE FROM plant WHERE P_CODE = " . $_SESSION['pcode'];
        mysqli_query($conn,$sql_del_plant);
        $_SESSION['warning'] = "ลบข้อมูลการปลูกสำเร็จ";
        header("location: ../planting.php");
    }
    
?>