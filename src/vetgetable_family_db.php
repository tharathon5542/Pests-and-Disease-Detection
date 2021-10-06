<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    // select vetgetable family data from database
    $sql_select_vetgetable_family_data = "SELECT * FROM vetgetable_family";
    $query_vetgetable_family_data = mysqli_query($conn,$sql_select_vetgetable_family_data);

    // get last vetgetable family code
    $sql_select_last_vetgetable_family_code = "SELECT MAX(VF_CODE) AS LAST_VF_CODE FROM vetgetable_family";
    $query_last_vetgetable_family_code = mysqli_query($conn,$sql_select_last_vetgetable_family_code);
    $result_last_vetgetable_family_code = mysqli_fetch_assoc($query_last_vetgetable_family_code);
        // if there is no data in vetgetable family
        if($result_last_vetgetable_family_code['LAST_VF_CODE'] === null){
            $last_vf_code = "00001";
        }else{
            $last_vf_code = substr("0000" . (intval($result_last_vetgetable_family_code['LAST_VF_CODE']) + 1),-5);
        }
 
    // on add vetgetable family
    if(isset($_POST['add_vetgetable_family'])){
        
        // get value from html tag
        $vf_engname = mysqli_real_escape_string($conn,$_POST['inputVFengname']);

        // insert vetgetable family data
        $sql_insert_vetgetable_family = "INSERT INTO vetgetable_family VALUES('$last_vf_code','$vf_engname')";
        mysqli_query($conn,$sql_insert_vetgetable_family);
        $_SESSION['success'] = "เพิ่มข้อมูลวงศ์พืชสำเร็จ";
        header("location: ../vetgetable_family.php");

    }
?>