<!DOCTYPE html>
<html>
<body>

<form action="create_user_account.php" method="post" enctype="multipart/form-data">
    Select Security File to upload (this can be used in place, or along side a password):<br>
    <input type="file" 		name="sec_file" 		id="id_sec_file"><br>
	Distress file (A file that will give a fake login when used). <br>
	<input type="file"		name="dist_file" 		id="dist_file"><br>
	Username: <br>
	<input type="username" 	name="username" 		id="id_username"><br>
	Password: <br>
	<input type="password" 	name="password" 		id="id_password"><br>
	Distress Password: <br>
	<input type="password" 	name="distress_pwd" 		id="id_distress_pwd"><br>
	Email: <br>
	<input type="email" 	name="email" 			id="id_email"><br>
	Recovery Email (backup email): <br>
	<input type="email" 	name="recovery_email" 	id="id_recovery_email"><br>
	Phone:<br>
	<input type="tel" 	name="phone" 	id="id_phone"><br>
	
	
    
	
	<input type="submit" value="Create Account" name="submit">
</form>

</body>
</html>