<?PHP
require_once("config.php");
function create_auth_tokens_table(){
	$conn=mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);
	if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
	
	$sql = "CREATE TABLE IF NOT EXISTS auth_tokens (
			id INT(11) NOT NULL AUTO_INCREMENT,
			selector VARCHAR(12),
			token VARCHAR(96),
			userid INT(11) NOT NULL,
			expires DATETIME,
			ip VARCHAR(45),
			PRIMARY KEY (id)
		);";
	if ($conn->query($sql) === TRUE) { echo "Table created successfully";} 
	else { echo "Error creating table: " . $conn->error; }
	
	$conn->close();
	
}
function create_logs_table(){
	$conn=mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);
	if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
	$sql = "CREATE TABLE IF NOT EXISTS logs (
		id INT(20) NOT NULL AUTO_INCREMENT,
		WARNING_LEVEL INT(2),
		EVENT VARCHAR(60),
		SUCCESSFUL BOOLEAN,
		SESSION_ID VARCHAR(128),
		GATEWAY_INTERFACE VARCHAR(20),
		SERVER_ADDR VARCHAR(45),
		SERVER_NAME VARCHAR(100),
		SERVER_SOFTWARE VARCHAR(255),
		SERVER_PROTOCOL VARCHAR(20),
		REQUEST_METHOD VARCHAR(4),
		REQUEST_TIME_FLOAT VARCHAR(16),
		QUERY_STRING VARCHAR(100),
		DOCUMENT_ROOT VARCHAR(260),
		HTTP_ACCEPT VARCHAR(255),
		HTTP_ACCEPT_CHARSET_VAL VARCHAR(100),
		HTTP_ACCEPT_ENCODING VARCHAR(100),
		HTTP_ACCEPT_LANGUAGE VARCHAR(100),
		HTTP_CONNECTION VARCHAR(60),
		HTTP_HOST VARCHAR(260),
		HTTP_REFERER VARCHAR(100),
		HTTP_USER_AGENT VARCHAR(255),
		HTTPS VARCHAR(3),
		REMOTE_ADDR VARCHAR(45),
		REMOTE_HOST VARCHAR(260),
		REMOTE_PORT INT(5),
		REMOTE_USER VARCHAR(2083),
		REDIRECT_REMOTE_USER VARCHAR(100),
		SCRIPT_FILENAME	VARCHAR(260),
		SERVER_PORT INT(5),
		SERVER_SIGNATURE VARCHAR(255),
		PATH_TRANSLATED VARCHAR(100),
		SCRIPT_NAME VARCHAR(260),
		REQUEST_URI	VARCHAR(2083),
		PHP_AUTH_DIGEST	VARCHAR(100),
		PHP_AUTH_USER VARCHAR(100),
		PHP_AUTH_PW	VARCHAR(100),
		AUTH_TYPE VARCHAR(100),
		PATH_INFO VARCHAR(100),
		ORIG_PATH_INFO VARCHAR(100),	
		GENERAL_INFO TEXT,	
		COOKIES TEXT,
		FORWARD_IP VARCHAR(45),
		PRIMARY KEY (id)
	);";
	if ($conn->query($sql) === TRUE) { echo "Table created successfully"; } 
	else { echo "Error creating table: " . $conn->error; }
	$conn->close();
	
}
function create_users_table(){
	$conn=mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);
	if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
	
	$sql = "CREATE TABLE IF NOT EXISTS users (
			id INT(11) NOT NULL,
			PRIMARY KEY (id)
		);";//MFA = phone,email,authentication_app,file,facial,bio-metric,pin
	if ($conn->query($sql) === TRUE) { echo "Table created successfully";} 
	else { echo "Error creating table: " . $conn->error; }
	
}
function create_authentication_table(){
	$conn=mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);
	if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
	
	$sql = "CREATE TABLE IF NOT EXISTS authentication (
			id INT(11) NOT NULL AUTO_INCREMENT,
			username VARCHAR(255),
			password VARCHAR(128),
			security_file VARCHAR(128),
			email VARCHAR(255),
			recovery_email VARCHAR(255),
			phone VARCHAR(32),
			ip VARCHAR(45),
			geo_region VARCHAR(90),
			is_admin TINYINT(1) DEFAULT 0,
			permissions VARCHAR(255) DEFAULT 0,
			login_attempts INT(3),
			mfa_types VARCHAR(72) DEFAULT NULL,
			distress_pwd VARCHAR(128) DEFAULT NULL,
			distress_file VARCHAR(128) DEFAULT NULL,
			creation_time DATETIME DEFAULT CURRENT_TIMESTAMP,
			modification_time DATETIME ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
		);";//MFA = phone,email,authentication_app,file,facial,bio-metric,pin
	if ($conn->query($sql) === TRUE) { echo "Table created successfully";} 
	else { echo "Error creating table: " . $conn->error; }
	$conn->close();
	
}
create_users_table();
create_authentication_table();
create_auth_tokens_table();
create_logs_table();
?>