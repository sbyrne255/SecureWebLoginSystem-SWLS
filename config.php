<?php
//This file should be used as the master config for everything configurable without adjusting code//
	define('DBUSER','your_db_username');//User to connect to database
	define('DBPWD','your_db_password');//Password for user to connect to db
	define('DBHOST','your_db_host');//IP or name of database server
	define('DBNAME','your_db_name');//Database name
	define('AES_KEY', "your_super_secret_private_encryption_key");//key used to encrypt the hashes of passwords before inserting into db.
	
	define('PASSWORD_FILE','password_list.txt');//Relative path to password files of NON allowed passwords, one per line.
	define("MIN_PASSWORD_LENGTH", 7);//inclusive, so 1234567 would be valid.
	define('ENFORCE_PASSWORD_FILE_FOR_ALL', true);//Defines if the distress password needs to NOT be in the banned passwords file (true [can't be in the file], false [can be])
	define('FILE_CAN_REPLACES_PASSWORD', true);//If you use a security you do not need a password (true/yes, false/no)
	
	define('REQUIRED_FIELDS', ['username', 'password', 'email']);//Field names being posted that are required.
	define("CELL_MUST_BE_UNIQUE", false);//phone number must NOT be in the database already (authorization table)
	define("EMAIL_MUST_BE_UNIQUE", true);//email address must NOT be in the database already (authorization table)
	define("USERNAME_MUST_BE_UNIQUE", true);//username must NOT be in the database already (authorization table)
	
	define("DOMAIN", "robertsroom.com");//Domain in which the cookie is valid (your domain name)
	define("COOKIE_NAME", "rememberme");//name of the cookie, normally leave this at default.
	define("EXPIRE_TIME", time() + 864000);//Expiration time on remember me. (in seconds)
	define("HASH_METHOD", "sha384");//Method used for hashing passwords & files (absolute minimum sha256)
	define("HASH_COST", 12);//Absolute minimum should be 10, never go lower than this!
	
?>
