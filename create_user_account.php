<?php
require_once("config.php");
require_once("validate_fields.php");
//this file should be everything required to insert and updat events in the logs table as users perform different actions.

	
	function create_new_user($username, $password, $email, $recovery_email, $phone, $security_file, $distress_pwd, $distress_file, $geo, $permissions, $mfa_types, $is_admin){
		$conn=mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);
		if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}	
		$ip = $_SERVER['REMOTE_ADDR'];
		$hashed_AES = hash(HASH_METHOD, AES_KEY);
		
		if($password == $distress_pwd && !FILE_CAN_REPLACES_PASSWORD){
			echo "Your distress password, and standard password cannot be the same. Please change one of them";
			return false;
		}
		if(ENFORCE_PASSWORD_FILE_FOR_ALL){
			$lines = file(PASSWORD_FILE, FILE_IGNORE_NEW_LINES);
			foreach($lines as $entry){
				if($entry == $password){
					echo "Your password is commonly used and therefore is not accepted. Please us a different password.";
					return false;
				}
				if($entry == $distress_pwd){
					echo "Your distress password is commonly used and therefore is not accepted. Please us a different distress password.";
					return false;
				}
			}
		}else{	
			$lines = file(PASSWORD_FILE, FILE_IGNORE_NEW_LINES);
			foreach($lines as $entry){
				if($entry == $password){
					echo "Your password is commonly used and therefore is not accepted. Please us a different password.";
					return false;
				}
			}
		}
		if((strlen($password) <= MIN_PASSWORD_LENGTH || $password == null) && !FILE_CAN_REPLACES_PASSWORD){
			echo "Password must be at least ".MIN_PASSWORD_LENGTH." long."; 
			return false; 
		} else { 
			if($password == null || strlen($password) <= 0){
				$password = null;
			} else {
				$password = password_hash($password, PASSWORD_DEFAULT, ['cost' => HASH_COST]); 
			}
		}
		if(strlen($distress_pwd) <= 0 || $distress_pwd == null){ $distress_pwd = null; echo "Null distress";}
		else{$distress_pwd = password_hash($distress_pwd, PASSWORD_DEFAULT, ['cost' => HASH_COST]);}		
		
		
		if(!validate_new_email($email)){return false;}
		if(!validate_new_phone($phone)){return false;}
		if(!validate_new_username($username)){return false;}
		
		
		
		$stmt = $conn->prepare("INSERT INTO authentication (".
		"username,
		email,
		recovery_email,
		phone,
		ip,
		geo_region,
		mfa_types,
		permissions,
		password,
		security_file,
		distress_pwd,
		distress_file
		) VALUES (?,?,?,?,?,?,?,?, AES_ENCRYPT(?,?), AES_ENCRYPT(?,?), AES_ENCRYPT(?,?), AES_ENCRYPT(?,?))");
		$stmt->bind_param("ssssssssssssssss", $username, $email, $recovery_email, $phone, $ip, $geo, $mfa_types, $permissions, $password, $hashed_AES, $security_file, $hashed_AES, $distress_pwd, $hashed_AES, $distress_file, $hashed_AES);//$distress_file, $hashed_AES);
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}	
	function compare_aes(){
		$conn=mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);
		if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}	
		
		$stmt = $conn->query("SELECT *, AES_DECRYPT(password, '".hash(HASH_METHOD, AES_KEY)."') first_name_decrypt FROM authentication");
		while($row = $stmt->fetch_assoc()){
			echo "<br> ASDF <br>";
			//echo $row["password"];
			echo $row["first_name_decrypt"];
			echo $conn->error;
			if (password_verify("mypassword", $row["first_name_decrypt"])){
				echo "MAtched";
			}
			else {echo "Fuck my nutts";}
		}
		
	}
	
	//ADD CAPTCHA TO ENSURE NO BOTS GET PAST THIS...
	
	
	
		
	//Prototype vars...
	$username 		= null;
	$email 			= null;
	$password 		= null;
	$distress_pwd	= null;
	$recovery_email = null;
	$phone			= null;
	$sec_file		= null;
	$dist_file		= null;
	$geo 			= null;
	$permissions 	= null;
	$mfa_types		= null;
	$is_admin		= false;
	
	foreach(REQUIRED_FIELDS as $field_name){
		if(FILE_CAN_REPLACES_PASSWORD && $field_name == "password"){
			if (empty($_POST["password"])){
				if(isset($_FILES['sec_file']['tmp_name'])){
					if(file_exists($_FILES['sec_file']['tmp_name']) || is_uploaded_file($_FILES['sec_file']['tmp_name'])) {
						if ( 0 < $_FILES['sec_file']['error'] ) {echo 'Error: ' . $_FILES['file']['error'] . '<br>';return false;}
						else { $sec_file = password_hash(hash(HASH_METHOD, $_FILES['sec_file']['tmp_name']), PASSWORD_DEFAULT, ['cost' => HASH_COST]); continue;}	
					}else{$sec_file = null; echo "Without a password you must supply a security file."; return false;}
				}
			}
		}
		if (!empty($_POST[$field_name])) {continue;}
		else{ echo $field_name." cannot be blank."; return false; }
	}
	
	if (!empty($_POST["username"])) {$username = $_POST["username"];}	
	if (!empty($_POST["password"])) {$password = $_POST["password"];}
	if (!empty($_POST["distress_pwd"])) {$distress_pwd = $_POST["distress_pwd"];}
	if (!empty($_POST["email"])) {$email = $_POST["email"];}
	if (!empty($_POST["recovery_email"])) {$recovery_email = $_POST["recovery_email"];}
	if (!empty($_POST["phone"])) {$phone = $_POST["phone"];}
	if (!empty($_POST["geo"])) {$geo = $_POST["geo"];}
	if (!empty($_POST["permissions"])) {$permissions = $_POST["permissions"];}
	if (!empty($_POST["mfa_types"])) {$mfa_types = $_POST["mfa_types"];}
	if (!empty($_POST["is_admin"])) {$is_admin = $_POST["is_admin"];}
	
	if(strlen($password) <= 0){
		$password = null;
	}

	if(isset($_FILES['sec_file']['tmp_name'])){
		if(file_exists($_FILES['sec_file']['tmp_name']) || is_uploaded_file($_FILES['sec_file']['tmp_name'])) {
			if ( 0 < $_FILES['sec_file']['error'] ) {echo 'Error: ' . $_FILES['file']['error'] . '<br>';return false;}
			else { $sec_file = password_hash(hash(HASH_METHOD, $_FILES['sec_file']['tmp_name']), PASSWORD_DEFAULT, ['cost' => HASH_COST]); }	
		}else{$sec_file = null;}
	}
	if(isset($_FILES['dist_file']['tmp_name'])){
		if(file_exists($_FILES['dist_file']['tmp_name']) || is_uploaded_file($_FILES['dist_file']['tmp_name'])) {
			if ( 0 < $_FILES['dist_file']['error'] ) {echo 'Error: ' . $_FILES['file']['error'] . '<br>';return false;}
			else { $dist_file = password_hash(hash(HASH_METHOD, $_FILES['dist_file']['tmp_name']), PASSWORD_DEFAULT, ['cost' => HASH_COST]);}	
		}else{$dist_file = null;}	
	}
create_new_user($username, $password, $email, $recovery_email, $phone, $sec_file, $distress_pwd, $dist_file, $geo, $permissions, $mfa_types, $is_admin);
//sending file with Ajax....	https://stackoverflow.com/questions/23980733/jquery-ajax-file-upload-php
//compare_aes();
?>
