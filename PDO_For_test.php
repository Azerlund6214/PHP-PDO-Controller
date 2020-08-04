<?php


    include "PDO_Controller.php";
	
	$DB_SERVER = "localhost"; # 127.0.0.1
	$DB_USER   = "root";
	$DB_PASS   = "root";
	$DB_NAME   = "vk_monitor";
	#$DB_PORT   = "000";
	
	
	$DB_CONFIG = array(
						'dbms'     => 'mysql',
						'host'     => 'localhost',  # 127.0.0.1
						#'dbname'   => 'database',
						#'port'     =>  3306,
						'charset'  => 'utf8',
						'username' => 'root',
						'password' => 'root',
					  );
	
	
	//var_dump( PDO_C::Build_Conn_String($DB_CONFIG) );
	
	// pgsql:host=192.168.137.1;port=5432;dbname=anydb
	// mysql:host=localhost;dbname=test;charset=utf8
	$PDO = new PDO_C(  );
	
	$PDO->Connect( PDO_C::Build_Conn_String($DB_CONFIG), $DB_CONFIG['username'], $DB_CONFIG['password'] );
	
	
	//echo ( $PDO->Check_connection() ) ? "Yes" :  "No";
	
	$PDO->Select_db("vk_monitor");
	
	
 
	
	$sql = "SELECT * FROM mon_results WHERE id = :id"; // AND post_url = ?";
	
    echo "<pre>";
	print_r( $PDO->getRow($sql , [':id'=>90] ) );
	print_r( $PDO->execute($sql , [':id'=>90] ) );
    echo "</pre>";
	
    
    
    
	exit("<hr>Exit main.");
	
	
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