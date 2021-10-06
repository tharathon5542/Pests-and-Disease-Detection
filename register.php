<?php 
    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }
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

    </head>
    <body style="background-color: gray;">
        <div id="layoutAuthentication" >
            <div id="layoutAuthentication_content" style="margin-bottom: 50px;">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h4 class="text-center font-weight-light my-4">สร้างบัญชีใหม่</h4></div>
                                    <div class="card-body">
                                        <form method="post" action="src/register_db.php" enctype="multipart/form-data">
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputFirstName">ชื่อ</label>
                                                        <?php 
                                                            if(!isset($_SESSION['tmp_fname'])){
                                                                echo '<input class="form-control py-4" id="inputFirstName" name="inputFirstName" type="text" placeholder="Enter first name *" required  />';
                                                            }else{
                                                                echo '<input class="form-control py-4" id="inputFirstName" name="inputFirstName" type="text" placeholder="Enter first name *" value="'. $_SESSION['tmp_fname'] .'" required />';
                                                                unset($_SESSION['tmp_fname']);
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputLastName">นามสกุล</label>
                                                        <?php 
                                                            if(!isset($_SESSION['tmp_fsurname'])){
                                                                echo '<input class="form-control py-4" id="inputLastName" name="inputLastName" type="text" placeholder="Enter last name *" required />';
                                                            }else{
                                                                echo '<input class="form-control py-4" id="inputLastName" name="inputLastName" type="text" placeholder="Enter last name *" value="' . $_SESSION['tmp_fsurname'] . '" required />';
                                                                unset($_SESSION['tmp_fsurname']);
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputPhonenumber">หมายเลขโทรศัพท์</label>
                                                        <?php 
                                                            if(!isset($_SESSION['tmp_ftel'])){
                                                                echo '<input class="form-control py-4" id="inputPhonenumber" name="inputPhonenumber"  type="text" placeholder="Enter Phone Number" maxlength="10" minlength="10" />';
                                                            }else{
                                                                echo '<input class="form-control py-4" id="inputPhonenumber" name="inputPhonenumber"  type="text" placeholder="Enter Phone Number" value="' . $_SESSION['tmp_ftel'] . '" maxlength="10" minlength="10" />';
                                                                unset($_SESSION['tmp_ftel']);
                                                            }
                                                        ?>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputPostcode">รหัสไปษณีย์</label>
                                                        <?php 
                                                            if(!isset($_SESSION['tmp_fpostcode'])){
                                                                echo '<input class="form-control py-4" id="inputPostcode" name="inputPostcode"  type="text" placeholder="Enter post code"  maxlength="5" minlength="5" />';
                                                            }else{
                                                                echo '<input class="form-control py-4" id="inputPostcode" name="inputPostcode"  type="text" placeholder="Enter post code" value="' . $_SESSION['tmp_fpostcode'] . '" maxlength="5" minlength="5" />';
                                                                unset($_SESSION['tmp_fpostcode']);
                                                            }
                                                        ?> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputAddress">ที่อยู่</label>
                                                <?php 
                                                    if(!isset($_SESSION['tmp_faddress'])){
                                                        echo '<input class="form-control py-4" id="inputAddress" name="inputAddress" placeholder="Enter address" />';
                                                    }else{
                                                        echo '<input class="form-control py-4" id="inputAddress" name="inputAddress" placeholder="Enter address" value="' . $_SESSION['tmp_faddress'] . '" />';
                                                        unset($_SESSION['tmp_faddress']);
                                                    }
                                                ?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputEmailAddress">อีเมล</label>
                                                <input class="form-control py-4" id="inputEmailAddress" name="inputEmailAddress" type="email" aria-describedby="emailHelp" placeholder="Enter email address *" required />
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputPassword">รหัสผ่าน</label>
                                                        <input class="form-control py-4" id="inputPassword" name="inputPassword" type="password" placeholder="Enter password *" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputConfirmPassword">ยืนยันรหัสผ่าน</label>
                                                        <input class="form-control py-4" id="inputConfirmPassword" name="inputConfirmPassword" type="password" placeholder="Confirm password *" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="fileUpload">รูปโปรไฟล์</label>
                                                <input class="form-control py-1" id="fileUpload" name="fileUpload" type="file" />
                                            </div>
                                            <div class="form-group">
                                                <div id="image-holder" ></div>
                                            </div>
                                            <div class="form-group mt-4 mb-0"><button  class="btn btn-success" name="register_user" id="register_user" >สร้างบัญชีใหม่</button></div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="login.php">เข้าสู่ระบบ</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Toast message if there any error -->
                    <?php
                        if(isset($_SESSION['errors'])){
                            echo "<script type='text/javascript'>toastr.error('" . $_SESSION['errors'] . "')</script>";
                            unset($_SESSION['errors']); 
                        }       
                    ?>

                </main>
            </div>
            <!-- <div id="layoutAuthentication_footer" style="margin-top: 50px;">
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

        <!-- jquery to preview image before upload -->
        <script>
            $("#fileUpload").on('change', function () {
                var imgPath = $(this)[0].value;
                var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();

                if (extn == "png" || extn == "jpg" || extn == "jpeg") {
                    if (typeof (FileReader) != "undefined") {
                        var image_holder = $("#image-holder");
                        image_holder.empty();

                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $("<img />", {
                                "src": e.target.result,
                                    "class": "thumb-image",
                                    "style": "border-radius: 50%;width: 200px;height: 200px;border: 1px solid #555;margin-bottom: 20px;"
                            }).appendTo(image_holder);
                            $('#register_user').attr("disabled",false);
                        }
                        image_holder.show();
                        reader.readAsDataURL($(this)[0].files[0]);
                    } else {
                        alert("This browser does not support FileReader.");
                    }
                } else {
                    alert("Pls select only images");
                    $('#register_user').attr("disabled",true);
                }
                });
        </script>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
