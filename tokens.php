<?php
require_once("config.php");
//Huge thanks to Scott Arciszewski for creating the starting base of this code!
//https://stackoverflow.com/questions/3128985/php-login-system-remember-me-persistent-cookie
//Long Ref:
//https://paragonie.com/blog/2015/04/secure-authentication-php-with-long-term-persistence



//ON GOOD LOGIN, SEARCH AUTH_TOKENS TABLE AND DELETE ANYTHING WITH THAT USER ID.... NEXT, CREATE NEW ENTRY IF REMEMBER ME BOX WAS CHECKED.



//Runs after a sucessful login (pass user id)
function set_token($userid){
	$conn=mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);
	if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}	
	
	$selector = base64_encode(random_bytes(9));
	$authenticator = random_bytes(33);
	$hashed_authenticator = hash(HASH_METHOD, $authenticator);
	$exp_date = date('Y-m-d\TH:i:s', EXPIRE_TIME);
	$ip = $_SERVER['REMOTE_ADDR'];
	setcookie(COOKIE_NAME, $selector.':'.base64_encode($authenticator), EXPIRE_TIME, "/", DOMAIN, true, true);

	$stmt = $conn->prepare("INSERT INTO auth_tokens (selector, token, userid, expires, ip) VALUES (?, ?, ?, ?, ?)");
	$stmt->bind_param("ssiss", $selector, $hashed_authenticator, $userid, $exp_date, $ip);
	
	$stmt->execute();
	$conn->close();
	
}

//Runs if cookie 'rememberme' is set.
function validate_token(){
	$conn=mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);
	if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}	
	
	//Login will set session so it will NOT be empty, thus voiding this if they are already logged in (via sessions)...
	if (empty($_SESSION['userid']) && !empty($_COOKIE[COOKIE_NAME])) {
		if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}	
		
		list($selector, $authenticator) = explode(':', $_COOKIE[COOKIE_NAME]);
		$ip = $_SERVER['REMOTE_ADDR'];

		if ($stmt = $conn->prepare("SELECT token, userid, ip, expires FROM auth_tokens WHERE selector = ? LIMIT 1")) {
			$stmt->bind_param("s", $selector); 
			$stmt->execute();
			$stmt->bind_result($token, $userid, $original_ip, $exp_date);
			$stmt->fetch()
			if (hash_equals($token, hash(HASH_METHOD, base64_decode($authenticator)))) {
				if($original_ip == $ip){	
					if(strtotime($exp_date) >= strtotime(date('Y-m-d H:i:s'))){							
						$_SESSION['userid'] = $userid;
						remove_token($selector);
						set_token($userid);
						echo "Login successful!";
						return true;
					}
						else{
							echo "Cookie has expired.";
							//update log table
							//redirect for login prompt.
							remove_token($selector);
							return false;
						}
					}
					else{
						//update log table
						//clear auth table where selector == selector (this cookie dies only!)
						//tell user we see they're on a new computer...
						echo "Looks like this is a new computer or changed your login location. Please re-authenticate to make sure it's you!";
						remove_token($selector);
						return false;
					}
				}
				else{
					//update log table
					remove_token($selector);
					echo "Bad cookie. You're attempt has been logged, repeated attempts for fake-authorization will result in a ban.";
				//On each entry, check the log table for the last handful of requests made from IPaddress X, if it has attempted more than 20 logins in 20 minutes, ban it.				
			}
}
	
}
function remove_token($selector){
	$conn=mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);
	if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}	
	$stmt = $conn->prepare("DELETE FROM auth_tokens WHERE selector = ?");
	$stmt->bind_param("s", $selector);
	$stmt->execute();
	$conn->close();
	
	
	
}
//Debuging
function show_token(){
	if(isset($_COOKIE[COOKIE_NAME])) {
		echo $_COOKIE[COOKIE_NAME];
	}
	else{
		echo "Not set...";
	}
}
//set_token(5);
show_token();
validate_token();

?>