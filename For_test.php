<?php


    include "DB_Controller_mysqli.php";



    $db_host = '127.0.0.1';
    $db_user = 'root';
    $db_pass = 'root';
    $db_name = "test_db";

    $DBC = new DB_Controller( $db_host , $db_user , $db_pass );

    $DBC->Disconnect();
    $DBC->Check_connection();


    echo "123";


	
?>