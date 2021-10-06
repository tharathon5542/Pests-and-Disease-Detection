<?php

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // check if username sesstion is set
    if(!isset($_SESSION['farmerCode'])){
        header('location: login.php');
    }

    // check is user an admin
    if($_SESSION['userRole'] !== "admin"){
        header('location: index.php');
    }

    // include profile_db to manage page
    include('src/farmer_db.php');

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
                            <li class="breadcrumb-item active">ข้อมูลผู้ปลูก</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                ตารางแสดงรายการข้อมูลผู้ปลูก
                            </div>
                            <form method="get" action="farmer_detail.php">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>รหัส</th>
                                                    <th>ชื่อ - นามสกุล</th>
                                                    <th>E-mail address</th>
                                                    <th>เบอร์โทร</th>
                                                    <th>รายละเอียด</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    while($rows = mysqli_fetch_array($query_farmer_data)){
                                                        echo '<tr>';
                                                        echo '<td>' . $rows['F_CODE'] . '</td>';
                                                        echo '<td>' . $rows['F_NAME'] . '  ' . $rows['F_SURNAME'] . '</td>';
                                                        echo '<td>' . $rows['F_EMAIL'] . '</td>';
                                                        echo '<td>' . $rows['F_TEL'] . '</td>';
                                                        echo '<td><button class="btn btn-primary btn-block" name="farmer_detail" id="farmer_detail" value="' . $rows['F_CODE'] . '">รายละเอียด</button></td>';
                                                        echo '</tr>';
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </form> 
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

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
</html>
