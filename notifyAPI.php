<?php 
    // include database server
    include('src/server.php');

    if(isset($_GET['requested'])){
        // get last notify code
        $sql_select_last_notify_code = "SELECT MAX(NO_CODE) AS NO_CODE FROM notify";
        $query_select_last_notify_code = mysqli_query($conn,$sql_select_last_notify_code);
        $result_select_last_notify_code = mysqli_fetch_assoc($query_select_last_notify_code);
        $notify_code = $result_select_last_notify_code['NO_CODE'];
        
        $jsonObject = array('notifyCode' => $notify_code);

        $jsonEncode = json_encode($jsonObject);

        echo $jsonEncode;
        
    }
?>