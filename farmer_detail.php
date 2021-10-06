<?php

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // check if username session is set
    if(!isset($_SESSION['farmerCode'])){
        header('location: login.php');
    }

    // check is user an admin
    if($_SESSION['userRole'] !== "admin"){
        header('location: index.php');
    }

    // include profile_db to manage page
    include('src/farmer_detail_db.php');

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
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        
        <!-- toast message -->   
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
        <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>

    </head>
    <body>
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">Insect Pests and Plant Disease Detection</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" style="margin-left: 10%;" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <!-- <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div> -->
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="profile.php">บัญชีผู้ใช้</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="login.php?logout='1'">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                หน้าแรก
                            </a>
                            <?php 
                                // check if user is admin
                                if($_SESSION['userRole'] === "admin"){
                                    echo '<a class="nav-link" href="farmer.php">
                                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                            ข้อมูลผู้ปลูก
                                         </a>';
                                }
                            ?>
                            <a class="nav-link" href="vetgetable_family.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                ข้อมูลวงศ์พืช
                            </a>
                            <a class="nav-link" href="vetgetable.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                ข้อมูลพืชผักสวนครัว
                            </a>
                            <a class="nav-link" href="disease.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                ข้อมูลโรคพืชผัก
                            </a>
                            <a class="nav-link" href="pests.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                ข้อมูลแมลงศัตรูพืช
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?php 
                            echo $_SESSION['userRole'];
                        ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">ข้อมูลผู้ปลูก</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                            <li class="breadcrumb-item"><a href="farmer.php">ข้อมูลผู้ปลูก</a></li>
                            <li class="breadcrumb-item active"><?php echo $farmer_code_detail; ?></li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header"><h4 class="font-weight-light">รายละเอียดของ <?php echo $result_farmer_data['F_NAME'] . ' ' . $result_farmer_data['F_SURNAME'] ?> </h4></div>
                            <div class="card-body">
                                <div class="form-row">
                                    <?php 
                                        // scan dir for profile image and set to img tag
                                        $profile_img_dir = "src/images/profile" . "/" . $farmer_code_detail;
                                        $files = scandir($profile_img_dir,1);
                                        $profile_img = $files[0];
                                        if($profile_img === ".."){
                                            echo '<img src="src/images/profile/default_avatar.png" alt="Avatar" style="border-radius: 50%;width: 200px;height: 200px;border: 1px solid #555;margin-bottom: 20px;">';
                                        }else{
                                            echo '<img src="'. $profile_img_dir . "/" . $profile_img .'" alt="Avatar" style="border-radius: 50%;width: 200px;height: 200px;border: 1px solid #555;margin-bottom: 20px;">';
                                        }
                                        
                                    ?>   
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputFirstName">Name</label>
                                            <input class="form-control py-4" id="inputFirstName" name="inputFirstName" type="text" value="<?php echo $result_farmer_data['F_NAME'] ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputLastName">Surname</label>
                                            <input class="form-control py-4" id="inputLastName" name="inputLastName" type="text" value="<?php echo $result_farmer_data['F_SURNAME'] ?>" readonly />
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputPhonenumber">Phone number</label>
                                            <input class="form-control py-4" id="inputPhonenumber" name="inputPhonenumber" type="text" value="<?php echo $result_farmer_data['F_TEL'] ?>" maxlength="10" minlength="10" readonly />
                                        </div>
                                    </div> 
                                </div>
                                <div class="form-row"> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputEmailAddress">E-mail</label>
                                            <input class="form-control py-4" id="inputEmailAddress" name="inputEmailAddress" type="email" aria-describedby="emailHelp" value="<?php echo $result_farmer_data['F_EMAIL'] ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputAddress">Address</label>
                                            <input class="form-control py-4" id="inputAddress" name="inputAddress" type="text" value="<?php echo $result_farmer_data['F_ADDRESS'] ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputPostcode">Postcode</label>
                                            <input class="form-control py-4" id="inputPostcode" name="inputPostcode" type="text" value="<?php echo $result_farmer_data['F_POSTCODE'] ?>" maxlength="5" minlength="5" readonly />
                                        </div>
                                    </div>
                                </div>
                                <div style="height: 5vh"></div>               
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                ตารางแสดงรายการตรวจพบ โรค และ แมลงศัตรูพืชผักสวนครัว
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>รหัสการตรวจพบ</th>
                                                <th>รายละเอียดการตรวจพบ</th>
                                                <th>ชนิดการตรวจพบ</th>
                                                <th>วันที่ เวลา การตรวจพบ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                while($rows = mysqli_fetch_array($query_notify_data)){
                                                    // check detect type
                                                    if($rows['NO_DETAIL_TYPE'] == '0'){
                                                        // select disease data
                                                        $sql_select_disease_data = "SELECT D_NAME FROM disease WHERE D_CODE =" . $rows['NO_DETAIL'];
                                                        $query_disease_data = mysqli_query($conn,$sql_select_disease_data);
                                                        $result_disease_data = mysqli_fetch_assoc($query_disease_data);
                                                        $detect = $result_disease_data['D_NAME'];
                                                        $detect_type = "โรค";
                                                    }else{
                                                        // select pests data
                                                        $sql_select_pests_data = "SELECT P_THAINAME FROM pests WHERE P_CODE =" . $rows['NO_DETAIL'];
                                                        $query_pests_data = mysqli_query($conn,$sql_select_pests_data);
                                                        $result_pests_data = mysqli_fetch_assoc($query_pests_data);
                                                        $detect = $result_pests_data['P_THAINAME'];
                                                        $detect_type = "แมลง";
                                                    }
                                                    echo '<tr>';
                                                    echo '<td>' . $rows['NO_CODE'] . '</td>';
                                                    echo '<td>' . $detect . '</td>';
                                                    echo '<td>' . $detect_type . '</td>';
                                                    echo '<td>' . $rows['NO_DATE'] . '</td>';
                                                    echo '</tr>';
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>                
                        <div class="card mb-4">
                            <div class="card-header">
                            <i class="fas fa-table mr-1"></i>
                            ตารางแสดงรายการพืชผักที่เกษตกรปลูก
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>แปลงที่ปลูก</th>
                                                <th>ชื่อพืชผักสวนครัว (ไทย)</th>
                                                <th>จำนวนผลผลิต</th>
                                                <th>วันที่ปลูก</th>
                                                <th>วันเก็บเกี่ยว</th>
                                                <th>สถานะการปลูก</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                while($rows = mysqli_fetch_array($query_plant_data)){
                                                    $sql_select_vetgetable_data = "SELECT V_THAINAME FROM vetgetable WHERE V_CODE =" . $rows['V_CODE'];
                                                    $query_vetgetable_data = mysqli_query($conn,$sql_select_vetgetable_data);
                                                    $result_vetgetable_data = mysqli_fetch_assoc($query_vetgetable_data);
                                                    echo '<tr>';
                                                    echo '<td>' . $rows['P_FARM_CODE'] . '</td>';
                                                    echo '<td>' . $result_vetgetable_data['V_THAINAME'] . '</td>';
                                                    echo '<td>' . $rows['P_NUM'] . '</td>';
                                                    echo '<td>' . $rows['P_DATE'] . '</td>';
                                                    echo '<td>' . $rows['P_DATE'] . '</td>';
                                                    echo '<td>' . $rows['P_STATUS'] . '</td>';
                                                    echo '</tr>';
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 
                        <div class="card mb-4"><div class="card-body">When scrolling, the navigation stays at the top of the page. This is the end of the static navigation demo.</div></div> -->
                    </div>
                    <!-- Toast message if there any error or success-->
                    <?php
                        if(isset($_SESSION['success'])){
                            echo "<script type='text/javascript'>toastr.success('" . $_SESSION['success'] . "')</script>";
                            unset($_SESSION['success']); 
                        }
                        if(isset($_SESSION['errors'])){
                            echo "<script type='text/javascript'>toastr.error('" . $_SESSION['errors'] . "')</script>";
                            unset($_SESSION['errors']);
                        }     
                    ?>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <!-- <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div> -->
                </footer>
            </div>
        </div>

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
                            $('#update_user').attr("disabled",false);
                        }
                        image_holder.show();
                        reader.readAsDataURL($(this)[0].files[0]);
                    } else {
                        alert("This browser does not support FileReader.");
                    }
                } else {
                    alert("Pls select only images");
                    $('#update_user').attr("disabled",true);
                }
                });
        </script>                

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
</html>
