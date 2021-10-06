<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    // create variable for disease code
    if(isset($_GET['dcode'])){
        // set disease code to dcode session
        $_SESSION['dcode'] = $_GET['dcode'];

        // delete tmp disease link vetgetable
        $sql_del_tmp_disease_link_vetgetable = "DELETE FROM tmp_disease_link_vetgetable";
        mysqli_query($conn,$sql_del_tmp_disease_link_vetgetable);

        // select disease data from database
        $sql_select_disease_data = "SELECT * FROM disease WHERE D_CODE = " . $_SESSION['dcode'];
        $query_disease_data = mysqli_query($conn,$sql_select_disease_data);
        $result_disease_data = mysqli_fetch_assoc($query_disease_data);
        // select disease link vetgetable for tmp disease link vetgetable
        $sql_select_disease_link_vetgetable = "SELECT * FROM disease_link_vetgetable WHERE D_CODE = " . $_SESSION['dcode'];
        $query_disease_link_vetgetable = mysqli_query($conn,$sql_select_disease_link_vetgetable);
        while($rows = mysqli_fetch_array($query_disease_link_vetgetable)){
            // insert disease link vetgetable to temporary disease link vetgetable
            $sql_insert_tmp_disease_link_vetgetable = "INSERT INTO tmp_disease_link_vetgetable (V_CODE) VALUES('" . $rows['V_CODE'] ."')";
            mysqli_query($conn,$sql_insert_tmp_disease_link_vetgetable);
        }

        // select vetgetable data
        $sql_select_vetgetable_data = "SELECT * FROM vetgetable";
        $query_vetgetable_data = mysqli_query($conn,$sql_select_vetgetable_data);
        
        // select temporary disease link vetgetable for link table
        $sql_select_tmp_disease_link_vetgetable = "SELECT * FROM tmp_disease_link_vetgetable";
        $query_tmp_disease_link_vetgetable = mysqli_query($conn,$sql_select_tmp_disease_link_vetgetable);

    }

    // on edit disease to database
    if(isset($_POST['edit_disease'])){

        // get value from html tag
        $d_code = mysqli_real_escape_string($conn,$_POST['inputDiseasecode']);
        $d_name = mysqli_real_escape_string($conn,$_POST['inputDiseasename']);
        $d_symptom = mysqli_real_escape_string($conn,$_POST['inputDiseasesymptom']);
        $d_remedy = mysqli_real_escape_string($conn,$_POST['inputDiseaseremedy']);
        $d_ward = mysqli_real_escape_string($conn,$_POST['inputDiseaseward']);

        // check if there is an image to upload
        $fileNames = array_filter($_FILES['files']['name']);
        if(!empty($fileNames)){

            // create array that define what type of file allow
            $allowTypes = array('jpg','jpeg','png');

            // delete old image from server
            array_map( 'unlink', array_filter((array) glob("./images/diseases/" . $d_code . "/" . "*") ) );

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
                            $fileNameNew = $d_code . "_" . ($i += 1) . "." . $fileActualExt;
                            $fileDestination = "./images/diseases/" . $d_code;
                            $fileUploadDestination = $fileDestination . "/" . $fileNameNew;

                            // upload image to server
                            move_uploaded_file($fileTmp,$fileUploadDestination);
                        }else{
                            // if file size is too big
                            $_SESSION['errors'] = "ขนาดของ File ใหญ่เกินกำหนด";
                            header('location: ../disease_edit.php'); 
                        }
                    }else{
                        // if file is have an errors
                        $_SESSION['errors'] = "File ไม่สามารถใช้งานได้";
                        header('location: ../disease_edit.php'); 
                    }
                }else{
                    // if file type not allow
                    $_SESSION['errors'] = "ชนิดของ File ไม่สามารถใช้งานได้";
                    header('location: ../disease_edit.php'); 
                }
            }
        }

        // delete old disease link vetgetable
        $sql_del_old_disease_link_vetgetable = "DELETE FROM disease_link_vetgetable WHERE D_CODE = $d_code";
        mysqli_query($conn,$sql_del_old_disease_link_vetgetable);
        // get new disease link vetgetable from tmp disease link vetgetable
        $sql_select_new_tmp_disease_link_vetgetable = "SELECT * FROM tmp_disease_link_vetgetable";
        $query_new_tmp_disease_link_vetgetable = mysqli_query($conn,$sql_select_new_tmp_disease_link_vetgetable);
        while($rows = mysqli_fetch_array($query_new_tmp_disease_link_vetgetable)){
            // insert new disease link vetgetable
            $sql_insert_new_disease_link_vetgetable = "INSERT INTO disease_link_vetgetable (D_CODE,V_CODE) VALUES('$d_code','". $rows['V_CODE'] ."')";
            mysqli_query($conn,$sql_insert_new_disease_link_vetgetable);
        }

        // delete tmp disease link vetgetable
        $sql_del_tmp_disease_link_vetgetable = "DELETE FROM tmp_disease_link_vetgetable";
        mysqli_query($conn,$sql_del_tmp_disease_link_vetgetable);

        // update disease data
        $sql_update_disease_data = "UPDATE disease SET D_NAME = '$d_name', D_SYMPTOM = '$d_symptom', D_REMEDY = '$d_remedy', D_WARD = '$d_ward' WHERE D_CODE = $d_code";
        mysqli_query($conn,$sql_update_disease_data);
        $_SESSION['success'] = "แก้ไขข้อมูลโรคพืชผักสำเร็จ";
        header("location: ../disease_edit.php?dcode=" . $d_code);

    }

    // on delete disease
    if(isset($_GET['deldcode'])){

        // get value from html tag
        $d_code = $_GET['deldcode'];

        // sql del disease from database
        $sql_del_disease = "DELETE FROM disease WHERE D_CODE = $d_code";
        mysqli_query($conn,$sql_del_disease);

        // sql del disease link vetgetable
        $sql_del_disease_link_vetgetable = "DELETE FROM disease_link_vetgetable WHERE D_CODE = $d_code";
        mysqli_query($conn,$sql_del_disease_link_vetgetable);

        // delete tmp disease link vetgetable
        $sql_del_tmp_disease_link_vetgetable = "DELETE FROM tmp_disease_link_vetgetable";
        mysqli_query($conn,$sql_del_tmp_disease_link_vetgetable);

        // file destination variable
        $fileDestination = "./images/diseases/" . $d_code;

        // delete old image from server
        array_map( 'unlink', array_filter((array) glob($fileDestination . "/" . "*") ) );

        //  delete directory
        rmdir($fileDestination);

        $_SESSION['warning'] = "ลบข้อมูลโรคสำเร็จ รหัส : " . $d_code;
        unset($_SESSION['dcode']);
        header("location: ./disease.php");
    }
 
?>