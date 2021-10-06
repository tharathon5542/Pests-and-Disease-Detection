<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    // create variable for pests code
    if(isset($_GET['pcode'])){
        // set pests code to dcode session
        $_SESSION['pcode'] = $_GET['pcode'];

        // delete tmp disease link vetgetable
        $sql_del_tmp_pests_link_vetgetable = "DELETE FROM tmp_pests_link_vetgetable";
        mysqli_query($conn,$sql_del_tmp_pests_link_vetgetable);



        // select pests data from database
        $sql_select_pests_data = "SELECT * FROM pests WHERE P_CODE = " . $_SESSION['pcode'];
        $query_pests_data = mysqli_query($conn,$sql_select_pests_data);
        $result_pests_data = mysqli_fetch_assoc($query_pests_data);
        // select pests link vetgetable for tmp pests link vetgetable
        $sql_select_pests_link_vetgetable = "SELECT V_CODE FROM pests_link_vetgetable WHERE P_CODE = " . $_SESSION['pcode'];
        $query_pests_link_vetgetable = mysqli_query($conn,$sql_select_pests_link_vetgetable);
        while($rows = mysqli_fetch_array($query_pests_link_vetgetable)){
            // insert pests link vetgetable to temporary pests link vetgetable
            $sql_insert_tmp_pests_link_vetgetable = "INSERT INTO tmp_pests_link_vetgetable (V_CODE) VALUES('".$rows['V_CODE']."')";
            mysqli_query($conn,$sql_insert_tmp_pests_link_vetgetable);
        }

        // select vetgetable data
        $sql_select_vetgetable_data = "SELECT * FROM vetgetable";
        $query_vetgetable_data = mysqli_query($conn,$sql_select_vetgetable_data);
        
        // select temporary pests link vetgetable for link table

        // $sql_select_tmp_pests_link_vetgetable = "SELECT * FROM pests_link_vetgetable WHERE P_CODE = " . $_SESSION['pcode'];
        $sql_select_tmp_pests_link_vetgetable = "SELECT * FROM tmp_pests_link_vetgetable";
        $query_tmp_pests_link_vetgetable = mysqli_query($conn,$sql_select_tmp_pests_link_vetgetable);

    }

    // on edit pests to database
    if(isset($_POST['edit_pests'])){

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

            // delete old image from server
            array_map( 'unlink', array_filter((array) glob("./images/pests/" . $p_code . "/" . "*") ) );

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

                            // upload image to server
                            move_uploaded_file($fileTmp,$fileUploadDestination);
                        }else{
                            // if file size is too big
                            $_SESSION['errors'] = "ขนาดของ File ใหญ่เกินกำหนด";
                            header('location: ../pests_edit.php'); 
                        }
                    }else{
                        // if file is have an errors
                        $_SESSION['errors'] = "File ไม่สามารถใช้งานได้";
                        header('location: ../pests_edit.php'); 
                    }
                }else{
                    // if file type not allow
                    $_SESSION['errors'] = "ชนิดของ File ไม่สามารถใช้งานได้";
                    header('location: ../pests_edit.php'); 
                }
            }
        }

        // delete old pests link vetgetable
        $sql_del_old_pests_link_vetgetable = "DELETE FROM pests_link_vetgetable WHERE P_CODE = $p_code";
        mysqli_query($conn,$sql_del_old_pests_link_vetgetable);
        // get new pests link vetgetable from tmp pests link vetgetable
        $sql_select_new_tmp_pests_link_vetgetable = "SELECT * FROM tmp_pests_link_vetgetable";
        $query_new_tmp_pests_link_vetgetable = mysqli_query($conn,$sql_select_new_tmp_pests_link_vetgetable);
        while($rows = mysqli_fetch_array($query_new_tmp_pests_link_vetgetable)){
            // insert new pests link vetgetable
            $sql_insert_new_pests_link_vetgetable = "INSERT INTO pests_link_vetgetable (P_CODE,V_CODE) VALUES('$p_code','". $rows['V_CODE'] ."')";
            mysqli_query($conn,$sql_insert_new_pests_link_vetgetable);
        }

        // delete tmp pests link vetgetable
        $sql_del_tmp_pests_link_vetgetable = "DELETE FROM tmp_pests_link_vetgetable";
        mysqli_query($conn,$sql_del_tmp_pests_link_vetgetable);

        // update pests data
        $sql_update_pests_data = "UPDATE pests SET P_THAINAME = '$p_thainame', P_ENGNAME = '$p_engname', P_SCINAME = '$p_sciname', P_DETAIL = '$p_detail', P_DESTRUCTION = '$p_destruction', P_REMEDY = '$p_remedy' WHERE P_CODE = $p_code";
        mysqli_query($conn,$sql_update_pests_data);
        $_SESSION['success'] = "แก้ไขข้อมูลแมลงศัตรูพืชสำเร็จ";
        header("location: ../pests_edit.php?pcode=" . $p_code);

    }

    // on delete pests
    if(isset($_GET['delpcode'])){

        // get value from html tag
        $p_code = $_GET['delpcode'];

        // sql del pests from database
        $sql_del_pests = "DELETE FROM pests WHERE P_CODE = $p_code";
        mysqli_query($conn,$sql_del_pests);

        // sql del pests link vetgetable
        $sql_del_pests_link_vetgetable = "DELETE FROM pests_link_vetgetable WHERE P_CODE = $p_code";
        mysqli_query($conn,$sql_del_pests_link_vetgetable);

        // delete tmp pests link vetgetable
        $sql_del_tmp_pests_link_vetgetable = "DELETE FROM tmp_pests_link_vetgetable";
        mysqli_query($conn,$sql_del_tmp_pests_link_vetgetable);

        // file destination variable
        $fileDestination = "./images/pests/" . $p_code;

        // delete old image from server
        array_map( 'unlink', array_filter((array) glob($fileDestination . "/" . "*") ) );

        //  delete directory
        rmdir($fileDestination);

        $_SESSION['warning'] = "ลบข้อมูลพืชสำเร็จ รหัส : " . $p_code;
        unset($_SESSION['pcode']);
        header("location: ./pests.php");
    }
 
?>