<?php


    include "DB_Controller_mysqli.php";



    $db_host = '127.0.0.1';
    $db_user = 'root';
    $db_pass = 'root';
    $db_name = "vk_monitor";

    $DBC = new DB_Controller( $db_host , $db_user , $db_pass );


    $DBC->Select_db($db_name);

    //echo ( $DBC->Check_connection() ) ? "yes" :  "no";
	
	$sql = "SELECT * FROM mon_results WHERE id = ? AND post_url = ?";
	$DBC->Prepared_stmt = $DBC->db->prepare( $sql );
	$DBC->Prepared_stmt->bind_param("is" , 16 , "str");
	$DBC->Prepared_stmt->execute();
	
	$DBC->Prepared_stmt->close();
	
	
	
	
	exit;
	
	$DBC->Query_prep(  , "is" , 44 , "str");
	
	//exit;
	
	
	$my = $DBC->db;
	
	
	$stmt = $my->prepare( "SELECT * FROM ?" ) ;
	
	if( ! $stmt )
	{ //если ошибка - убиваем процесс и выводим сообщение об ошибке.
	    die( "SQL Error: {$my->errno} - {$my->error}" );
	}
	
	echo "<hr>";
	$stmt->bind_param("s" , "mon_results");
	echo "<hr>";
	$stmt->execute();
	$stmt->close();
	echo $DBC->Get_connection()->info;
	
	
	


    //$DBC->Query_prep("SELECT count(*) FROM '?'" , "s" , "mon_results"  );


    //mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


    echo "<hr>End";


    /*
    $DB_SERVER="db_server_name";
    $DB_USER_READER="root";
    $DB_PASS_READER="passw*rd";
    $DB_NAME="db_name";
    $DB_PORT="port number";

    $con = mysqli_connect($DB_SERVER, $DB_USER_READER, $DB_PASS_READER, $DB_NAME, $DB_PORT);
     */

?>