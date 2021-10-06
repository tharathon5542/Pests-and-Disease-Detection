<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    // select farmer profile from database using farmercode that come from session
    $sql_select_farmer_data = "SELECT * FROM farmer WHERE F_CODE = " . $_SESSION['farmerCode'];
    $query_farmer_data = mysqli_query($conn,$sql_select_farmer_data);
    $result_farmer_data = mysqli_fetch_assoc($query_farmer_data);

    //on update user profile
    if(isset($_POST['update_user'])){
        
        // get value from html tag
        $f_name = mysqli_real_escape_string($conn,$_POST['inputFirstName']);
        $f_surname = mysqli_real_escape_string($conn,$_POST['inputLastName']);
        $tel = mysqli_real_escape_string($conn,$_POST['inputPhonenumber']);
        $postcode = mysqli_real_escape_string($conn,$_POST['inputPostcode']);
        $address = mysqli_real_escape_string($conn,$_POST['inputAddress']);
        $email = mysqli_real_escape_string($conn,$_POST['inputEmailAddress']);

        // if there an image to upload
        $fileName = $_FILES['fileUpload']['name'];
        if(!empty($fileName)){

            // define image type that allow
            $allowTypes = array('jpg','jpeg','png');

            // define variable for upload
            $fileTmp = $_FILES['fileUpload']['tmp_name'];
            $fileSize = $_FILES['fileUpload']['size'];
            $fileError = $_FILES['fileUpload']['error'];
            $fileType = $_FILES['fileUpload']['type'];

            // get actual file extend
            $fileExt = explode('.' , $fileName);
            $fileActualExt = strtolower(end($fileExt));

            // check file is in allow type
            if(in_array($fileActualExt,$allowTypes)){
                // check is there any file errors
                if($fileError === 0){
                    // check file size
                    if($fileSize < 1000000){

                        // generate new file name
                        $fileNameNew = $_SESSION['farmerCode'] . "." . $fileActualExt;
                        $fileDestination = "./images/profile/" . $_SESSION['farmerCode'];
                        $fileUploadDestination = $fileDestination . "/" . $fileNameNew;

                        // delete old image from server
                        array_map( 'unlink', array_filter((array) glob($fileDestination . "/" . "*") ) );

                        // upload image to server
                        move_uploaded_file($fileTmp,$fileUploadDestination);

                    }else{
                        // if image is too big
                        $_SESSION['errors'] = "ขนาดของภาพใหญ่เกินไป";
                        header('location: ../profile.php');
                    }
                }else{
                    // if file type not allow
                    $_SESSION['errors'] = "File ไม่สามารถใช้งานได้";
                    header('location: ../profile.php');
                }
            }else{
                // if file type not allow
                $_SESSION['errors'] = "ชนิดของ File ไม่ถูกต้อง";
                header('location: ../profile.php');
            }
        }
        
        // update farmer profile
        $sql_update_user = "UPDATE farmer SET F_NAME = '$f_name', F_SURNAME = '$f_surname', F_POSTCODE = '$postcode', F_ADDRESS = '$address', F_TEL = '$tel' WHERE F_CODE = " .  $_SESSION['farmerCode'] ;
        mysqli_query($conn,$sql_update_user);
        $_SESSION['success'] = "แก้ไขบัญชีสำเร็จ";
        header('location: ../profile.php');     
    }
?>