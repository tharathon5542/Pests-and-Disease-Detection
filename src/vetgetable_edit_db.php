<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    // create variable for vcode
    if(isset($_GET['vcode'])){
        // set variable to session
        $_SESSION['vcode'] = $_GET['vcode'];
    }

    // select vetgetable data from database
    $sql_select_vetgetable_data = "SELECT * FROM vetgetable INNER JOIN vetgetable_family ON vetgetable.VF_CODE = vetgetable_family.VF_CODE WHERE V_CODE = " . $_SESSION['vcode'];
    $query_vetgetable_data = mysqli_query($conn,$sql_select_vetgetable_data);
    $result_vetgetable_data = mysqli_fetch_assoc($query_vetgetable_data);
    if(!$result_vetgetable_data){
        header("location: vetgetable.php");
    }

    // select vetgetable family data from database
    $sql_select_vetgetable_family_data = "SELECT * FROM vetgetable_family WHERE VF_CODE != " . $result_vetgetable_data['VF_CODE'];
    $query_vetgetable_family_data = mysqli_query($conn,$sql_select_vetgetable_family_data);

    // select vetgetable link disease
    $sql_select_disease_link_vetgetalbe = "SELECT * FROM disease_link_vetgetable WHERE V_CODE = " . $_SESSION['vcode'];
    $query_disease_link_vetgetable = mysqli_query($conn,$sql_select_disease_link_vetgetalbe);

    // select vetgetable link pests
    $sql_select_pests_link_vetgetalbe = "SELECT * FROM pests_link_vetgetable WHERE V_CODE = " . $_SESSION['vcode'];
    $query_pests_link_vetgetable = mysqli_query($conn,$sql_select_pests_link_vetgetalbe);

    // on edit vetgetable
    if(isset($_POST['edit_vetgetable'])){

        // get value from html tag
        $v_thainame = mysqli_real_escape_string($conn,$_POST['inputVetgetablethainame']);
        $v_engname = mysqli_real_escape_string($conn,$_POST['inputVetgetableengname']);
        $v_sciname = mysqli_real_escape_string($conn,$_POST['inputVetgetablesciname']);
        $v_detail = mysqli_real_escape_string($conn,$_POST['inputVetgetabledetail']);
        $v_plant = mysqli_real_escape_string($conn,$_POST['inputVetgetableplant']);
        $v_planting = mysqli_real_escape_string($conn,$_POST['inputVetgetableplanting']);
        $v_ward = mysqli_real_escape_string($conn,$_POST['inputVetgetableward']);
        $v_harvest = mysqli_real_escape_string($conn,$_POST['inputVetgetableharvest']);
        $vf_family = mysqli_real_escape_string($conn,$_POST['selectVetgetablefamily']);

        // check if there is an image to upload
        $fileNames = array_filter($_FILES['files']['name']);
        if(!empty($fileNames)){

            // create array that define what type of file allow
            $allowTypes = array('jpg','jpeg','png');

            // file destination variable
            $fileDestination = "./images/vetgetables/" . $_SESSION['vcode'];

            // delete old image from server
            array_map( 'unlink', array_filter((array) glob($fileDestination . "/" . "*") ) );

            // create loop count for file name
            $i = 0;
            // loop for multiple images upload
            foreach($_FILES['files']['name'] as $key=>$val){

                // define file properties
                $fileName = $_FILES['files']['name'][$key];
                $fileTmp = $_FILES['files']['tmp_name'][$key];
                $fileSize = $_FILES['files']['size'][$key];
                $fileError = $_FILES['files']['error'][$key];
                $fileType = $_FILES['files']['type'][$key];

                // get actual file extend
                $fileExt = explode('.' , $fileName);
                $fileActualExt = strtolower(end($fileExt));

                // check if file type is allow
                if(in_array($fileActualExt, $allowTypes)){
                    // if there is no file errors
                    if($fileError === 0){
                        // check file size
                        if($fileSize < 1000000){
                            // create new file name
                            $fileNameNew = $_SESSION['vcode'] . "_" . ($i += 1) . "." . $fileActualExt;
                            $fileUploadDestination = $fileDestination . "/" . $fileNameNew;

                            // upload image to server
                            move_uploaded_file($fileTmp,$fileUploadDestination);
                        }else{
                            // if file size is too big
                            $_SESSION['errors'] = "ขนาดของ File ใหญ่เกินกำหนด";
                            header('location: ../vetgetable_edit.php?vcode =' . $_SESSION['vcode']); 
                        }
                    }else{
                        // if file is have an errors
                        $_SESSION['errors'] = "File ไม่สามารถใช้งานได้";
                        header('location: ../vetgetable_edit.php?vcode =' . $_SESSION['vcode']); 
                    }
                }else{
                    // if file type not allow
                    $_SESSION['errors'] = "ชนิดของ File ไม่สามารถใช้งานได้";
                    header('location: ../vetgetable_edit.php?vcode =' . $_SESSION['vcode']); 
                }
            }
        }

        // update vetgetable data
        $sql_update_vetgetable_data = "UPDATE vetgetable SET V_THAINAME = '$v_thainame', V_ENGNAME = '$v_engname', V_SCINAME = '$v_sciname', V_DETAIL = '$v_detail'
        , V_DETAIL = '$v_detail', V_PLANT = '$v_plant', V_PLANTING = '$v_planting', V_WARD = '$v_ward', V_HARVEST = '$v_harvest', VF_CODE = '$vf_family' WHERE V_CODE = " . $_SESSION['vcode'];
        mysqli_query($conn,$sql_update_vetgetable_data);
        $_SESSION['success'] = "แก้ไขข้อมูลพืชสำเร็จ";

        // remove chach
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Content-Type: application/xml; charset=utf-8");
        header("location: ../vetgetable_edit.php?vcode=" . $_SESSION['vcode']);
    }

    // on del vetgetable
    if(isset($_GET['delvcode'])){

        // sql delete vetgetable
        $sql_del_vetgetable = "DELETE FROM vetgetable WHERE V_CODE = " . $_GET['delvcode'];
        mysqli_query($conn,$sql_del_vetgetable);

        // sql delete vetgetable from planting
        $sql_del_planting = "DELETE FROM plant WHERE V_CODE = " . $_GET['delvcode'];
        mysqli_query($conn,$sql_del_planting);

        // sql delete vetgetable from disease link vetgetable
        $sql_del_disease_link_vetgetable = "DELETE FROM disease_link_vetgetable WHERE V_CODE = " . $_GET['delvcode'];
        mysqli_query($conn,$sql_del_disease_link_vetgetable);

        // file destination variable
        $fileDestination = "./images/vetgetables/" . $_GET['delvcode'];

        // delete old image from server
        array_map( 'unlink', array_filter((array) glob($fileDestination . "/" . "*") ) );

        //  delete directory
        rmdir($fileDestination);

        $_SESSION['warning'] = "ลบข้อมูลพืชสำเร็จ รหัส : " . $_GET['delvcode'];
        unset($_SESSION['vcode']);
        header("location: ./vetgetable.php");
    }
?>