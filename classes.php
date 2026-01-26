<?php
trait database {
	//private string $servername = "localhost";
	//private string $username = "root";
	//private string $password = ""; 
	private $connect = null;
	
	//Open MYSQL connection
	public function connection() {
		if($this->connect == null) {
			try {
				mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
				$host = getenv('DB_HOST') ?: $this->servername;
				$user = getenv('DB_USER') ?: $this->username;
				$pass = getenv('DB_PASS') ?: $this->password;
				$this->connect = new mysqli($host, $user, $pass);
				//$this->connect = new mysqli($this->servername, $this->username, $this->password);
				//echo "<p>Connection succeeds!</p>";
			} catch(mysqli_sql_exception $e) {
				die ($e->getCode(). ":" .$e->getMessage());
			}
		}
		return $this->connect;
		
	}
	//Create database
	public function dbCreation() {
		$db = $this->connection();
		$dbName = getenv('DB_NAME') ?: 'EasyEV_Charging';
		try {
			$sql = "CREATE DATABASE IF NOT EXISTS `$dbName`
	        CHARACTER SET utf8mb4
	        COLLATE utf8mb4_unicode_ci";
			$db->query($sql);
			$db->select_db($dbName);		
			return $db;
		} catch (mysqli_sql_exception $e) {
			die("Error creating database: " . $e->getCode(). ": " . $e->getMessage()); 
		}
	}
	
	public function tableCreation() {

		$db = $this->connection();
		$db = $this->dbCreation();
		try {
			$ev = "CREATE TABLE IF NOT EXISTS charging_stations (
				station_id   INT AUTO_INCREMENT PRIMARY KEY,
				name         VARCHAR(150) DEFAULT 'EV station',
				address 	 VARCHAR(255) NOT NULL,
				city         VARCHAR(255) NOT NULL,
				state        VARCHAR(255) NOT NULL,
				description  VARCHAR(300) NOT NULL,
				cost_per_hr  DECIMAL(6,2) NOT NULL,
				capacity     INT NOT NULL DEFAULT 1,    -- number of chargers here
				available    INT DEFAULT 1    -- current free slots
			)";
			$db->query($ev);
			$session = "CREATE TABLE IF NOT EXISTS sessions (
			session_id   INT AUTO_INCREMENT PRIMARY KEY,
			userId      INT NOT NULL,
			station_id   INT NOT NULL,
			created_at TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,  
			start_time TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP, 
			end_time   TIMESTAMP   NULL        DEFAULT NULL,
			total_cost   DECIMAL(8,2),
			FOREIGN KEY (userId)    REFERENCES users(userId),
			FOREIGN KEY (station_id) REFERENCES charging_stations(station_id)
			)";
			$db->query($session);
			
		} catch (mysqli_sql_exception $e) {
			die("Error creating tables: " . $e->getCode(). ": " . $e->getMessage()); 
		}
	}

	
	// //Close MYSQL connection
	// public function disconnection() {
	// 	$connect = $this->connection();
	// 	$connect->close();
	// 	return $this->connect;
	// }
}

trait EV {
	use database;
	public $evName;
	public $address;
	public $city;
	public $state;
	public $latitude;
	public $longitude;
	public $description;
	public $cost;
	public $capacity;
	public $available;
	
	public function setEV($evName, $address, $city, $state, $description, $cost, $capacity, $available) {
		$this->evName = $evName;
		$this->address = $address;
		$this->city = $city;
		$this->state = $state;
		$this->description = $description;
		$this->cost = $cost;
		$this->capacity = $capacity;
		$this->available = $available;
	}
	
