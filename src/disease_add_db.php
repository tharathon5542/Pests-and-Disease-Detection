<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    // get last disease code
    $sql_select_last_disease_code = "SELECT MAX(D_CODE) AS LAST_D_CODE FROM disease";
    $query_last_disease_code = mysqli_query($conn,$sql_select_last_disease_code);
    $result_last_disease_code = mysqli_fetch_assoc($query_last_disease_code);
    if($result_last_disease_code){
        $last_d_code = substr("0000" . (intval($result_last_disease_code['LAST_D_CODE']) + 1),-5);
    }else{
        $last_d_code = "00001";
    }

    // get vetgetable data from database
    $sql_select_vetgetable_data = "SELECT * FROM vetgetable";
    $query_vetgetable_data = mysqli_query($conn,$sql_select_vetgetable_data);

    // on add disease to database
    if(isset($_POST['add_disease'])){

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

                            // create new directory 
                            mkdir($fileDestination,0777,true);

                            // upload image to server
                            move_uploaded_file($fileTmp,$fileUploadDestination);
                        }else{
                            // if file size is too big
                            $_SESSION['errors'] = "ขนาดของ File ใหญ่เกินกำหนด";
                            header('location: ../disease_add.php'); 
                        }
                    }else{
                        // if file is have an errors
                        $_SESSION['errors'] = "File ไม่สามารถใช้งานได้";
                        header('location: ../disease_add.php'); 
                    }
                }else{
                    // if file type not allow
                    $_SESSION['errors'] = "ชนิดของ File ไม่สามารถใช้งานได้";
                    header('location: ../disease_add.php'); 
                }
            }
        }

        // create new directory 
        mkdir("./images/diseases/" . $d_code,0777,true);

        // insert disease data
        $sql_insert_disease_data = "INSERT INTO disease VALUES('$d_code','$d_name','$d_symptom','$d_remedy','$d_ward')";
        mysqli_query($conn,$sql_insert_disease_data);

        // get temporary disease link vetgetable
        $sql_select_tmp_disease_link_vetgetable = "SELECT * FROM tmp_disease_link_vetgetable";
        $query_tmp_disease_link_vetgetable = mysqli_query($conn,$sql_select_tmp_disease_link_vetgetable);
        while($rows = mysqli_fetch_array($query_tmp_disease_link_vetgetable)){
            // insert disease link vetgetable
            $sql_insert_disease_link_vetgetable = "INSERT INTO disease_link_vetgetable (D_CODE,V_CODE) VALUES('$d_code','" . $rows['V_CODE'] . "')";
            mysqli_query($conn,$sql_insert_disease_link_vetgetable);
        }

        // delete tmp disease link vetgetable
        $sql_del_tmp_disease_link_vetgetable = "DELETE FROM tmp_disease_link_vetgetable";
        mysqli_query($conn,$sql_del_tmp_disease_link_vetgetable);

        $_SESSION['success'] = "เพิ่มข้อมูลโรคพืชสำเร็จ";
        header("location: ../disease.php");

    }else{
        // delete tmp disease link vetgetable
        $sql_del_tmp_disease_link_vetgetable = "DELETE FROM tmp_disease_link_vetgetable";
        mysqli_query($conn,$sql_del_tmp_disease_link_vetgetable);
    }
 
?>