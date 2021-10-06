<?php

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // check if username session is set
    if(!isset($_SESSION['farmerCode'])){
        header('location: login.php');
    }

    // include profile_db to manage page
    include('src/disease_edit_db.php');

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
        <!-- image modal css -->
        <link href="css/imageModal.css" rel="stylesheet" />
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
                                // check if user is farmer user
                                if($_SESSION['userRole'] === "user"){
                                    echo '<a class="nav-link" href="planting.php">
                                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                            การปลูกพืช
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
                        <h1 class="mt-4">ข้อมูลโรคพืชผัก</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                            <li class="breadcrumb-item"><a href="disease.php">ข้อมูลโรคพืชผัก</a></li>
                            <?php 
                                // if come from vetgetable link 
                                if(isset($_GET['link'])){
                                    // get vetgetable name
                                    $sql_get_vetgetable_name = "SELECT V_THAINAME FROM vetgetable WHERE V_CODE = " . $_GET['link'];
                                    $query_vetgetable_name = mysqli_query($conn,$sql_get_vetgetable_name);
                                    $result_vetgetable_name = mysqli_fetch_assoc($query_vetgetable_name);
                                    echo '<li class="breadcrumb-item"><a href="vetgetable_edit.php?vcode=' . $_GET['link'] . '">'. $result_vetgetable_name['V_THAINAME'] .'</a></li>';
                                }
                            ?>
                            <li class="breadcrumb-item active"><?php echo $_SESSION['dcode'];?></li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header"><h4 class="font-weight-light">รายละเอียด</h4></div>
                            <div class="card-body">
                            <form method="post" action="src/disease_edit_db.php" enctype="multipart/form-data">
                                <div class="form-row">
                                    <?php 
                                        if($_SESSION['userRole'] == "admin"){
                                            echo '<div class="col-md-2">';
                                            echo    '<div class="form-group">';
                                            echo        '<label class="small mb-1" for="inputDiseasecode">รหัสโรคพืชผัก</label>';
                                            echo        '<input class="form-control py-3" id="inputDiseasecode" name="inputDiseasecode" type="text" value="' . $result_disease_data['D_CODE']. '" readonly />';          
                                            echo    '</div>';
                                            echo '</div>';
                                        }
                                    ?>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputDiseasename">ชื่อโรคพืชผัก</label>
                                            <?php 
                                                // only admin can edit
                                                if($_SESSION['userRole'] == "admin"){
                                                    echo '<input class="form-control py-3" id="inputDiseasename" name="inputDiseasename" type="text" value="'. $result_disease_data['D_NAME'] .'" required />';
                                                }else{
                                                    echo '<input class="form-control py-3" id="inputDiseasename" name="inputDiseasename" type="text" value="'. $result_disease_data['D_NAME'] .'" required readonly />';
                                                }
                                            ?>
                                            
                                        </div>
                                    </div> 
                                </div>
                                <div class="form-row"> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputDiseasesymptom">อาการของโรค</label>
                                            <?php 
                                                // only admin can edit
                                                if($_SESSION['userRole'] == "admin"){
                                                    echo '<textarea class="form-control py-1" id="inputDiseasesymptom" name="inputDiseasesymptom" style="min-height: 150px;">'. $result_disease_data['D_SYMPTOM'] .'</textarea>';
                                                }else{
                                                    echo '<textarea class="form-control py-1" id="inputDiseasesymptom" name="inputDiseasesymptom" style="min-height: 150px;" readonly >'. $result_disease_data['D_SYMPTOM'] .'</textarea>';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputDiseaseremedy">การรักษาโรค</label>
                                            <?php 
                                                // only admin can edit
                                                if($_SESSION['userRole'] == "admin"){
                                                    echo '<textarea class="form-control py-1" id="inputDiseaseremedy" name="inputDiseaseremedy" style="min-height: 150px;">'. $result_disease_data['D_REMEDY'] .'</textarea>';
                                                }else{
                                                    echo '<textarea class="form-control py-1" id="inputDiseaseremedy" name="inputDiseaseremedy" style="min-height: 150px;" readonly >'. $result_disease_data['D_REMEDY'] .'</textarea>';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputDiseaseward">การป้องกันโรค</label>
                                            <?php 
                                                // only admin can edit
                                                if($_SESSION['userRole'] == "admin"){
                                                    echo '<textarea class="form-control py-1" id="inputDiseaseward" name="inputDiseaseward" style="min-height: 150px;">'. $result_disease_data['D_WARD'] .'</textarea>';
                                                }else{
                                                    echo '<textarea class="form-control py-1" id="inputDiseaseward" name="inputDiseaseward" style="min-height: 150px;" readonly >'. $result_disease_data['D_WARD'] .'</textarea>';
                                                }
                                            ?>
                                            
                                        </div>
                                    </div> 
                                </div>
                                <?php 
                                    // only admin can edit disease link vetgetable 
                                    if($_SESSION['userRole'] == "admin"){
                                        echo '<div class="form-row">';
                                        echo    '<div class="col-md-4">';
                                        echo        '<div class="form-group">';
                                        echo            '<label class="small mb-1" for="selectDiseaselinkvetgetable">ชนิดพืชที่เกิดโรคนี้</label>';
                                        echo                '<select class="custom-select" id="selectDiseaselinkvetgetable" name="selectDiseaselinkvetgetable">';
                                                                while($rows = mysqli_fetch_array($query_vetgetable_data)){
                                                                    echo '<option value = "'. $rows['V_CODE'] .'">'. $rows['V_CODE'] . ' : ' . $rows['V_THAINAME'] .'</option>';
                                                                }
                                        echo                '</select>';
                                        echo        '</div>';
                                        echo    '</div>';
                                        echo    '<div class="col-md-2">';
                                        echo        '<div class="form-group">';
                                        echo            '<button type="button" class="btn btn-success btn-block" name="add_disease_link_vetgetable" id="add_disease_link_vetgetable" style="margin-top: 27px;">เพิ่มรายการ</button>';
                                        echo        '</div>';
                                        echo    '</div>';
                                        echo    '<div class="col-md-2">';
                                        echo        '<div class="form-group">';
                                        // echo            '<button type="button" class="btn btn-danger btn-block" name="del_disease_link_vetgetable" id="del_disease_link_vetgetable" style="margin-top: 27px;">ลบรายการ</button>';
                                        echo        '</div>';
                                        echo    '</div>';
                                        echo '</div>';
                                    }  
                                ?>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <i class="fas fa-table mr-1"></i>
                                                ตารางแสดงรายการข้อมูลชนิดพืชที่เกิดโรคนี้
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered"  width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <?php if($_SESSION['userRole'] == "admin") echo '<th>รหัสพืชผักสวนครัว</th>'; ?>
                                                                <th>ชื่อพืชผักสวนครัว (ไทย)</th>
                                                                <th>ชื่อพืชผักสวนครัว (อังกฤษ)</th>
                                                                <th>ชื่อพืชผักสวนครัว (วิทยาศาสตร์)</th>
                                                                <?php 
                                                                    if($_SESSION['userRole'] == "admin"){
                                                                        echo '<th style="width: 15%;">ลบรายการ</th>';
                                                                    }
                                                                ?>      
                                                            </tr>
                                                        </thead>
                                                        <tbody id="add_disease_link_vetgetable_table">
                                                            <?php 
                                                                // loop to get data from tmp disease link vetgetable
                                                                while($rows = mysqli_fetch_array($query_tmp_disease_link_vetgetable)){
                                                                    // select vetgetable data
                                                                    $sql_select_vetgetable_data = "SELECT * FROM vetgetable WHERE V_CODE =" . $rows['V_CODE'];
                                                                    $query_vetgetable_data = mysqli_query($conn,$sql_select_vetgetable_data);
                                                                    $result_vetgetable_data = mysqli_fetch_assoc($query_vetgetable_data); 
                                                                    echo '<tr>';
                                                                    if($_SESSION['userRole'] == "admin") echo '<td>'. $result_vetgetable_data['V_CODE'] .'</td>';
                                                                    echo '<td>'. $result_vetgetable_data['V_THAINAME'] .'</td>';
                                                                    echo '<td>'. $result_vetgetable_data['V_ENGNAME'] .'</td>';
                                                                    echo '<td>'. $result_vetgetable_data['V_SCINAME'] .'</td>';
                                                                    if($_SESSION['userRole'] == "admin"){
                                                                        echo '<td><button type="button" class="btn btn-danger btn-block" name="del_disease_link_vetgetable" id="del_disease_link_vetgetable" onClick="testDelLink('. $rows['TMP_DLV_CODE'] .')" >ลบรายการ</button></td>';
                                                                    }
                                                                    echo '</tr>';
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    // only admin can edit images 
                                    if($_SESSION['userRole'] == "admin"){
                                        echo '<div class="form-row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="gallery-photo-add">รูปโรค</label>
                                                        <input class="custom-file py-1" id="gallery-photo-add" name="files[]" type="file" multiple/>
                                                    </div>
                                                </div>
                                            </div>';
                                    }   
                                ?>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="small mb-1" for="gallery">คลังรูปภาพ</label>
                                            <div id="gallery" name="gallery" class="gallery">
                                            <?php 
                                                // get images
                                                // scan dir for vetgetable image and set to img tag
                                                $disease_img_dir = "src/images/diseases" . "/" . $_SESSION['dcode'];
                                                $disease_images = scandir($disease_img_dir);
                                                // loop get images
                                                foreach($disease_images as $images){
                                                    if( $images !== ".." and $images !== "."){
                                                        echo '<img id="myImg" name="myImg" src="'. $disease_img_dir . "/" . $images .'" alt="' . $result_disease_data['D_CODE'] . '" style="width:150px;height:150px;padding:5px;">';
                                                    }
                                                }
                                            ?>
                                            <!-- The Modal -->
                                            <div id="myModal" class="modal">
                                                <img class="modal-content" id="img01">
                                                <div id="caption"></div>
                                            </div> 
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                <div style="height: 5vh"></div>
                                <?php 
                                    // only admin can submit edit
                                    if($_SESSION['userRole'] == "admin"){
                                        echo '<div class="form-row">
                                                <div class="col-md-2">
                                                    <button class="btn btn-primary btn-block" name="edit_disease" id="edit_disease">แก้ไขข้อมูล</button>
                                                </div>
                                            </div>';
                                    }
                                ?>            
                            </form> 
                            </div>               
                        </div>
                    </div>        
                        <!-- 
                        <div class="card mb-4"><div class="card-body">When scrolling, the navigation stays at the top of the page. This is the end of the static navigation demo.</div></div> -->
            
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

            // Multiple images preview in browser
            $(function() { 
            var imagesPreview = function(input, placeToInsertImagePreview) {
                $("div.gallery").html("");
                if (input.files) {
                    var filesAmount = input.files.length;

                    for (i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();
                        reader.onload = function(event) {
                            $($.parseHTML('<img class="gallery" style="width:150px;height:150px;padding: 5px;">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            };
                $('#gallery-photo-add').on('change', function() {
                    imagesPreview(this, 'div.gallery');
                });
            });

            // on disease link vetgetable add to table
            $(document).ready(function(){
            $('#add_disease_link_vetgetable').click(function(){
                var vetgetableCode = document.getElementById("selectDiseaselinkvetgetable").value;
                $.ajax({
                    url: "./src/ajax/ajax_add_disease_link_vetgetable.php",
                    method: "POST",
                    data:{vetgetable_code: vetgetableCode},
                    dataType:"text",
                    success:function(data){
                        $('#add_disease_link_vetgetable_table').html(data);
                    }
                })
            });
            });

            // on disease link vetgetable delete from table
            function testDelLink(tmp_dlv_code){
                $.ajax({
                    url: "./src/ajax/ajax_add_disease_link_vetgetable.php",
                    method: "POST",
                    data:{delDisease_link_vetgetable: tmp_dlv_code},
                    dataType:"text",
                    success:function(data){
                        $('#add_disease_link_vetgetable_table').html(data);
                    }
                })  
            }
             

            // Get the modal
            var modal = document.getElementById("myModal");
            // Get the image and insert it inside the modal - use its "alt" text as a caption
            var img = document.getElementsByName("myImg");
            var modalImg = document.getElementById("img01");
            var captionText = document.getElementById("caption");
            for(i = 0; i < img.length; i++){
                img[i].onclick = function(){
                    modal.style.display = "block";
                    modalImg.src = this.src;
                    captionText.innerHTML = this.alt;
                } 
            }
            
            // Get the <span> element that closes the modal
            var span = document.getElementById("myModal");

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() { 
                modal.style.display = "none";
            }

        </script>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
</html>
