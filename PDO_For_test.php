<?php


    include "PDO_Controller.php";
	
	
	$DB_CONFIG = array(
						'dbms'     => 'mysql',
						'host'     => 'localhost',  # 127.0.0.1
						'dbname'   => 'vk_monitor',
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
	
	//$PDO->Select_db("vk_monitor");
	
	
 
	
	$sql = "SELECT * FROM mon_results WHERE id <= :id"; // AND post_url = ?";
	//$sql = "UPDATE mon_results SET post_url='123' WHERE id >= 377 "; // AND post_url = ?";
	
	
	
    echo "<pre>";
	#print_r( $PDO->getRow($sql , [':id'=>90] ) );
	print_r( $PDO->Query($sql , [':id'=>88] ) );
	#print_r( $PDO->Execute( $sql ) );
	#print_r( $PDO->fetcher( "c" ) );
	#print_r( $PDO->fetcher( "row" ) );
	print_r( $PDO->fetcher( "all" ) );
	
	
    echo "</pre>";
	
    

    
    
    
	exit("<hr>Exit main.");
	


    /*

     */

?>