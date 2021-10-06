<?php 
    // include database server
    include('src/server.php');

    if(isset($_GET['requested'])){
        // get last notify code
        $sql_select_last_notify_code = "SELECT MAX(NO_CODE) AS NO_CODE FROM notify";
        $query_select_last_notify_code = mysqli_query($conn,$sql_select_last_notify_code);
        $result_select_last_notify_code = mysqli_fetch_assoc($query_select_last_notify_code);
        $new_notify_code = substr( "0000" . (intval($result_select_last_notify_code['NO_CODE']) + 1), -5);
        
        $F_CODE = $_GET['fcode'];
        $NO_DETAIL = $_GET['nodetail'];
        $NO_DETAIL_TYPE = $_GET['nodetailtype'];
        $NO_SCORE = $_GET['noscore'];

        $sql_insert_notify = "INSERT INTO notify (NO_CODE, F_CODE, NO_DETAIL, NO_DETAIL_TYPE, NO_SCORE) VALUES('$new_notify_code',$F_CODE,$NO_DETAIL,$NO_DETAIL_TYPE,$NO_SCORE)";
        mysqli_query($conn,$sql_insert_notify);
        
    }
?>