<?php
//database connection script
include_once 'resource/Database.php';
include_once 'resource/Utilities.php';
include_once 'resource/send-email.php';
//process the form
if(isset($_POST['signupBtn'], $_POST['token'])){
    
    if(valid_token($_POST['token'])){
        //process the form
         // initialize an array to store any error message from the form
    $form_errors = array();
    
    //form validation
    $must_fields = array('username', 'email','password');
    
    //call for the function to check empty field and merge the return data into form_error_array
    $form_errors = array_merge ($form_errors, check_empty_fields($must_fields));
    
    //Fields that requires checking for min_length
    $fields_need_checking_length =array ('username'=>4, 'password'=>6);
    
    //Call the function to check minimum required length and merge thr return data into the form_error_array
    $form_errors =array_merge ($form_errors, check_min_length ($fields_need_checking_length));
    
    //email validation /merge the return data into form_error_array
    $form_errors = array_merge($form_errors, check_email());
    
    //collect form data and store in variables
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if(checkDuplicateEntries("users","email", $email, $db)){
        $result=shortMessage("Bu email manzil allaqachon olingan. Iltimos boshqa email manzil ishlating.");
    }
    else if(checkDuplicateEntries("users","username", $username, $db)){
        $result=shortMessage("Bu username allaqachon olingan. Iltimos boshqa username ishlating.");
    }
    //check if error erray is empty, if it is , process form data and insert record
    else if (empty($form_errors)) {
            
    //hashing the password
    $hashed_password =password_hash($password, PASSWORD_DEFAULT);
    
try{
    //create SQL insert statement
    $sqlInsert = "INSERT INTO users (username,email,password, join_date)
                  VALUES (:username, :email, :password, now())";
    
    // Use PDO prepared to sanitize data    
    $statement = $db->prepare($sqlInsert);
    
    //add the data into the database
    $statement ->execute(array(':username'=>$username, ':email'=>$email, ':password' =>$hashed_password));
    
    //check if one new row was created
    if($statement->rowCount()==1) {
        
        //get the last inserted ID
        $user_id = $db->lastInsertID();
        
        //encode the ID
        $encode_id =  base64_encode("encodeuserid{$user_id}");
        
        //prepare email body
        $mail_body = '<html>
        <body style="background-color:#CCCCCC; color:#000; font-family: Arial, Helvetica, sans-serif;
                            line-height:1.8em;">
        <h2>FOYA24: something awesome has to happen</h2>
        <p>Hurmatli '.$username.'<br><br>Saytimizga registratsiya qilganingiz uchun rahmat! Email manzilingizni tasdiqlash uchun iltimos quydagi linkka bosing.</p>
        <p><a href="http://localhost/foya/activate.php?id='.$encode_id.'"> Email manzilni tasdiqlamoq</a></p>
        <p><strong>&copy;2017 FOYA</strong></p>
        </body>
        </html>';
        
        $mail ->addAddress($email,$username);
        $mail ->Subject = "FOYA24 dan xabar";
        $mail ->Body =$mail_body;
        
        //Error Handling for PHPMailer
        if(!$mail->Send()){
        $result = "<script type=\"text/javascript\">
                            swal(\"Error\",\" Email jönatishda xatolik böldi: $mail->ErrorInfo \",\"error\");</script>";
        }
        else{
        $result = "<script type=\"text/javascript\">
                                    swal({
                                    title: \"Tabriklaymiz, $username!\",
                                    text: \"Siz saytga muvaffaqiyatli a'zo böldingiz. Tasdiqlash linki uchun iltimos emailingizni tekshiring.\",
                                    type: 'success',
                                    confirmButtonText: \"Rahmat!\" });
                                </script>";
        }
                
    }
} catch (PDOException $ex){
     $result = shortMessage("Voy, xato sodir böldi: " .$ex->getMessage());
    }
}
    else{
        if(count($form_errors) ==1) {
            $result = shortMessage("Formda 1 ta xato böldi <br>");
           
        }else{
            $result = shortMessage("Formda ".count($form_errors)." xatolar böldi.<br>");
     
        }
    }
        
    }else {
        // display error message
        $result = "<script type=\"text/javascript\">
                    swal('Xatolik!','Bu sörov nomalum manba tomonidan yuborildi va bu saytga zarar yetkazuvchi xavf bölishi mumkin.', 'error');                       
              </script>";
    }
   
}    

//activation

else if(isset($_GET['id'])) {
$encoded_id = $_GET['id'];
$decode_id = base64_decode($encoded_id);
$user_id_array = explode("encodeuserid", $decode_id);
$id = $user_id_array[1];

$sql = "UPDATE users SET activated =:activated WHERE id=:id AND activated='0'";

$statement = $db->prepare($sql);
$statement->execute(array(':activated' => "1", ':id' => $id));

if ($statement->rowCount() == 1) {
$result = '<h2 style="text-align:center;">Email manzil tasdiqlandi!</h2>
        <p style="text-align:center;">Sizning email manzilingiz tasdiqlandi va endi siz saytga ulana olasiz. <a href="login.php">Saytga ulanmoq</a></p>';
} else {
$result = "<p>Hech qanday özgarish bölmadi. Agar siz hali email manzilingizni tasdiqlatmagan bölsangiz, iltimos sayt adminiga murojat qiling.</p>";
}
}