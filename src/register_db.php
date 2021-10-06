<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    // on register
    if(isset($_POST['register_user'])){

        // get value from html tag
        $f_name = mysqli_real_escape_string($conn,$_POST['inputFirstName']);
        $f_surname = mysqli_real_escape_string($conn,$_POST['inputLastName']);
        $tel = mysqli_real_escape_string($conn,$_POST['inputPhonenumber']);
        $postcode = mysqli_real_escape_string($conn,$_POST['inputPostcode']);
        $address = mysqli_real_escape_string($conn,$_POST['inputAddress']);
        $email = mysqli_real_escape_string($conn,$_POST['inputEmailAddress']);
        $password = mysqli_real_escape_string($conn,$_POST['inputPassword']);
        $confirm_password = mysqli_real_escape_string($conn,$_POST['inputConfirmPassword']);

        // check email is used or not
        $sql_check_email = "SELECT * FROM farmer WHERE F_EMAIL = '$email' ";
        $query_check_email = mysqli_query($conn,$sql_check_email);
        $result_check_email = mysqli_fetch_assoc($query_check_email);
        
        // if check email is success
        if(!$result_check_email){
            // check password and confirm password is the same
            if($password === $confirm_password){
                // if check password is success
                // get last farmer code from database
                $sql_get_last_farmer_code = "SELECT MAX(F_CODE) AS F_CODE FROM farmer";
                $query_get_last_farmer_code = mysqli_query($conn,$sql_get_last_farmer_code);
                $result_get_last_farmer_code = mysqli_fetch_assoc($query_get_last_farmer_code);
                
                // check if there is no data in database
                if(!$result_get_last_farmer_code){
                    $new_farmer_code = "00001";
                }else{
                    // + 1 for new farmer code
                    $sum_farmer_code = intval($result_get_last_farmer_code['F_CODE']) + 1;
                    $last_farmer_code = "0000" . $sum_farmer_code;
                    $new_farmer_code = substr($last_farmer_code,-5);
                }    

                // encrypt password
                $new_password = md5($confirm_password);
          
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
                                $fileNameNew = $new_farmer_code . "." . $fileActualExt;
                                $fileDestination = "./images/profile/" . $new_farmer_code;
                                $fileUploadDestination = $fileDestination . "/" . $fileNameNew; 
                                // create new directory 
                                mkdir($fileDestination,0777,true);
                                // upload image to server
                                move_uploaded_file($fileTmp,$fileUploadDestination);

                                // insert data to database
                                $sql_insert_famer = "INSERT INTO farmer VALUES('$new_farmer_code','$f_name','$f_surname','$postcode','$address','$email','$new_password','$tel','user');";
                                mysqli_query($conn,$sql_insert_famer);
                                $_SESSION['email'] = $email;
                                $_SESSION['password'] = $confirm_password;
                                $_SESSION['success'] = "สร้างบัญชีสำเร็จ";
                                header('location: ../login.php');
                            }else{
                                // if file size is too big
                                // create session for temp user info
                                // user don't have to input info again
                                $_SESSION['tmp_fname'] = $f_name;
                                $_SESSION['tmp_fsurname'] = $f_surname;
                                $_SESSION['tmp_ftel'] = $tel;
                                $_SESSION['tmp_fpostcode'] = $postcode;
                                $_SESSION['tmp_faddress'] = $address;
                                $_SESSION['errors'] = "ขนาดของภาพใหญ่เกินไป";
                                header('location: ../register.php');
                            }
                        }else{
                            // if there is an erros in file upload
                            // create session for temp user info
                            // user don't have to input info again
                            $_SESSION['tmp_fname'] = $f_name;
                            $_SESSION['tmp_fsurname'] = $f_surname;
                            $_SESSION['tmp_ftel'] = $tel;
                            $_SESSION['tmp_fpostcode'] = $postcode;
                            $_SESSION['tmp_faddress'] = $address;
                            $_SESSION['errors'] = "File ไม่สามารถใช้งานได้";
                            header('location: ../register.php');
                        } 
                    }else{
                        // if file type not allow
                        // create session for temp user info
                        // user don't have to input info again
                        $_SESSION['tmp_fname'] = $f_name;
                        $_SESSION['tmp_fsurname'] = $f_surname;
                        $_SESSION['tmp_ftel'] = $tel;
                        $_SESSION['tmp_fpostcode'] = $postcode;
                        $_SESSION['tmp_faddress'] = $address;
                        $_SESSION['errors'] = "ชนิดของ File ไม่ถูกต้อง";
                        header('location: ../register.php');
                    }
                }else{
                    // if there is no image to upload
                    // create directory for furture
                    $fileDestination = "./images/profile/" . $new_farmer_code;
                    // create new directory 
                    mkdir($fileDestination,0777,true);

                    // insert data to database
                    $sql_insert_famer = "INSERT INTO farmer VALUES('$new_farmer_code','$f_name','$f_surname','$postcode','$address','$email','$new_password','$tel','user');";
                    mysqli_query($conn,$sql_insert_famer);
                    $_SESSION['email'] = $email;
                    $_SESSION['password'] = $confirm_password;
                    $_SESSION['success'] = "สร้างบัญชีสำเร็จ";
                    header('location: ../login.php');
                }
            }else{
                // if check password is fail
                // create session for temp user info
                // user don't have to input info again
                $_SESSION['tmp_fname'] = $f_name;
                $_SESSION['tmp_fsurname'] = $f_surname;
                $_SESSION['tmp_ftel'] = $tel;
                $_SESSION['tmp_fpostcode'] = $postcode;
                $_SESSION['tmp_faddress'] = $address;
                $_SESSION['errors'] = "Password ไม่ตรงกัน";
                header('location: ../register.php');
            }
        }else{
            // if check email is fail
            // create session for temp user info
            // user don't have to input info again
            $_SESSION['tmp_fname'] = $f_name;
            $_SESSION['tmp_fsurname'] = $f_surname;
            $_SESSION['tmp_ftel'] = $tel;
            $_SESSION['tmp_fpostcode'] = $postcode;
            $_SESSION['tmp_faddress'] = $address;
            $_SESSION['errors'] = "มี Email นี้อยู่ในระบบแล้ว";
            header('location: ../register.php');
        }
    }
?>