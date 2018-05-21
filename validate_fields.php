<?PHP
	require_once("config.php");	
	
	//Used for creating an account, or changing primary email address
	function validate_new_email($email){
		$conn=mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);
		if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {echo "That is not a valid Email address. \n"; return false;}//Email address was invalid
		if(EMAIL_MUST_BE_UNIQUE){
			if ($stmt = $conn->prepare("SELECT COUNT(id) FROM authentication WHERE email = ? LIMIT 1")) {
				$stmt->bind_param('s', $email);
				$stmt->execute();
				$stmt->bind_result($matches);
				$stmt->fetch();
				$conn->close();
				if($matches > 0){ echo "Email already in use, please login or use a different email."; return false;}
				else{ return true; }
			}
		}
	}
	
	//Used for creating an account or changing primary phone number
	function validate_new_phone($phone){
		$has_plus = false;
		if(str_split($phone)[0] == "+"){$has_plus = true;}
		$phone = preg_replace("/[^0-9]/", '', $phone);
		if(strlen($phone) < 9){ echo "Invalid phone number, please use full phone number, area code, and international prefix if applicable.";return false; }
		if($has_plus){$phone = "+".$phone;}
		
		if(CELL_MUST_BE_UNIQUE){
			$conn=mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);
			if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}			
			if ($stmt = $conn->prepare("SELECT COUNT(id) FROM authentication WHERE phone = ? LIMIT 1")) {
				$stmt->bind_param('s', $phone);
				$stmt->execute();
				$stmt->bind_result($matches);
				$stmt->fetch();
				$conn->close();
				if($matches > 0){ echo "Phone number already in use, please login or use a different number."; return false;}
				else{ return true; }
			}
		}
		else{ return true; }
		
	}
	
	//Used for creation of new user name or changing existing username
	function validate_new_username($username){
		if(strlen($username) <= 0){
			echo "Username cannot be blank. Please add a username.";
			return false;
		}
		
		$conn=mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);
		if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
		if(USERNAME_MUST_BE_UNIQUE){
			if ($stmt = $conn->prepare("SELECT COUNT(id) FROM authentication WHERE email = ? OR username = ? LIMIT 1")) {
				$stmt->bind_param('ss', $username, $username);
				$stmt->execute();
				$stmt->bind_result($matches);
				$stmt->fetch();
				$conn->close();
				if($matches > 0){ echo "Username already in use, please use a different username."; return false;}
				else{ return true; }
			}
		}
		return true;
	}
	
?>