	public function addEV() {
		$db = $this->dbCreation();
		//Create EV table
		try {
			$sql = "CREATE TABLE IF NOT EXISTS charging_stations (
				station_id   INT AUTO_INCREMENT PRIMARY KEY,
				name         VARCHAR(150) DEFAULT 'EV station',
				address 	 VARCHAR(255) NOT NULL,
				city         VARCHAR(255) NOT NULL,
				state        VARCHAR(255) NOT NULL,
				description  VARCHAR(300) NOT NULL,
				cost_per_hr  DECIMAL(6,2) NOT NULL,
				capacity     INT NOT NULL DEFAULT 1,    -- number of chargers here
				available    INT DEFAULT 1    -- current free slots
			)";
			$db->query($sql);
			$stmt = $db->prepare("INSERT INTO charging_stations (name, address, city, state, description,
			cost_per_hr, capacity, available) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("sssssdii",  
			  $this->evName,
			  $this->address,
			  $this->city,
			  $this->state,
			  $this->description,
			  $this->cost,
			  $this->capacity,
			  $this->available);
			$stmt->execute();
			$result = [
			  'evName'  => $this->evName,
			  'evAddress' => $this->address,
			  'evCity'  => $this->city,
			  'evState'  => $this->state,
			  'evDescription'  => $this->description,
			  'evCost'  => $this->cost,
			  'evCapacity'  => $this->capacity,
			  'evAvailable'  => $this->available,

			];
			return $result;
		} catch (mysqli_sql_exception $e) {
			die("Error creating table for charging stations: " . $e->getCode(). ": " . $e->getMessage());
		}	
	}
	
	public function editEV (
		
		int    $stationId,
		string $name,
		string $address,
		string $city,
		string $state,
		string $description,
		float  $cost,
		int    $capacity,
		int    $available,

		){
			try {
				$db = $this->dbCreation();
				$stmt = $db->prepare(
					"UPDATE Charging_stations
              SET name         = ?,
                  address      = ?,
                  city         = ?,
                  state        = ?,
                  description  = ?,
                  cost_per_hr  = ?,
                  capacity     = ?,
                  available    = ?
            WHERE station_id  = ?"
				);
				$stmt->bind_param(
					"sssssdiii",
					$name,
					$address,
					$city,
					$state,
					$description,
					$cost,
					$capacity,
					$available,
					$stationId
					
				);
				$stmt->execute();
				if ($db->affected_rows !== 1) {
					// no slots left, roll back
					$db->rollback();
					return false;
				}
				$db->commit();
				return true;

			} catch(mysqli_sql_exception $e) {
				$db->rollback();
				die("Error editing table charging station: " . $e->getCode(). ": " . $e->getMessage());
			}
			
		}

	public function checkEV($address, $city, $state) {
		$db = $this->dbCreation();
		//Create user table
		try {
			$stmt = $db->prepare("SELECT * FROM charging_stations WHERE address = ? AND city = ? AND state = ?");
			$stmt->bind_param("sss", $address, $city, $state);
			$stmt->execute();
			$res = $stmt->get_result();              
			$all = $res->fetch_all(MYSQLI_ASSOC);
			return $all;
		} catch (mysqli_sql_exception $e) {
			die("Error at table charging station: " . $e->getCode(). ": " . $e->getMessage());
		}
	}
}

trait Session {
	use database;
	public $user_id;
	public $station_id;
	public $start_time;
	
	public function setSession(int $user_id, $start_time) {
		$this->user_id = $user_id;
		$this->start_time = $start_time;
		
	}

