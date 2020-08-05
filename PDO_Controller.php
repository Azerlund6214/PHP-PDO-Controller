<?php

	class PDO_C
	{

		public $connection; # Главное подключение к бд. Получать через getConnection()
		
		public $connectionString = null; # Записывается только в случае успеха
		public $username = ''; #
		public $password = ''; #
		
		
		public $last_statement = ""; # Результат последнего запроса. Висит в памяти, поэтому не держать крупные запросы.
		
		
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

				//$this -> connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
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
			
			if( $this->Have_error() )
				$this->Echo_error( true ); # Вывести инфу и ВЫЙТИ
			
		}
		
		
		####################################
		### Исключения и отладка.
		
		/**
		 * Произошла ли ошибка?
		 * @param object PDO::Statement
		 * @return bool = true / false
		 */
		public function Have_error( $statement = null )
		{
			if ( $this->connection->errorCode() != "00000" )
				return true;
			
			if ( !is_null( $statement ))
				if ( $statement->errorCode() != "00000" )
					return true;
			
			return false;
		}
		
		/**
		 * Вывести текстом последнюю ошибку PDO или Statement
		 * @param bool $Exit_after_echo - Завершить скрипт после вывода
		 * @param object PDO::Statement
		 */
		public function Echo_error( $Exit_after_echo = false , $stmt = null)
		{
			# https://www.php.net/manual/ru/pdo.errorinfo.php
			# https://www.php.net/manual/ru/pdo.errorcode.php
			# https://www.php.net/manual/ru/pdo.error-handling.php
			
			$con = $this->getConnection();
			
			$have_error = false;
			
			
			if ( $con->errorCode() != "00000" )
			{
				echo "<hr>Echo_error => Есть ошибки в PDO CONNECTION." ;
				echo "<br><br>PDO::errorCode() => " . $con->errorCode() ;
				echo "<br>PDO::errorInfo() [2] => " . $con->errorInfo()[2] ;
				
				echo "<br><br>PDO::errorInfo() => " ;
				echo "<pre>"; print_r ( $con->errorInfo() ); echo "</pre>";
				
				$have_error = true;
			}
			
			if ( !is_null($stmt) )
				if ( $stmt->errorCode() != "00000" )
				{
					echo "<hr>Echo_error => Есть ошибки в PDO Statement" ;
					echo "<br><br>PDO::errorCode() => " . $stmt->errorCode() ;
					echo "<br>PDO::errorInfo() [2] => " . $stmt->errorInfo()[2] ;
					
					echo "<br><br>PDO::errorInfo() => " ;
					echo "<pre>"; print_r ( $stmt->errorInfo() ); echo "</pre>";
					
					$have_error = true;
				}
			
			if( ! $have_error )
				echo "<br>Echo_error => Ошибок нет";
			
			
			if ( $Exit_after_echo )
				exit("<hr>PDO->Echo_error - Exit_after_echo=true");
		}
		

		
		
		

		####################################
		###
		
		
		/**
		 * Исполняет запрос с параметрами (и без них)
		 * Результат получаестся в отдельном методе ->fetcher() !!!!!
		 * Если запрос не прошел, то будет выведена подробная ошибка и ВЫХОД
		 * @param $query
		 * @param array $parameters = Значения для подстановки [':id'=>90 ... ]
		 * @return array|string
		 */
		public function Query( $query , $parameters = array( ) )
		{
			$con = $this->getConnection();
			
			
			$statement = $con->prepare($query);
			$statement->execute($parameters);
			
			# Проверка на ошибку в запросе.
			if( $this->Have_error( $statement ) )
				$this->Echo_error(true , $statement ); # Вывести инфу и ВЫЙТИ
			
			$this->last_statement = $statement;
			
		}
		
		
		/**
		 * Выдает результат последней выборки в удобном виде.
		 * Если выборка была большая, то после желательно вызвать ->Clear_Last_Stmt()
		 * @param string $mode
		 * @return array|int
		 */
		public function fetcher( $mode = "Assoc" )
		{
			$stmt = $this->last_statement;
			
			if ( ! $stmt )
				return "Last statement is empty (null).";
			
			
			
			switch ( strtolower( $mode ) )
			{
				case "a":
				case "as":
				case "ass":
				case "all":
				case "assoc":
					return $stmt->fetchAll( PDO::FETCH_ASSOC );
					# [0,1,2...][имя столбца]=>значение
					# Массив со всеми строками результата
					# МНОГО строк в асоциативном массиве
					# SELECT много
	
				case "o":
				case "one":
				case "row":
				case "onerow":
				case "one_row":
					return $stmt->fetch( PDO::FETCH_ASSOC );
					# [имя столбца]=>значение
					# ОДНА строка (первая из набора)
					# SELECT с WHERE или когда нужна любая строка из таблицы
				
				case "c":
				case "count":
				case "rowcount":
				case "countrows":
				case "row_count":
					return $stmt->rowCount(  );
					# 4 / 50 / 0 ...
					# Число строк в последнем запросе
					# Для INSERT, UPDATE, DELETE
				
				case "l":
				case "last":
				case "id":
				case "lastid":
					return $this->getConnection()->lastInsertId();
					# id последней вставленной записи
					#if ( preg_match('/^\s*INSERT\s/i', $query) )
				
				
				default: exit( "<hr>PDO->fetcher() - Выпал case-default = $mode" );
			}
			
		
		}
		
		
		/**
		 * Очищает результаты последнего запроса. (что бы не висел в памяти класса)
		 */
		public function Clear_Last_Stmt(  )
		{
			$this->last_statement = null;
		}
		
		
		
	
		

		

		
		
		####################################
		###
		
		/*
			Просто примеры различных запросов, что бы не искать
			SELECT col1, col2, col3 FROM tablename WHERE col4=? LIMIT ?
			SELECT name, colour, calories      FROM fruit  	WHERE colour = :colour
			UPDATE my_table SET fname = ?, lname = ? WHERE id = ?
			INSERT INTO fruits( name, colour )    	VALUES( :name, ":colour" )
			INSERT INTO table (name, length, price)    VALUES (?,?,?)
		*/
		
		####################################
		###



		
		
		
		

	} # End class
	
?>