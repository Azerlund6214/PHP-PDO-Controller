<?php

	class DB_Controller
	{

		public $db; # Главное подключение к бд

		
		####################################
		
		
		public function Get_connection()
		{
			return $this -> db;
		}
		
		public function __construct()
		{	//exit("123");
		}
		

		
		

        /**
         * Подключиться к серверу СУБД
         * @param string $host
         * @param string $user
         * @param string $pass
         * @return bool - true либо exit()
         */
        public function Connect( $host , $user , $pass  )
		{
			
			// MySQLi, процедурная часть
			//$mysqli = mysqli_connect('localhost','username','password','database');

			// MySQLi, ООП
			$mysqli = new mysqli( $host , $user , $pass );

			
			if ($mysqli->connect_errno)
			{
				echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
				exit;
			}
			
			$mysqli->set_charset( "utf8" );
			
			
			$this -> db = $mysqli;
			

				
			//echo $mysqli->host_info . "\n";
			
			return true;
		}


        /**
         * Отключиться от сервера СУБД
         */
		public function Disconnect( )
		{
			$this->db->close();
		}


        /**
         * Вывести последнюю ошибку mysqli
         * @param bool $need_bool - Возвращать значение или просто сделать echo
         * @return bool или ничего
         */
		public function Get_error( $need_bool = false )
		{
			if ( $this->db->errno != 0 )
			{
                if($need_bool)
                    return true;

                echo "Get_error => (№" . $this->db->errno . ") " . $this->db->error;
            }
			else
			{
                if($need_bool)
                    return false;

                echo "Get_error => Ошибок нет";
            }


		}
		
		
        /**
         * Выполнить запрос БЕЗ возвращаемого результата
         * @param $sql
         * TODO: Добавить реакцию на ошибку в запросе(неудачный запрос при кривом sql)
         */
		public function Exec( $sql )
		{
			$this->db -> query( $sql );
		
		}

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


        /**
         * Выбрать рабочую БД
         * @param string $target_db
         */
		public function Select_db( $target_db )
		{
			$this->db -> query("USE $target_db");
		}
		

        /**
         * Проверка работоспособности соединения с СУБД
         * Выведет версию СУБД и выйдет.
         */
		function test_conn()
		{
			echo $this->Query('SELECT VERSION()')[0][0];
			exit;
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