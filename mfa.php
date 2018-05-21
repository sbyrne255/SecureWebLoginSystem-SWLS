<?php

	function sendMail($username, $password, $reciever, $subject, $body){
		require(substr($_SERVER['DOCUMENT_ROOT'],0,42).'/PHPMailer/PHPMailerAutoload.php');//PHPMailerAutoload.php is a dependency.
		$mail = new PHPMailer(); // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; // or 587
		$mail->IsHTML(true);
		$mail->Username = $username;
		$mail->Password = $password;
		$mail->SetFrom("youremail");
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->AddAddress($reciever);
		$mail->Send();
		 }

?>