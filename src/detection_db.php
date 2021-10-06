<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('server.php');

    $farmerCode = $_SESSION['farmerCode'];

    // if disease code is set
    if(isset($_GET['NoCode'])){

        $notifyCode = $_GET['NoCode'];

        $explodedNotifyCode = explode(",", $notifyCode);
        
    }

 
?>