	public function informCost($station_Id) {
        
		try{
			$db = $this->dbCreation();
			$sql = "CREATE TABLE IF NOT EXISTS sessions (
			session_id   INT AUTO_INCREMENT PRIMARY KEY,
			userId      INT NOT NULL,
			station_id   INT NOT NULL,
			created_at TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,  
			start_time TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP, 
			end_time   TIMESTAMP   NULL        DEFAULT NULL,
			total_cost   DECIMAL(8,2),
			FOREIGN KEY (userId)    REFERENCES users(userId),
			FOREIGN KEY (station_id) REFERENCES charging_stations(station_id)
			)";

			$db->query($sql);
			$stmt = $db->prepare(
			"SELECT cost_per_hr 
				FROM Charging_stations
				WHERE station_id = ?"
			);

			$stmt->bind_param("s", $station_Id);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc() ?: null;
			
		} catch (mysqli_sql_exception $e) {
			die("Error checking cost at charging stations: " . $e->getCode(). ": " . $e->getMessage());
			return false;
		}
	}

	// public function getSession($userId, $sessionId){
	// 	$db = $this->dbCreation();

	// 	try {
	// 		$stmt = $db->prepare("SELECT * FROM Sessions WHERE userId = ? AND session_id = ? AND end_time IS NULL");
	// 		$stmt->bind_param('ii',$userId, $sessionId);
	// 		$stmt->execute();
	// 		$res = $stmt->get_result();
	// 		$row = $res->fetch_assoc();
	// 			if (! $row) {
	// 				return null;	
	// 			}
	// 		return $row;

	// 	} catch (mysqli_sql_exception $e) {
	// 		die("Error retrieving session: " . $e->getCode(). ": " . $e->getMessage());
	// 	}
		
	// }       
	
	public function checkIn($station_id) {
		
		try {
			$db = $this->dbCreation();
			$sql = "CREATE TABLE IF NOT EXISTS sessions (
			session_id   INT AUTO_INCREMENT PRIMARY KEY,
			userId      INT NOT NULL,
			station_id   INT NOT NULL,
			created_at TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,  
			start_time TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP, 
			end_time   TIMESTAMP   NULL        DEFAULT NULL,
			total_cost   DECIMAL(8,2),
			FOREIGN KEY (userId)    REFERENCES users(userId),
			FOREIGN KEY (station_id) REFERENCES charging_stations(station_id)
			)";

			$db->query($sql);
			//Check if the same user open this session before and haven't been check out
			$stmt1 = $db->prepare("SELECT COUNT(*) FROM sessions WHERE userId = ? AND station_id = ? AND end_time IS NULL");
			$stmt1->bind_param("ii",  $this->user_id, $station_id);

			$stmt1->execute();
			$stmt1->bind_result($openCount);
			$stmt1->fetch();
			$stmt1->close();

			if ($openCount > 0) {
				$db->rollback();
				$html = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><p>You currently checked in at this station!</p>';
				$html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
				$html .= '</div>';
				echo $html;
				return false; //stop further processing if the session is still opened
			}
			//Check if the location is already full
			$stmt2 = $db->prepare("UPDATE charging_stations SET available = available - 1 WHERE station_id = ? AND available > 0");
			$stmt2->bind_param("i",  $station_id);

			$stmt2->execute(); 
			//Check if the issued location is full
			if ($stmt2->affected_rows !== 1) {
				$db->rollback();
				echo "<p class='text-danger'>This station is already fully booked!</p>";
            	return false;
			}
			$stmt2->close();

			//Prepare for insearting if the first and second condition pass
			$stmt3 = $db->prepare("INSERT INTO Sessions (userId, start_time, station_id) VALUES (?,?,?)");
			$stmt3->bind_param("isi", $this->user_id, $this->start_time, $station_id);
			
			$stmt3->execute(); //two conditions pass, then execute this
			$stmt3->close();
			$db->commit();
			$time = date_format(date_create($this->start_time), 'l jS \of F Y h:i:s A'); //Change date format for better readable
	
			$cost_per_hr = $this->informCost($station_id);
			//Create bootstrap alert
			$html = '<div class="alert alert-success alert-dismissible fade show" role="alert">
			<h4>Thank you for checking in with us!</h4>
			<p>Your check-in date is ' . $time .'</p>
			<strong>Successfully booked this station!</strong> Here is the cost per hour you need to pay: <b>$' .$cost_per_hr['cost_per_hr'] .'</b>';
			$html .= '<p>The total cost will be informed when you check out</p>';
			$html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
			$html .= '</div>';
			echo $html;
			//var_dump($cost_per_hr);
			return true;
			//deduct availability in ev
		} catch (mysqli_sql_exception $e) {
			$db->rollback();
			die("Error checking in at charging stations: " . $e->getCode(). ": " . $e->getMessage());
			return false;
		}
	}

	
	public function payment(int $sessionId) {
		$db = $this->dbCreation();
		$stmt = $db->prepare("
		UPDATE sessions AS s
		JOIN charging_stations AS ev
			ON s.station_id = ev.station_id
		SET s.total_cost = 
			ROUND(
			TIMESTAMPDIFF(MINUTE, s.start_time, s.end_time) 
			/ 60.0 * ev.cost_per_hr
			, 2)
		WHERE s.session_id = ?
		");
		$stmt->bind_param('i', $sessionId);
		$stmt->execute();
		
		
		if ($stmt->affected_rows !== 1) {
			throw new Exception("UPDATE payment failed: " . $stmt->error);
			//return null;
		}
		$stmt->close();

		//Fetch back the total cost
		$stmt2 = $db->prepare("
		SELECT total_cost
			FROM sessions
		WHERE session_id = ?
		");
		$stmt2->bind_param('i', $sessionId);
		$stmt2->execute();
		$res = $stmt2->get_result();
		$row = $res->fetch_assoc();
		return $row ? (float)$row['total_cost'] : null;
	}
	
	
	public function checkOut(int $sessionId, string $endTime, int $stationId){
		$db = $this->dbCreation();
		//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		try {
			$stmt = $db->prepare("
			UPDATE sessions
				SET end_time = ?
			WHERE session_id = ?
				AND end_time IS NULL
			");
			$stmt->bind_param('si', $endTime, $sessionId);
			$stmt->execute();
			if ($stmt->affected_rows !== 1) {
				$db->rollback();
				return null;
			}

			$stmt->close();

			//release the booked spot
			$stmt2 = $db->prepare("UPDATE charging_stations SET available = available + 1 WHERE station_id = ?");
			$stmt2->bind_param("i",  $stationId);

			$stmt2->execute(); 
			//Check if releasing is successful
			if ($stmt2->affected_rows !== 1) {
				$db->rollback();
            	return null;
			}
			$stmt2->close();
			$payment = $this->payment($sessionId);
			return $payment;

		} catch (mysqli_sql_exception $e) {
			$db->rollback();
			die("Error checking out session: " . $e->getCode(). ": " . $e->getMessage());
		}
		
	}

	public function listCheckIn(int $userId){
		$db = $this->dbCreation();
		try {
			$sql = "CREATE TABLE IF NOT EXISTS sessions (
			session_id   INT AUTO_INCREMENT PRIMARY KEY,
			userId      INT NOT NULL,
			station_id   INT NOT NULL,
			created_at TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,  
			start_time TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP, 
			end_time   TIMESTAMP   NULL        DEFAULT NULL,
			total_cost   DECIMAL(8,2),
			FOREIGN KEY (userId)    REFERENCES users(userId),
			FOREIGN KEY (station_id) REFERENCES charging_stations(station_id)
			)";

			$db->query($sql);
			$stmt = $db->prepare("
			SELECT
				s.session_id,
				s.station_id,
				s.start_time,
				s.end_time,
				s.total_cost,
				c.name        AS station_name,
				c.address     AS station_address,
				c.cost_per_hr
			FROM Sessions AS s
			JOIN charging_stations AS c
				ON s.station_id = c.station_id
			WHERE s.userId = ?
				AND s.end_time IS NULL
			ORDER BY s.end_time DESC
			");
			$stmt->bind_param('i', $userId);
			$stmt->execute();
			$res = $stmt->get_result();
			
			$all = [];
			while ($row = $res->fetch_assoc()) {
				$all[] = [
					'session_Id'      => (int)$row['session_id'],
					'station_Id'      => (int)$row['station_id'],
					'stationName'    => $row['station_name'],
					'stationAddress' => $row['station_address'],
					'start_time'      => $row['start_time'],
					'end_time'        => $row['end_time'],
					'total_cost'      => (float)$row['total_cost'],
				];
			}
			$stmt->close();
			return $all === [] ? null : $all;

		} catch (mysqli_sql_exception $e) {
			die("Error retrieving active sessions: " . $e->getCode(). ": " . $e->getMessage());
		}
		
	}
	
	public function listCheckOut(int $userId){
		$db = $this->dbCreation();
		try {
			$stmt = $db->prepare("
			SELECT
				s.session_id,
				s.station_id,
				s.start_time,
				s.end_time,
				s.total_cost,
				ev.name        AS station_name,
				ev.address     AS station_address,
				ev.cost_per_hr
			FROM Sessions AS s
			JOIN charging_stations AS ev
				ON s.station_id = ev.station_id
			WHERE s.userId = ?
				AND s.end_time IS NOT NULL
			ORDER BY s.end_time DESC
			");
			$stmt->bind_param('i', $userId);
			$stmt->execute();
			$res = $stmt->get_result();
			
			$all = [];
			while ($row = $res->fetch_assoc()) {
				$all[] = [
					'session_id'      => (int)$row['session_id'],
					'station_id'      => (int)$row['station_id'],
					'stationName'    => $row['station_name'],
					'stationAddress' => $row['station_address'],
					'start_time'      => $row['start_time'],
					'end_time'        => $row['end_time'],
					'total_cost'      => (float)$row['total_cost'],
				];
			}
			$stmt->close();
			return $all === [] ? null : $all;

		} catch (mysqli_sql_exception $e) {
			die("Error retrieving past sessions: " . $e->getCode(). ": " . $e->getMessage());
		}
		
	}
	
}

interface searchLocations
{
   public function searchEV($stationId, string $description);
}

//User class
class User implements searchLocations {
	use database;
	use Session;
	public string $userName;
	public string $userPhone;
	public $userEmail;
	public $userPassword;
	public $userRole;
	
	public function  setUser(string $userEmail, $userPassword) {
		$this->userEmail = $userEmail;
		$this->userPassword = $userPassword;
		//echo "<p>The current user is" .$this->userEmail ."with" .$this->userPassword ."</p>";
		
	}
	
	public function setInfo(string $userName, string $userPhone, string $userRole) {
		$this->userName = $userName;
		$this->userPhone = $userPhone;
		$this->userRole = $userRole;
	}
	
	public function addUser(string $userEmail, $userPassword, string $userName, string $userPhone, string $userRole) {
		$db = $this->dbCreation();
		//Create user table
		try {
			$sql = "CREATE TABLE IF NOT EXISTS users (
			userId INT NOT NULL AUTO_INCREMENT,
			name VARCHAR(60) NOT NULL,
			phone VARCHAR(10) NOT NULL,
			email VARCHAR(100) UNIQUE NOT NULL,
			password CHAR(60) NOT NULL,
			role VARCHAR(15) NOT NULL,
			reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
			)";
			$db->query($sql);
			$stmt = $db->prepare("INSERT INTO users (name, phone, email, password, role) VALUES (?, ?, ?, ?, ?)");
			$stmt->bind_param("sssss", 
				$userName, 
				$userPhone, 
				$userEmail, 
				$userPassword, 
				$userRole
			);
			$stmt->execute();
			
			$user = [
			  'userName'  => $userName,
			  'userEmail' => $userEmail,
			  'userRole'  => $userRole
			];
			$stmt->close();
			return $user;
		} catch (mysqli_sql_exception $e) {
			die("Error creating table: " . $e->getCode(). ": " . $e->getMessage());
		}
	}
	
	public function getUser(string $userEmail, string $userPassword) {
		$db = $this->dbCreation();
		//Create user table
		try {
			$sql = "CREATE TABLE IF NOT EXISTS users (
			userId INT NOT NULL AUTO_INCREMENT,
			name VARCHAR(60) NOT NULL,
			phone VARCHAR(10) NOT NULL,
			email VARCHAR(100) UNIQUE NOT NULL,
			password CHAR(60) NOT NULL,
			role VARCHAR(15) NOT NULL,
			reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (userId)
			)";
			$db->query($sql);
			$stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
			$stmt->bind_param("ss", $userEmail, $userPassword);
			$stmt->execute();
			$res = $stmt->get_result();              
			$row = $res->fetch_assoc();
			if (! $row) {
				return null;	
			}
			$result = array("userId"=>$row["userId"], "userName"=>$row["name"], "userEmail"=>$row["email"], "userRole"=>$row["role"]);
			$stmt->close();
			return $result;

		} catch (mysqli_sql_exception $e) {
			die("Error at table: " . $e->getCode(). ": " . $e->getMessage());
		}
	}

	public function checkUser(string $userEmail) {
		$db = $this->dbCreation();
		//Create user table
		try {
			$stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
			$stmt->bind_param("s", $userEmail);
			$stmt->execute();
			$res = $stmt->get_result();              
			$row = $res->fetch_assoc();
			if (! $row) {
				return true;	
			} else {
				return false;
			}
		} catch (mysqli_sql_exception $e) {
			die("Error at table: " . $e->getCode(). ": " . $e->getMessage());
		}
	}
	
	 public function searchEV($stationId, string $description){
        
		try {
			$db = $this->dbCreation();
			$sql = "CREATE TABLE IF NOT EXISTS charging_stations (
				station_id   INT AUTO_INCREMENT PRIMARY KEY,
				name         VARCHAR(150) DEFAULT 'EV station',
				address 	 VARCHAR(255) NOT NULL,
				city         VARCHAR(255) NOT NULL,
				state        VARCHAR(255) NOT NULL,
				description  VARCHAR(300) NOT NULL,
				cost_per_hr  DECIMAL(6,2) NOT NULL,
				capacity     INT NOT NULL DEFAULT 1,   
				available    INT DEFAULT 1    
			)";
			$db->query($sql);
			
			if (trim($description) !== null && !empty(trim($description))) {
				if (!empty(trim($stationId))) {
					$query = "SELECT * FROM charging_stations WHERE station_id = ? AND description LIKE ?";
					$like = "%{$description}%";
					$stmt = $db->prepare($query);
					$stmt->bind_param("is", $stationId, $like);
					$stmt->execute();
					$res = $stmt->get_result();
					$result = $res->fetch_all(MYSQLI_ASSOC) ?: null;
					$stmt->close();
					return $result;
				} else {
					$query = "SELECT * FROM charging_stations WHERE description LIKE ?";
					$like = "%{$description}%";
					$stmt = $db->prepare($query);
					$stmt->bind_param("s", $like);
					$stmt->execute();
					$res = $stmt->get_result();
					$result = $res->fetch_all(MYSQLI_ASSOC) ?: null;
					$stmt->close();
				return $result;
				}
		}  else if (empty(trim($description)) && !empty(trim($stationId))) {
			$query = "SELECT * FROM charging_stations WHERE station_id = ?";
			$stmt = $db->prepare($query);
			$stmt->bind_param("i", $stationId);
			$stmt->execute();
			$res = $stmt->get_result();
			$result = $res->fetch_all(MYSQLI_ASSOC) ?: null;
			$stmt->close();
        	return  $result;
		}

        return null;

		} catch (mysqli_sql_exception $e) {
			die("Error at table: " . $e->getCode(). ": " . $e->getMessage());
		}	
	 }

	public function listAvailableEVs() {
		$db = $this->dbCreation(); 
		$stmt = $db->prepare(
			"SELECT * FROM charging_stations WHERE available > 0"
		);
		$stmt->execute();
		$res = $stmt->get_result();
		$avail = $res->fetch_all(MYSQLI_ASSOC) ?: null;
		$stmt->close();
		return $avail;
	}
	
}

