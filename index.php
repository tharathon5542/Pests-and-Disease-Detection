<?php

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // check if username sesstion is set
    if(!isset($_SESSION['farmerCode'])){
        header('location: login.php');
    }

    // include profile_db to manage page
    include('src/index_db.php');

    // include database server for chart
    include('src/server.php'); 

    function get_notify($query_notify_data,$conn){
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
            echo '<td>' . $rows['NO_DATE'] . '</td>';
            echo '<td>' . $detect_type . '</td>';
            echo '<td>' . $detect . '</td>';
            if($detect_type == "แมลง"){
                echo '<td><a href="pests_edit.php?pcode='. $rows['NO_DETAIL'] . '"><button class="btn btn-primary " name="vetgetable_detail" id="vetgetable_detail"><img src="./src/images/detail.png" style="width: 25px; height: 25px" /></button></a></td>';
            }else{
                echo '<td><a href="disease_edit.php?dcode='. $rows['NO_DETAIL'] . '"><button class="btn btn-primary " name="vetgetable_detail" id="vetgetable_detail"><img src="./src/images/detail.png" style="width: 25px; height: 25px" /></button></a></td>';
            }
            echo '</tr>';
        }
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
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <!-- toast message -->   
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
        <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    </head>
    <body class="sb-nav-fixed">
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
                            <!-- <div class="sb-sidenav-menu-heading">Interface</div>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Layouts
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="layout-static.html">Static Navigation</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Pages
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Authentication
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="login.html">Login</a>
                                            <a class="nav-link" href="register.html">Register</a>
                                            <a class="nav-link" href="password.html">Forgot Password</a>
                                        </nav>
                                    </div>
                                </nav>
                            </div>
                            <div class="sb-sidenav-menu-heading">Addons</div>
                            <a class="nav-link" href="charts.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Charts
                            </a>
                            <a class="nav-link" href="tables.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                            </a> -->
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
                        <h1 class="mt-4">หน้าแรก</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">หน้าแรก</li>
                        </ol>
                        <div class="form-row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar mr-1"></i>
                                        จำนวนการปลูกพืชของเกษตกร (แปลง)
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart1" width="100%" height="40"></canvas></div>
                                    <div class="card-footer small text-muted"><?php date_default_timezone_set("Asia/Bangkok"); echo "อัพเดทครั้งล่าสุดวันที่ : " . date("d-m-Y") . " , " . date("H:i:sa") ?></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar mr-1"></i>
                                        จำนวนการตรวจพบ โรค และ แมลงศัตรูพืช ในแต่ละเดือน
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart2" width="100%" height="40"></canvas></div>
                                    <div class="card-footer small text-muted"><?php date_default_timezone_set("Asia/Bangkok"); echo "อัพเดทครั้งล่าสุดวันที่ : " . date("d-m-Y") . " , " . date("H:i:sa") ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">พืชผักสวนครัว</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="vetgetable.php">ดูข้อมูล</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">โรคพืชผักสวนครัว</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="disease.php">ดูข้อมูล</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">แมลงศัตรูพืชผักสวนครัว</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="pests.php">ดูข้อมูล</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                            if($_SESSION['userRole'] == "user"){
                                echo '<div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-table mr-1"></i>
                                    ตารางแสดงรายการตรวจพบ โรค และ แมลงศัตรูพืชผักสวนครัว
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>วันที่ เวลา การตรวจพบ</th>
                                                    <th>ชนิดการตรวจพบ</th>
                                                    <th>รายละเอียดการตรวจพบ</th>
                                                    <th style="width: 15%;">รายละเอียด</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                                            get_notify($query_notify_data, $conn);
                                            echo '</tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>';
                            }
                        ?>
                    </div>
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

                    <?php
                        if(isset($_SESSION['success'])){
                            echo "<script type='text/javascript'>toastr.success('" . $_SESSION['success'] . "')</script>";
                            unset($_SESSION['success']); 
                        }
                        if(isset($_SESSION['warning'])){
                            echo "<script type='text/javascript'>toastr.warning('" . $_SESSION['warning'] . "')</script>";
                            unset($_SESSION['warning']);
                        }  
                        if(isset($_SESSION['errors'])){
                            echo "<script type='text/javascript'>toastr.error('" . $_SESSION['errors'] . "')</script>";
                            unset($_SESSION['errors']);
                        }     
                    ?>

                </main>
            </div>
            
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <!-- <script src="assets/demo/chart-area-demo.js"></script> -->
        <!-- <script src="assets/demo/chart-bar-demo.js"></script> -->
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <!-- <script src="assets/demo/datatables-demo.js"></script> -->

        <script>

            // Call the dataTables jQuery plugin
            $(document).ready(function() {
                $('#dataTable').DataTable();
                $('#dataTable2').DataTable();
            });
            
            // Set new default font family and font color to mimic Bootstrap's default styling
            Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
            Chart.defaults.global.defaultFontColor = '#292b2c';

            // Bar Chart Example 1
            var ctx = document.getElementById("myBarChart2");
            var myLineChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    <?php 
                        echo "\"มกราคม\"" . "," . "\"กุมภาพันธ์\"" . "," . "\"มีนาคม\"" . "," . "\"เมษายน\"" 
                        . "," . "\"พฤษภาคม\"" . "," . "\"มิถุนายน\"" . "," . "\"กรกฎาคม\"" . "," . "\"สิงหาคม\"" 
                        . "," . "\"กันยายน\"" . "," . "\"ตุลาคม\"" . "," . "\"พฤศจิกายน\"" . "," . "\"ธันวาคม\""; 
                    ?>
                ],
                datasets: [{
                label: "จำนวนการตรวจพบ (ครั้ง)",
                backgroundColor: "rgba(2,117,216,1)",
                borderColor: "rgba(2,117,216,1)",
                data: [
                    <?php 
                        $tmpVal1 = 0;
                        $tmpVal2 = 0;
                        $tmpVal3 = 0;
                        $tmpVal4 = 0;
                        $tmpVal5 = 0;
                        $tmpVal6 = 0;
                        $tmpVal7 = 0;
                        $tmpVal8 = 0;
                        $tmpVal9 = 0;
                        $tmpVal10 = 0;
                        $tmpVal11 = 0;
                        $tmpVal12 = 0;

                        // select notify data
                        $sql_select_all_notify_data = "SELECT MONTH(NO_DATE) AS NO_MONTH,COUNT(*) AS COUNT_NO_DETAIL FROM notify GROUP BY MONTH(NO_DATE)";
                        $query_all_notify_data = mysqli_query($conn,$sql_select_all_notify_data);
                        while($rows = mysqli_fetch_array($query_all_notify_data)){
                            switch($rows['NO_MONTH']){
                                case "1" : $tmpVal1 += intval($rows['COUNT_NO_DETAIL']);
                                break;
                                case "2" : $tmpVal2 += intval($rows['COUNT_NO_DETAIL']);
                                break;
                                case "3" : $tmpVal3 += intval($rows['COUNT_NO_DETAIL']);
                                break;
                                case "4" : $tmpVal4 += intval($rows['COUNT_NO_DETAIL']);
                                break;
                                case "5" : $tmpVal5 += intval($rows['COUNT_NO_DETAIL']);
                                break;
                                case "6" : $tmpVal6 += intval($rows['COUNT_NO_DETAIL']);
                                break;
                                case "7" : $tmpVal7 += intval($rows['COUNT_NO_DETAIL']);
                                break;
                                case "8" : $tmpVal8 += intval($rows['COUNT_NO_DETAIL']);
                                break;
                                case "9" : $tmpVal9 += intval($rows['COUNT_NO_DETAIL']);
                                break;
                                case "10" : $tmpVal10 += intval($rows['COUNT_NO_DETAIL']);
                                break;
                                case "11" : $tmpVal11 += intval($rows['COUNT_NO_DETAIL']);
                                break;
                                case "12" : $tmpVal12 += intval($rows['COUNT_NO_DETAIL']);
                                break;
                            }
                        }
                        echo "$tmpVal1,$tmpVal2,$tmpVal3,$tmpVal4,$tmpVal5,$tmpVal6,$tmpVal7,$tmpVal8,$tmpVal9,$tmpVal10,$tmpVal11,$tmpVal12";
                    ?>
                ],
                }],
            },
            options: {
                scales: {
                xAxes: [{
                    time: {
                    unit: 'month'
                    },
                    gridLines: {
                    display: false
                    },
                    ticks: {
                    maxTicksLimit: 12
                    }
                }],
                yAxes: [{
                    ticks: {
                    min: 0,
                    max: <?php 
                        // select max notify detail
                        $sql_select_max_notify_data = "SELECT COUNT(*) AS COUNT_NO_DETAIL FROM notify GROUP BY MONTH(NO_DATE) ORDER BY COUNT_NO_DETAIL DESC LIMIT 1";
                        $query_max_notify_data = mysqli_query($conn,$sql_select_max_notify_data);
                        $result_max_notify_data = mysqli_fetch_assoc($query_max_notify_data);
                        $max_notify_data = intval($result_max_notify_data['COUNT_NO_DETAIL']) + intval($result_max_notify_data['COUNT_NO_DETAIL'] * 0.4);
                        echo "" . intval($max_notify_data) ;
                    ?>,
                    maxTicksLimit: 12
                    },
                    gridLines: {
                    display: true
                    }
                }],
                },
                legend: {
                display: false
                }
            }
            });

            // Bar Chart Example 2
            var ctx2 = document.getElementById("myBarChart1");
            var myLineChart2 = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ["ผักกวางตุ้ง", "คะน้า", "ต้นหอม", "ตะไคร้", "ถั่วฝักยาว", "ผักชี", "ผักบุ้งจีน", "พริกขี้หนู ", "กะหล่ำปลี", "ผักกาดขาว", "ถั่วงอก","แตงกวา"],
                datasets: [{
                label: "จำนวนการปลูก (แปลง)",
                backgroundColor: "rgba(2,117,216,1)",
                borderColor: "rgba(2,117,216,1)",
                data: [
                        <?php 
                            // select planting data
                            $sql_select_planting_data = "SELECT V_CODE,COUNT(V_CODE) AS COUNT_PLANT FROM plant GROUP BY V_CODE";
                            $query_planting_data = mysqli_query($conn,$sql_select_planting_data);
                            $out_put = "";
                            $val1 = 0;
                            $val4 = 0;
                            $val5 = 0;
                            $val6 = 0;
                            $val7 = 0;
                            $val8 = 0;
                            $val9 = 0;
                            $val10 = 0;
                            $val11 = 0;
                            $val12 = 0;
                            $val13 = 0;
                            $val14 = 0;
                            while($rows = mysqli_fetch_array($query_planting_data)){
                               switch($rows['V_CODE']){
                                   case "00001" : $val1 = $rows['COUNT_PLANT'];
                                   break;
                                   case "00004" : $val4 = $rows['COUNT_PLANT'];
                                   break;
                                   case "00005" : $val5 = $rows['COUNT_PLANT'];
                                   break;
                                   case "00006" : $val6 = $rows['COUNT_PLANT'];
                                   break;
                                   case "00007" : $val7 = $rows['COUNT_PLANT'];
                                   break;
                                   case "00008" : $val8 = $rows['COUNT_PLANT'];
                                   break;
                                   case "00009" : $val9 = $rows['COUNT_PLANT'];
                                   break;
                                   case "00010" : $val10 = $rows['COUNT_PLANT'];
                                   break;
                                   case "00011" : $val11 = $rows['COUNT_PLANT'];
                                   break;
                                   case "00012" : $val12 = $rows['COUNT_PLANT'];
                                   break;
                                   case "00013" : $val13 = $rows['COUNT_PLANT'];
                                   break;
                                   case "00014" : $val14 = $rows['COUNT_PLANT'];
                                   break;
                               }
                            }
                            echo "$val1, $val4, $val5, $val6, $val7, $val8, $val9, $val10, $val11, $val12,$val13, $val14";
                        ?>
                      ],
                }],
            },
            options: {
                scales: {
                xAxes: [{
                    time: {
                    unit: 'number'
                    },
                    gridLines: {
                    display: false
                    },
                    ticks: {
                    maxTicksLimit: 14
                    }
                }],
                yAxes: [{
                    ticks: {
                    min: 0,
                    max: <?php
                            //  get highes count to calculate
                            $sql_select_highes_count = "SELECT V_CODE,COUNT(V_CODE) AS COUNT_PLANT FROM plant GROUP BY V_CODE ORDER BY COUNT(V_CODE) DESC LIMIT 1";
                            $query_highes_count = mysqli_query($conn,$sql_select_highes_count);
                            $result_highes_count = mysqli_fetch_assoc($query_highes_count);
                            $highes_count = intval($result_highes_count['COUNT_PLANT']) + (intval($result_highes_count['COUNT_PLANT']) * 0.5);
                            echo "" . intval($highes_count); 
                        ?>,
                    maxTicksLimit: 14
                    },
                    gridLines: {
                    display: true
                    }
                }],
                },
                legend: {
                display: false
                }
            }
            });


        </script>
    </body>
</html>
