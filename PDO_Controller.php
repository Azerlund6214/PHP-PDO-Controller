<?php

	class PDO_Controller
	{

		public $PDO; # Главное подключение к бд

		
		
		
		####################################
		
		
		
		
		
		
		
		
		
		
		
		
		####################################
		
		
		
		public function Get_connection()
		{
			return $this -> db;
		}



		public function __construct( $host = null , $user = null , $pass = null )
		{

		    if( $host && $user && $pass )
                if( ! @$this->Connect($host , $user , $pass) )
                    exit( "<br>Не удалось подключиться к бд. (из конструктора)" );

		}
		
		
		
		
	
		
		
		
		
		/**
         * Подключиться к серверу СУБД
         * @param string $host
         * @param string $user
         * @param string $pass
         * @return bool - true / false
         */
        public function Connect( $host , $user , $pass  )
		{
			
			// MySQLi, процедурная часть
			//$mysqli = mysqli_connect('localhost','username','password','database');

			// MySQLi, ООП
			$mysqli = new mysqli( $host , $user , $pass );

			
			if ($mysqli->connect_errno)
			{
				echo "<hr>Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
				return false;
			}
			
			$mysqli->set_charset( "utf8" );
			
			
			$this -> db = $mysqli;
			

				

			
			return true;
		}


        /**
         * Отключиться от сервера СУБД
         */
		public function Disconnect( )
		{
		    // Нужна проверка что если уже закрыто или пустое

			$this->db->close();
			$this->db = null;

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
         * @return bool = true / false
         */
		function Check_connection(  )
		{
		    # Если еще ни разу не подключались
            if( ! $this->db )
                return false;


            # Если уже подключались, пробуем выполнить запрос.
			$this->Query("SELECT VERSION()");

			if( ! $this->Has_error(  ) )
                return true;


            return false;

		}



        /**
         * Вывести текстом последнюю ошибку mysqli
         */
        public function Echo_error(  )
        {
            if ( $this->db->errno != 0 )
                echo "<hr>Echo_error => (№" . $this->db->errno . ") " . $this->db->error;
            else
                echo "<br>Echo_error => Ошибок нет";
        }

        /**
         * Произошла ли ошибка?
         * @return bool = true / false
         */
        public function Has_error(  )
        {
            if ( $this->db->errno != 0 )
                return true;

            return false;
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