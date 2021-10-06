<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }
    
    // include database server
    include('server.php');

    // on edit vetgetable family
    if(isset($_GET['vetgetable_detail_edit'])){
        // create variable for vetgetable family code
        $_SESSION['vf_code'] = $_GET['vetgetable_detail_edit'];        
    }

    // select vetgetable family data from database
    $sql_select_vetgetable_family_data = "SELECT * FROM vetgetable_family WHERE VF_CODE = " .  $_SESSION['vf_code'];
    $query_vetgetable_family_data = mysqli_query($conn,$sql_select_vetgetable_family_data);
    $result_vetgetable_family_data = mysqli_fetch_assoc($query_vetgetable_family_data);
    if(!$result_vetgetable_family_data){
        header("location: vetgetable_family.php");
    }

    // on edit vetgetable family
    if(isset($_POST['update_vetgetable_family'])){

        // get value from html tag
        $vf_engname = mysqli_real_escape_string($conn,$_POST['inputVFengname']);

        // edit vetgetable family data
        $sql_update_vetgetable_family = "UPDATE vetgetable_family SET VF_ENGNAME = '$vf_engname' WHERE VF_CODE = " .  $_SESSION['vf_code'];
        mysqli_query($conn,$sql_update_vetgetable_family);       
        $_SESSION['success'] = "แก้ไขข้อมูลวงศ์พืชสำเร็จ";
        header("location: ../vetgetable_family_edit.php");
    }

    // on del vetgetable family
    if(isset($_POST['del_vetgetable_family'])){
        // del vetgetable family
        $sql_del_vetgetable_family = "DELETE FROM vetgetable_family WHERE VF_CODE = " .  $_SESSION['vf_code'];
        mysqli_query($conn,$sql_del_vetgetable_family);

        // del all vetgetable with in this vetgetable family
        $sql_del_vetgetable = "DELETE FROM vetgetable WHERE VF_CODE = " .  $_SESSION['vf_code'];
        mysqli_query($conn,$sql_del_vetgetable);
        $_SESSION['warning'] = "ลบข้อมูลวงศ์พืชสำเร็จ รหัส : " . $_SESSION['vf_code'];
        unset($_SESSION['vf_code']);
        header("location: ../vetgetable_family.php");
    }
 
?>