<?php 
    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // unset session from logout
    if(!empty($_GET['logout'])){
        unset($_SESSION['farmerCode']);
        unset($_SESSION['userRole']);
    }

    include("./src/login_db.php")
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Insect Pests and Plant Disease Detection</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>

        <!-- toast message -->   
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
        <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>

        <style>
            .loader {
                border: 16px solid #f3f3f3; /* Light grey */
                border-top: 16px solid #3498db; /* Blue */
                border-radius: 50%;
                width: 120px;
                height: 120px;
                animation: spin 2s linear infinite;
                }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>

    </head>
    <body class="" style="background-color: gray;">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header">
                                        <img src="src/images/college.png" style="width:200px; height:200px;display: block;margin-left: auto;margin-right: auto;"/>
                                        <h4 class="text-center font-weight-light my-4">ระบบตรวจแมลงศัตรูพืช และโรคพืช</h4>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" action="src/login_db.php">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputEmail">อีเมล</label>
                                                <?php 
                                                    if(isset($_GET['NoCode'])){
                                                        echo '<input id="hiddenNoCode" name="hiddenNoCode" type="hidden" value="'. $_GET['NoCode']. '"/>';
                                                    } 
                                                ?>
                                                
                                                <?php 
                                                    if(isset($_SESSION['email'])){
                                                        echo '<input class="form-control py-4" id="inputEmail" type="email" name="inputEmail" placeholder="Enter email address" value="' . $_SESSION['email'] . '" required />';
                                                        unset($_SESSION['email']);    
                                                    }else if(isset($_SESSION['rememberEmail'])){
                                                        echo '<input class="form-control py-4" id="inputEmail" type="email" name="inputEmail" placeholder="Enter email address" value="' . $_SESSION['rememberEmail'] . '" required />';
                                                    }else{
                                                        echo '<input class="form-control py-4" id="inputEmail" type="email" name="inputEmail" placeholder="Enter email address" required />';
                                                    }
                                                ?>          
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputPassword">รหัสผ่าน</label>
                                                <?php 
                                                    if(isset($_SESSION['password'])){
                                                        echo '<input class="form-control py-4" id="inputPassword" name="inputPassword" type="password" placeholder="Enter password" value="' . $_SESSION['password'] . '" required />';
                                                        unset($_SESSION['password']);
                                                    }else if(isset($_SESSION['rememberPass'])){
                                                        echo '<input class="form-control py-4" id="inputPassword" name="inputPassword" type="password" placeholder="Enter password" value="' . $_SESSION['rememberPass'] . '" required />';
                                                    }else{
                                                        echo '<input class="form-control py-4" id="inputPassword" name="inputPassword" type="password" placeholder="Enter password" required />';        
                                                    }
                                                ?>    
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <?php 
                                                        if(isset($_SESSION['rememberCheck'])){
                                                            echo '<input class="custom-control-input" id="rememberMeCheck" name="rememberMeCheck" type="checkbox" checked />';
                                                        }else{
                                                            echo '<input class="custom-control-input" id="rememberMeCheck" name="rememberMeCheck" type="checkbox" />';
                                                        }
                                                    ?> 
                                                    <label class="custom-control-label" for="rememberMeCheck">จดจำการเข้าสู่ระบบ</label>
                                                </div>
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="password.php"></a>
                                                <button class="btn btn-success" name="login_user">เข้าสู่ระบบ</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="register.php">สร้างบัญชีใหม่</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Toast message if there any error or success-->
                    <?php
                        if(isset($_SESSION['errors'])){
                            echo "<script type='text/javascript'>toastr.error('" . $_SESSION['errors'] . "')</script>";
                            unset($_SESSION['errors']); 
                        }
                        if(isset($_SESSION['success'])){
                            echo "<script type='text/javascript'>toastr.success('" . $_SESSION['success'] . "')</script>";
                            unset($_SESSION['success']); 
                        }       
                    ?>
                </main>
            </div>
            <!-- <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Pests and Disease Detection</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div> -->
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
