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

    // select last vetgetable code
    $sql_select_last_vetgetable_code = "SELECT MAX(V_CODE) AS LAST_V_CODE FROM vetgetable";
    $query_last_vetgetable_code = mysqli_query($conn,$sql_select_last_vetgetable_code);
    $result_last_vetgetable_code = mysqli_fetch_assoc($query_last_vetgetable_code);
    $last_v_code = substr("0000" . (intval($result_last_vetgetable_code['LAST_V_CODE']) + 1),-5);
 
    // on add vetgetable
    if(isset($_POST['add_vetgetable'])){

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
                            $fileNameNew = $last_v_code . "_" . ($i += 1) . "." . $fileActualExt;
                            $fileDestination = "./images/vetgetables/" . $last_v_code;
                            $fileUploadDestination = $fileDestination . "/" . $fileNameNew;

                            // create new directory 
                            mkdir($fileDestination,0777,true);

                            // upload image to server
                            move_uploaded_file($fileTmp,$fileUploadDestination);
                        }else{
                            // if file size is too big
                            $_SESSION['errors'] = "ขนาดของ File ใหญ่เกินกำหนด";
                            header('location: ../vetgetable_add.php'); 
                        }
                    }else{
                        // if file is have an errors
                        $_SESSION['errors'] = "File ไม่สามารถใช้งานได้";
                        header('location: ../vetgetable_add.php'); 
                    }
                }else{
                    // if file type not allow
                    $_SESSION['errors'] = "ชนิดของ File ไม่สามารถใช้งานได้";
                    header('location: ../vetgetable_add.php'); 
                }
            }
        }

        // create new directory 
        mkdir("./images/vetgetables/" . $last_v_code,0777,true);

        // insert vetgetable data
        $sql_insert_vetgetable_data = "INSERT INTO vetgetable VALUES('$last_v_code','$v_thainame','$v_engname'
        ,'$v_sciname','$v_detail','$v_plant','$v_planting','$v_ward','$v_harvest','$vf_family')";
        mysqli_query($conn,$sql_insert_vetgetable_data);
        $_SESSION['success'] = "เพิ่มข้อมูลพืชสำเร็จ";
        header("location: ../vetgetable.php");
    }
?>