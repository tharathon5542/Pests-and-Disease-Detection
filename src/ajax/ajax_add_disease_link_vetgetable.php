<?php 

    // start session if there no session currently start now
    if(session_id() == '') {
        session_start();
    }

    // include database server
    include('../server.php');

    // on add disease link vetgetable
    if(isset($_POST['vetgetable_code'])){

        // set variable vetgetable code
        $v_code = $_POST['vetgetable_code'];

        // insert data to temporary database disease link vetgetable
        $sql_insert_tmp_disease_link_vetgetable = "INSERT INTO tmp_disease_link_vetgetable (V_CODE) VALUES('$v_code')";
        mysqli_query($conn,$sql_insert_tmp_disease_link_vetgetable);

        // select temporary database disease link vetgetable
        $sql_select_tmp_disease_link_vetgetable = "SELECT * FROM tmp_disease_link_vetgetable";
        $query_tmp_disease_link_vetgetable = mysqli_query($conn,$sql_select_tmp_disease_link_vetgetable);

        $out_put = "";
        while($rows = mysqli_fetch_array($query_tmp_disease_link_vetgetable)){

            // select vetgetable data from database
            $sql_select_vetgetable_data = "SELECT * FROM vetgetable WHERE V_CODE = " . $rows['V_CODE'];
            $query_vetgetable_data = mysqli_query($conn,$sql_select_vetgetable_data);
            $result_vetgetable_data = mysqli_fetch_assoc($query_vetgetable_data);

            $out_put .= "<tr>".
                            "<td>" . $result_vetgetable_data['V_CODE'] . "</td>" .
                            "<td>" . $result_vetgetable_data['V_THAINAME'] . "</td>" .
                            "<td>" . $result_vetgetable_data['V_ENGNAME'] . "</td>" .
                            "<td>" . $result_vetgetable_data['V_SCINAME'] . "</td>" .
                            '<td><button type="button" class="btn btn-danger btn-block" name="del_disease_link_vetgetable" onClick="testDelLink('. $rows['TMP_DLV_CODE'] .')" >ลบรายการ</button></td>' .
                        "</tr>";
        }
        echo $out_put;
    }

    // on del disease link vetgetable from table
    if(isset($_POST['delDisease_link_vetgetable'])){

        $sql_del_tmp_disease_link_vetgetable = "DELETE FROM tmp_disease_link_vetgetable WHERE TMP_DLV_CODE = " . $_POST['delDisease_link_vetgetable'];
        mysqli_query($conn,$sql_del_tmp_disease_link_vetgetable);
        
        // select temporary database disease link vetgetable
        $sql_select_tmp_disease_link_vetgetable = "SELECT * FROM tmp_disease_link_vetgetable";
        $query_tmp_disease_link_vetgetable = mysqli_query($conn,$sql_select_tmp_disease_link_vetgetable);

        $out_put = "";
        while($rows = mysqli_fetch_array($query_tmp_disease_link_vetgetable)){

            // select vetgetable data from database
            $sql_select_vetgetable_data = "SELECT * FROM vetgetable WHERE V_CODE = " . $rows['V_CODE'];
            $query_vetgetable_data = mysqli_query($conn,$sql_select_vetgetable_data);
            $result_vetgetable_data = mysqli_fetch_assoc($query_vetgetable_data);

            if($result_vetgetable_data){
                $out_put .= "<tr>".
                            "<td>" . $result_vetgetable_data['V_CODE'] . "</td>" .
                            "<td>" . $result_vetgetable_data['V_THAINAME'] . "</td>" .
                            "<td>" . $result_vetgetable_data['V_ENGNAME'] . "</td>" .
                            "<td>" . $result_vetgetable_data['V_SCINAME'] . "</td>" .
                            '<td><button type="button" class="btn btn-danger btn-block" name="del_disease_link_vetgetable" onClick="testDelLink('. $rows['TMP_DLV_CODE'] .')" >ลบรายการ</button></td>' .
                        "</tr>";
            }    
        }
        echo $out_put;
    }

    
 
?>