<?php

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // check if username session is set
    if(!isset($_SESSION['farmerCode'])){
        header('location: login.php');
    }

    // check is admin
    if($_SESSION['userRole'] !== "admin"){
        header('location: disease.php');
    }

    // include profile_db to manage page
    include('src/disease_add_db.php');

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
                        <a class="dropdown-item" href="profile.php">?????????????????????????????????</a>
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
                                ?????????????????????
                            </a>
                            <?php 
                                // check if user is admin
                                if($_SESSION['userRole'] === "admin"){
                                    echo '<a class="nav-link" href="farmer.php">
                                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                            ???????????????????????????????????????
                                         </a>';
                                }
                                // check if user is farmer user
                                if($_SESSION['userRole'] === "user"){
                                    echo '<a class="nav-link" href="planting.php">
                                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                            ??????????????????????????????
                                         </a>';
                                }
                            ?>
                            <a class="nav-link" href="vetgetable_family.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                ???????????????????????????????????????
                            </a>
                            <a class="nav-link" href="vetgetable.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                ?????????????????????????????????????????????????????????
                            </a>
                            <a class="nav-link" href="disease.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                ?????????????????????????????????????????????
                            </a>
                            <a class="nav-link" href="pests.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                ??????????????????????????????????????????????????????
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
                        <h1 class="mt-4">????????????????????????????????????????????????????????????</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">?????????????????????</a></li>
                            <li class="breadcrumb-item"><a href="disease.php">?????????????????????????????????????????????</a></li>
                            <li class="breadcrumb-item active">????????????????????????????????????????????????????????????</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header"><h4 class="font-weight-light">?????????????????????????????????</h4></div>
                            <div class="card-body">
                            <form method="post" action="src/disease_add_db.php" enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputDiseasecode">???????????????????????????????????????</label>
                                            <input class="form-control py-3" id="inputDiseasecode" name="inputDiseasecode" type="text" value="<?php echo $last_d_code; ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputDiseasename">???????????????????????????????????????</label>
                                            <input class="form-control py-3" id="inputDiseasename" name="inputDiseasename" type="text" required />
                                        </div>
                                    </div> 
                                </div>
                                <div class="form-row"> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputDiseasesymptom">?????????????????????????????????</label>
                                            <textarea class="form-control py-1" id="inputDiseasesymptom" name="inputDiseasesymptom" style="min-height: 150px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputDiseaseremedy">?????????????????????????????????</label>
                                            <textarea class="form-control py-1" id="inputDiseaseremedy" name="inputDiseaseremedy" style="min-height: 150px;"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputDiseaseward">????????????????????????????????????</label>
                                            <textarea class="form-control py-1" id="inputDiseaseward" name="inputDiseaseward" style="min-height: 150px;"></textarea>
                                        </div>
                                    </div> 
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="small mb-1" for="selectDiseaselinkvetgetable">????????????????????????????????????????????????????????????</label>
                                            <select class="custom-select" id="selectDiseaselinkvetgetable" name="selectDiseaselinkvetgetable">
                                                <?php 
                                                    while($rows = mysqli_fetch_array($query_vetgetable_data)){
                                                        echo '<option value = "'. $rows['V_CODE'] .'">'. $rows['V_CODE'] . ' : ' . $rows['V_THAINAME'] .'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-success btn-block" name="add_disease_link_vetgetable" id="add_disease_link_vetgetable" style="margin-top: 27px;">?????????????????????????????????</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <i class="fas fa-table mr-1"></i>
                                                ???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered"  width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <?php if($_SESSION['userRole'] == "admin") echo '<th>???????????????????????????????????????????????????</th>'; ?>
                                                                <th>??????????????????????????????????????????????????? (?????????)</th>
                                                                <th>??????????????????????????????????????????????????? (??????????????????)</th>
                                                                <th>??????????????????????????????????????????????????? (?????????????????????????????????)</th>
                                                                <?php 
                                                                    if($_SESSION['userRole'] == "admin"){
                                                                        echo '<th style="width: 15%;">????????????????????????</th>';
                                                                    }
                                                                ?>  
                                                            </tr>
                                                        </thead>
                                                        <tbody id="add_disease_link_vetgetable_table">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div id="test">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="small mb-1" for="gallery-photo-add">??????????????????</label>
                                            <input class="custom-file py-1" id="gallery-photo-add" name="files[]" type="file" multiple/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="small mb-1" for="gallery">??????????????????????????????</label>
                                            <div id="gallery" name="gallery" class="gallery"></div>
                                        </div>
                                    </div>
                                </div>
                                <div style="height: 5vh"></div>
                                <div class="form-row">
                                    <div class="col-md-2">
                                        <button class="btn btn-success btn-block" name="add_disease" id="add_disease">?????????????????????????????????</button>
                                    </div>
                                </div>
                            </form>                
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

        </script>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
</html>
