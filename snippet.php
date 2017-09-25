include_once 'resource/send-mail.php';

$user_id = $db->lastInsertId();
$encode_id = base64_encode("encodeuserid{$$user_id}");

//prepare email body
$mail_body = '<html>
<body style="background-color:#CCCCCC; color:#000; font-family: Arial, Helvetica, sans-serif;
                    line-height:1.8em;">
<h2>User Authentication: Code A Secured Login System</h2>
<p>Dear '.$username.'<br><br>Thank you for registering, please click on the link below to
	confirm your email address</p>
<p><a href="http://auth.dev/activate.php?id='.$encode_id.'"> Confirm Email</a></p>
<p><strong>&copy;2016 ICT DesighHUB</strong></p>
</body>
</html>';

$mail->addAddress($email, $username);
$mail->Subject = " Message from ICT DesignHUB ";
$mail->Body = $mail_body;

//Error Handling for PHPMailer
if(!$mail->Send()){
$result = "<script type=\"text/javascript\">
                    swal(\"Error\",\" Email sending failed: $mail->ErrorInfo \",\"error\");</script>";
}
else{
$result = "<script type=\"text/javascript\">
                            swal({
                            title: \"Congratulations $username!\",
                            text: \"Registration Completed Successfully. Please check your email for confirmation link\",
                            type: 'success',
                            confirmButtonText: \"Thank You!\" });
                        </script>";
}


//activation
if(isset($_GET['id'])) {
$encoded_id = $_GET['id'];
$decode_id = base64_decode($encoded_id);
$user_id_array = explode("encodeuserid", $decode_id);
$id = $user_id_array[1];

$sql = "UPDATE users SET activated =:activated WHERE id=:id AND activated='0'";

$statement = $db->prepare($sql);
$statement->execute(array(':activated' => "1", ':id' => $id));

if ($statement->rowCount() == 1) {
$result = '<h2>Email Confirmed </h2>
<p>Your email address has been verified, you can now <a href="login.php">login</a> with your email and password.</p>';
} else {
$result = "<p class='lead'>No changes made please contact site admin,
    if you have not confirmed your email before</p>";
}
}