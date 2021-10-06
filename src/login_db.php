<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    // on login
    if(isset($_POST['login_user'])){

        // get value from html tag
        $email = mysqli_real_escape_string($conn,$_POST['inputEmail']);
        $password = mysqli_real_escape_string($conn,$_POST['inputPassword']);
        $hiddenNoCode = mysqli_real_escape_string($conn,$_POST['hiddenNoCode']);

        // encrypt password
        $check_password = md5($password);

        // check email & password to login
        $sql_check_login = "SELECT * FROM farmer WHERE F_EMAIL = '$email' AND F_PASSWORD = '$check_password'";
        $query_check_login = mysqli_query($conn,$sql_check_login);
        $result_check_login = mysqli_fetch_assoc($query_check_login);

        // if check login is success
        if($result_check_login){
            $_SESSION['farmerCode'] = $result_check_login['F_CODE'];
            $_SESSION['userRole'] = $result_check_login['USER_ROLE'];

            // check if remember me is check or not
            if(isset($_POST['rememberMeCheck'])){
                $_SESSION['rememberEmail'] = $email;
                $_SESSION['rememberPass'] = $password;
                $_SESSION['rememberCheck'] = '1';
            }else{
                unset($_SESSION['rememberEmail']);
                unset($_SESSION['rememberPass']);
                unset($_SESSION['rememberCheck']);
            }
            if(isset($hiddenNoCode) && strlen($hiddenNoCode) > 0){
                header('location: ../index.php?NoCode=' . $hiddenNoCode);
            }else{
                header('location: ../index.php');
            }

        }else{
        // if check login is fail
            $_SESSION['errors'] = "Email หรือ รหัสผ่านไม่ถูกต้อง";
            header('location: ../login.php');
        }

    }
?>