//Admin class
class Admin extends User implements searchLocations {
	use database;
	use EV;
	use Session;
	
	 public function searchEV($stationId, string $description){
        
		try {
			$db = $this->dbCreation();
			$sql = "CREATE TABLE IF NOT EXISTS charging_stations (
				station_id   INT AUTO_INCREMENT PRIMARY KEY,
				name         VARCHAR(150) DEFAULT 'EV station',
				address 	 VARCHAR(255) NOT NULL,
				city         VARCHAR(255) NOT NULL,
				state        VARCHAR(255) NOT NULL,
				description  VARCHAR(300) NOT NULL,
				cost_per_hr  DECIMAL(6,2) NOT NULL,
				capacity     INT NOT NULL DEFAULT 1,   
				available    INT DEFAULT 1    
			)";
			$db->query($sql);
			
			if (trim($description) !== null && !empty(trim($description))) {
				if (!empty(trim($stationId))) {
					$query = "SELECT * FROM charging_stations WHERE station_id = ? AND description LIKE ?";
					$like = "%{$description}%";
					$stmt = $db->prepare($query);
					$stmt->bind_param("is", $stationId, $like);
					$stmt->execute();
					$res = $stmt->get_result();
					$all = $res->fetch_all(MYSQLI_ASSOC) ?: null;
					$stmt->close();
				} else {
					$query = "SELECT * FROM charging_stations WHERE description LIKE ?";
					$like = "%{$description}%";
					$stmt = $db->prepare($query);
					$stmt->bind_param("s", $like);
					$stmt->execute();
					$res = $stmt->get_result();
					$all = $res->fetch_all(MYSQLI_ASSOC) ?: null;
					$stmt->close();
					return $all;
				}
		}  else if (empty(trim($description)) && !empty(trim($stationId))) {
			$query = "SELECT * FROM charging_stations WHERE station_id = ?";
			$stmt = $db->prepare($query);
			$stmt->bind_param("i", $stationId);
			$stmt->execute();
        	$res = $stmt->get_result();
			$all = $res->fetch_all(MYSQLI_ASSOC) ?: null;
			$stmt->close();
			return $all;
		}

        return null;

		} catch (mysqli_sql_exception $e) {
			die("Error at table: " . $e->getCode(). ": " . $e->getMessage());
		}	
		

    }
	
	public function listAllUser() {
		$db = $this->dbCreation();
		
		$stmt = $db->prepare("SELECT name, email, phone, role FROM users");
		$stmt->execute();
		$res = $stmt->get_result();
		$all = $res->fetch_all(MYSQLI_ASSOC) ?: null;
		$stmt->close();
		return $all;
	}
	
	public function listActiveUsers(){

		try {
			$db = $this->dbCreation();
			//Prevent error throwing from non-existing table
			$sql = "CREATE TABLE IF NOT EXISTS sessions (
			session_id   INT AUTO_INCREMENT PRIMARY KEY,
			userId      INT NOT NULL,
			station_id   INT NOT NULL,
			created_at TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,  
			start_time TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP, 
			end_time   TIMESTAMP   NULL        DEFAULT NULL,
			total_cost   DECIMAL(8,2),
			FOREIGN KEY (userId)    REFERENCES users(userId),
			FOREIGN KEY (station_id) REFERENCES charging_stations(station_id)
			)";

			$db->query($sql);
			$stmt = $db->prepare("
			SELECT DISTINCT u.name, u.email, u.phone
				FROM sessions s
				JOIN users u  ON s.userId = u.userId
			WHERE s.end_time IS NULL
			");
			$stmt->execute();
			$res = $stmt->get_result();
			
			$active = [];
			while ($row = $res->fetch_assoc()) {
				$active[] = [
					'name'  => $row['name'],
					'email' => $row['email'],
					'phone' => $row['phone'],
				];
			}
			$stmt->close();
			//var_dump($active);
			return $active;

		} catch (mysqli_sql_exception $e) {
			die("Error at table: " . $e->getCode(). ": " . $e->getMessage());
		}	
		
	}

	public function listAllEVs() {
		try {
			$db = $this->dbCreation();
			$sql = "CREATE TABLE IF NOT EXISTS charging_stations (
				station_id   INT AUTO_INCREMENT PRIMARY KEY,
				name         VARCHAR(150) DEFAULT 'EV station',
				address 	 VARCHAR(255) NOT NULL,
				city         VARCHAR(255) NOT NULL,
				state        VARCHAR(255) NOT NULL,
				description  VARCHAR(300) NOT NULL,
				cost_per_hr  DECIMAL(6,2) NOT NULL,
				capacity     INT NOT NULL DEFAULT 1,   
				available    INT DEFAULT 1    
			)";
			$db->query($sql);
			$res = $db->query("SELECT * FROM charging_stations");
			$all = $res->fetch_all(MYSQLI_ASSOC) ?: null;
			//var_dump($all);
			return $all ?: null;
		} catch (mysqli_sql_exception $e) {
			die("Error at table: " . $e->getCode(). ": " . $e->getMessage());
		}	
		
	}
	
	
	public function listFullEVs(){
		$db = $this->dbCreation();
		try {
			$db = $this->dbCreation();
			$sql = "CREATE TABLE IF NOT EXISTS charging_stations (
				station_id   INT AUTO_INCREMENT PRIMARY KEY,
				name         VARCHAR(150) DEFAULT 'EV station',
				address 	 VARCHAR(255) NOT NULL,
				city         VARCHAR(255) NOT NULL,
				state        VARCHAR(255) NOT NULL,
				description  VARCHAR(300) NOT NULL,
				cost_per_hr  DECIMAL(6,2) NOT NULL,
				capacity     INT NOT NULL DEFAULT 1,   
				available    INT DEFAULT 1    
			)";
			$db->query($sql);
			$stmt = $db->prepare(
			"SELECT * FROM charging_stations WHERE available = 0"
			);
			$stmt->execute();
			$res = $stmt->get_result();
			$full = $res->fetch_all(MYSQLI_ASSOC) ?: null;
			$stmt->close();
			return $full;

		} catch (mysqli_sql_exception $e) {
			die("Error at table: " . $e->getCode(). ": " . $e->getMessage());
		}	
		
	}
}






?>
