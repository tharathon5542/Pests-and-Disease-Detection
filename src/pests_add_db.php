<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    // get last pests code
    $sql_select_last_pests_code = "SELECT MAX(P_CODE) AS LAST_P_CODE FROM pests";
    $query_last_pests_code = mysqli_query($conn,$sql_select_last_pests_code);
    $result_last_pests_code = mysqli_fetch_assoc($query_last_pests_code);
    if($result_last_pests_code){
        $last_p_code = substr("0000" . (intval($result_last_pests_code['LAST_P_CODE']) + 1),-5);
    }else{
        $last_p_code = "00001";
    }

    // get vetgetable data from database
    $sql_select_vetgetable_data = "SELECT * FROM vetgetable";
    $query_vetgetable_data = mysqli_query($conn,$sql_select_vetgetable_data);

    // on add disease to database
    if(isset($_POST['add_pests'])){

        // get value from html tag
        $p_code = mysqli_real_escape_string($conn,$_POST['inputPestscode']);
        $p_thainame = mysqli_real_escape_string($conn,$_POST['inputPeststhainame']);
        $p_engname = mysqli_real_escape_string($conn,$_POST['inputPestsengname']);
        $p_sciname = mysqli_real_escape_string($conn,$_POST['inputPestssciname']);
        $p_detail = mysqli_real_escape_string($conn,$_POST['inputPestsdetail']);
        $p_destruction = mysqli_real_escape_string($conn,$_POST['inputPestsdestruction']);
        $p_remedy = mysqli_real_escape_string($conn,$_POST['inputPestsremedy']);

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
                            $fileNameNew = $p_code . "_" . ($i += 1) . "." . $fileActualExt;
                            $fileDestination = "./images/pests/" . $p_code;
                            $fileUploadDestination = $fileDestination . "/" . $fileNameNew;

                            // create new directory 
                            mkdir($fileDestination,0777,true);

                            // upload image to server
                            move_uploaded_file($fileTmp,$fileUploadDestination);
                        }else{
                            // if file size is too big
                            $_SESSION['errors'] = "ขนาดของ File ใหญ่เกินกำหนด";
                            header('location: ../pests_add.php'); 
                        }
                    }else{
                        // if file is have an errors
                        $_SESSION['errors'] = "File ไม่สามารถใช้งานได้";
                        header('location: ../pests_add.php'); 
                    }
                }else{
                    // if file type not allow
                    $_SESSION['errors'] = "ชนิดของ File ไม่สามารถใช้งานได้";
                    header('location: ../pests_add.php'); 
                }
            }
        }

        // create new directory 
        mkdir("./images/pests/" . $p_code,0777,true);

        // insert pests data
        $sql_insert_pests_data = "INSERT INTO pests VALUES('$p_code','$p_thainame','$p_engname','$p_sciname','$p_detail','$p_destruction','$p_remedy')";
        mysqli_query($conn,$sql_insert_pests_data);

        // get temporary pests link vetgetable
        $sql_select_tmp_pests_link_vetgetable = "SELECT V_CODE FROM tmp_pests_link_vetgetable";
        $query_tmp_pests_link_vetgetable = mysqli_query($conn,$sql_select_tmp_pests_link_vetgetable);
        while($rows = mysqli_fetch_array($query_tmp_pests_link_vetgetable)){
            // insert pests link vetgetable
            $sql_insert_pests_link_vetgetable = "INSERT INTO pests_link_vetgetable (P_CODE,V_CODE) VALUES('$p_code','" . $rows['V_CODE'] . "')";
            mysqli_query($conn,$sql_insert_pests_link_vetgetable);
        }

        // delete tmp pests link vetgetable
        $sql_del_tmp_pests_link_vetgetable = "DELETE FROM tmp_pests_link_vetgetable";
        mysqli_query($conn,$sql_del_tmp_pests_link_vetgetable);

        $_SESSION['success'] = "เพิ่มข้อมูลแมลงศัตรูพืชสำเร็จ";
        header("location: ../pests.php");

    }else{
        // delete tmp pests link vetgetable
        $sql_del_tmp_pests_link_vetgetable = "DELETE FROM tmp_pests_link_vetgetable";
        mysqli_query($conn,$sql_del_tmp_pests_link_vetgetable);
    }
 
?>