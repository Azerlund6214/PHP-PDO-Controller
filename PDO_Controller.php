<?php

	class PDO_C
	{

		public $connection; # Главное подключение к бд. Получать через getConnection()
		
		public $connectionString = null; # Записывается только в случае успеха
		public $username = ''; #
		public $password = ''; #
		
		
		
		
		####################################
		
		public function __construct( $con_str = null , $user = null , $pass = null )
		{
			
			if( $con_str && $user && $pass )
				$this->Connect($con_str , $user , $pass);
			
		}
		
		####################################
		### Все про подключение
		
		/**
		 * Собирает строку подключения из данных массива.
		 * @param $Arr_config - массив с данными для подключения
		 * @return string
		 */
		public static function Build_Conn_String( $Arr_config )
		{
			$string  = $Arr_config['dbms']. ":" ;
			$string .= "host=" . $Arr_config['host'] . ";" ;
			
			if( isset( $Arr_config['dbname']  ) ) $string .= "dbname="  . $Arr_config['dbname']  . ";" ;
			if( isset( $Arr_config['port']    ) ) $string .= "port="    . $Arr_config['port']    . ";" ;
			if( isset( $Arr_config['charset'] ) ) $string .= "charset=" . $Arr_config['charset'] . ";" ;
			
			# Обрезаем лишний ; в конце
			$string = substr($string, 0, -1);
			
			return $string;
			
			
			# pgsql:host=192.168.137.1;port=5432;dbname=anydb
			# mysql:host=localhost;dbname=test;charset=utf8
			
			/*
				'dbms'     => 'mysql',
				'host'     => 'localhost',  # 127.0.0.1
				'dbname'   => 'database',
				'port'     =>  3306,
				'charset'  => 'utf8',
				'username' => 'user',
				'password' => 'password',
			*/
		
		}
		
		
		/**
		 * Подключиться к серверу СУБД. Успех либо выход.
		 * @param $Conn_str - Уже готовая строка подключения
		 * @param $User
		 * @param $Pass
		 */
		public function Connect( $Conn_str , $User , $Pass  )
		{
			
			try
			{
				
				$this -> connection = new PDO( $Conn_str, $User, $Pass );
				
			} catch (PDOException $e) {
				echo "<hr>Ошибка подключения через PDO.";
				echo "<br>Строка подключения: "; var_dump($Conn_str);
				echo "<br>Логин: "; var_dump($User);
				echo "<br>Пароль: "; var_dump($Pass);
				echo "<br>Текст ошибки: " . $e->getMessage();
				
				echo "<hr>";
				
				echo "<pre>"; print_r ($e); echo "</pre>";
				
				echo "<hr>";
				die("PDO->Connect = Выход");
			}
			
			$this -> connectionString = $Conn_str;
			$this -> username = $User;
			$this -> password = $Pass;
			
		}
		
		
		/**
		 * Возвращает подключение либо завершает скрипт если его нет.
		 * @return PDO Connection / Exit
		 */
		public function getConnection()
		{
			
			if ( is_null( $this -> connectionString ) )
				exit ("<hr>PDO->getConnection = Строка подключения пуста, еще ни разу не подключались.<br>Exit");
			
			if ( is_null( $this -> connection ) )
				exit ("<hr>PDO->getConnection = this->connection = NULL.<br>Exit");
			
			
			return $this -> connection;
		}
		
		
		/**
		 * Проверка работоспособности соединения с СУБД
		 * Полезно для быстрого теста при первом подключении БД
		 * @return bool = true / false
		 * echo ( $PDO->Check_connection() ) ? "Yes" :  "No";
		 */
		function Check_connection(  )
		{
			
			$con = $this->getConnection();
			
			
			# Если еще ни разу не подключались
			if( ! $con )
				return false;
			
			
			# Если уже подключались, пробуем выполнить запрос.
			$statement = $con->prepare("SELECT VERSION()");
			$statement->execute( );
			$result = $statement->fetch(  )[0];
			
			#print_r($result);
			
			if( ! $result )
				return false;
			
			
			return true;
			
		}
		
		
		/**
		 * Отключиться от сервера СУБД
		 */
		public function Disconnect___PUSTO( ){		}
		
		
		####################################
		### Мелкие запросы-обертки
		
		/**
		 * Выбрать рабочую БД
		 * @param string $target_db
		 */
		public function Select_db( $target_db )
		{
			$con = $this->getConnection();
			
			$con->exec("USE $target_db");
			
			if( $this->Has_error() )
				$this->Echo_error( true ); # Вывести инфу и ВЫЙТИ
			
		}
		
		
		####################################
		### Исключения и отладка.
		
		/**
		 * Вывести текстом последнюю ошибку mysqli
		 * @param bool $Exit_after_echo - Завершить скрипт после вывода
		 */
		public function Echo_error( $Exit_after_echo = false )
		{
			# https://www.php.net/manual/ru/pdo.errorinfo.php
			# https://www.php.net/manual/ru/pdo.errorcode.php
			
			$con = $this->getConnection();
			
			if ( $con->errorCode() != "00000" )
			{
				echo "<hr>Echo_error => Есть ошибки." ;
				echo "<br><br>PDO::errorCode() => " . $con->errorCode() ;
				echo "<br>PDO::errorInfo() [2] => " . $con->errorInfo()[2] ;
				
				echo "<br><br>PDO::errorInfo() => " ;
				echo "<pre>"; print_r ( $con->errorInfo() ); echo "</pre>";
				
			}
			else
				echo "<br>Echo_error => Ошибок нет";
			
			
			if ( $Exit_after_echo )
				exit("<hr>PDO->Echo_error - Exit_after_echo=true");
		}
		
		/**
		 * Произошла ли ошибка?
		 * @return bool = true / false
		 */
		public function Has_error(  )
		{
			if ( $this->connection->errorCode() != "00000" )
				return true;
			
			return false;
		}
		
		
		####################################
		###
		
		
		
		
		####################################
		###
		
		
		
		
		####################################
		###
		
		
		
		/*
	$pdo = new PDO (whatever);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$pdo->exec ("QUERY WITH SYNTAX ERROR");
	} catch (PDOException $e) {
		if ($e->getCode() == '2A000')
			echo "Syntax Error: ".$e->getMessage();
	}
*/
		
		


		
		
		
		
		
		
		
		
		
		
		
		



		
		
        /**
         * Выполнить запрос БЕЗ возвращаемого результата
         * @param $sql
         * TODO: Добавить реакцию на ошибку в запросе(неудачный запрос при кривом sql)
         */
		public function Exec( $sql )
		{
			$this->db -> query( $sql );
		
		}





        /* Не работает, в процессе */
        public function Query_prep_sql( $sql )
        {
			
            $stmt = $this->db->prepare( $sql ) ;
			
            if( ! $stmt )
                $this->Echo_error();
			else
	            $this->Prepared_stmt;
			
			
			
            print_r($args);
            //$stmt->bind_param($a , $args);
            

            $stmt->execute();

            $stmt->close();
    

            echo "<hr>";
            //echo $this->db->info;

        }


        /* Сделать метод фетча статичным */

        /**
         * Выполнить запрос и вернуть результат
         * @param string $sql
         * @param string $fetch_type = all / assoc
         * @return mixed
         * TODO: Добавить другие виды фетчей
         * TODO: Добавить реакцию на ошибку в запросе(неудачный запрос при кривом sql)
         */
		public function Query( $sql , $fetch_type = "all" )
		{
			
			$result = $this->db -> query( $sql );
			#print_r("Select вернул ". $result->num_rows ." строк.");

            # В этом месте будет отлов ошибки запроса

            //$this-> Get_error();

			switch( $fetch_type )
			{
				case "all":    return $result -> fetch_all();  break;
				case "assoc": return  $result-> fetch_assoc(); break;
				
				default: exit("Невалидный fetch_type");
			}


		}


















		
		/*
			$query = $mysqli->prepare('
			SELECT * FROM users
			WHERE username = ?
			AND email = ?
			AND last_login > ?');
			  
			$query->bind_param('sss', 'test', $mail, time() - 3600);
			$query->execute();
			
			
			
            // MySQLi, ООП
            if ($result = $mysqli->query($query))
            {
               while ($user = $result->fetch_object('User'))
               {
                  echo $user->info()."\n";
               }
            }




            // MySQLi, "ручная" зачистка параметра
            $username = mysqli_real_escape_string($_GET['username']);
            $mysqli->query("SELECT * FROM users WHERE username = '$username'");

            // mysqli, подготовленные выражения
            $query = $mysqli->prepare('SELECT * FROM users WHERE username = ?');
            $query->bind_param('s', $_GET['username']);
            $query->execute();

		*/
		
		
		

	} # End class
	
?>