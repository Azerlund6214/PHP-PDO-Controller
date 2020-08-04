<?php


    include "PDO_Controller.php";
	
	$DB_SERVER = "localhost"; # 127.0.0.1
	$DB_USER   = "root";
	$DB_PASS   = "root";
	$DB_NAME   = "vk_monitor";
	#$DB_PORT   = "000";
	
	// pgsql:host=192.168.137.1;port=5432;dbname=anydb
	$PDO = new PDO('mysql:host=localhost;dbname=test', $user, $pass);
	
	exit("<hr>Exit main.");
    
    $DBC = new DB_Controller( $db_host , $db_user , $db_pass );
	
	
	$DBC->Select_db($db_name);

    //echo ( $DBC->Check_connection() ) ? "yes" :  "no";
	
	$sql = "SELECT * FROM mon_results WHERE id = ? AND post_url = ?";
	
	
	if( ! $stmt )
	{ //если ошибка - убиваем процесс и выводим сообщение об ошибке.
	
	}
	
	echo "<hr>";
	
	echo "<hr>";
	
	
	


    //$DBC->Query_prep("SELECT count(*) FROM '?'" , "s" , "mon_results"  );


    //mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


    echo "<hr>End";


    /*


    $con = mysqli_connect($DB_SERVER, $DB_USER_READER, $DB_PASS_READER, $DB_NAME, $DB_PORT);
     */

?>