<?php
//add our database connection script
include_once 'resource/Database.php';
include_once 'resource/Utilities.php';
include_once 'resource/send-email.php';

//process the form if the reset password button is clicked
if(isset($_POST['passwordResetBtn'], $_POST['token'])){
    
    if(valid_token($_POST['token'])){
        //process the form
        //initialize an array to store any error message from the form
    $form_errors = array();

    //Form validation
    $must_fields = array( 'new_password', 'confirm_password');

    //call the function to check empty field and merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_empty_fields($must_fields));

    //Fields that requires checking for minimum length
    $fields_need_checking_length = array('new_password' => 6, 'confirm_password' => 6);

    //call the function to check minimum required length and merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_min_length($fields_need_checking_length));

    
    //check if error array is empty, if yes process form data and insert record
    if(empty($form_errors)){
        //collect form data and store in variables
        $id = $_POST['user_id'];
        $password1 = $_POST['new_password'];
        $password2 = $_POST['confirm_password'];

        //kiritilgan parollar bir xilligini tekshirmoq
        if($password1 != $password2){
            $result = "<p style='padding:20px; border: 1px solid gray; color: red;'> Yangi parol va tasqidlovchi parol bir xil emas. Iltimos boshqattan urinib köring.</p>";
        }else{
            try{
                //create SQL select statement to verify if email address input exist in the database
                $sqlQuery ="SELECT id FROM users WHERE id =:id";

                //use PDO prepared to sanitize data
                $statement = $db->prepare($sqlQuery);

                //execute the query
                $statement->execute(array(':id' => $id));

                //check if record exist
                if($statement->rowCount() == 1){
                    //hash the password
                    $hashed_password = password_hash($password1, PASSWORD_DEFAULT);

                    //SQL statement to update password
                    $sqlUpdate = "UPDATE users SET password =:password WHERE id=:id";

                    //use PDO prepared to sanitize SQL statement
                    $statement = $db->prepare($sqlUpdate);

                    //execute the statement
                    $statement->execute(array(':password' => $hashed_password, ':id' => $id));
                    
                    $result = "<script type=\"text/javascript\">
                    swal({
                        title: \"Tabriklaymiz!\",
                        text:  \"Siz parolingizni muvaffaqiyatli yangiladingiz!\",
                        type: 'success',
                        confirmButtonText: \"Okay!\"});        
                     </script>";

                    
                }
                else{
                     $result = "<script type=\"text/javascript\">
                    swal({
                        title: \"Voy!\",
                        text:  \"Siz kiritgan email manzili bizning bazamizda yöq. Iltimos qaytadan urinib köring!\",
                        type: 'error',
                        confirmButtonText: \"Okay!\"});        
                     </script>";
                }
            }catch (PDOException $ex){
                $result = "<p style='padding:20px; border: 1px solid gray; color: red;'> Vooy, xatolik yuz berdi: ".$ex ->getMessage()."</p>";
            }
        }
    }
    else{
        if(count($form_errors) == 1){
            $result = "<p style='color: red;'> Formda 1 ta xatolik böldi.<br>";
        }else{
            $result = "<p style='color: red;'> Formda ".count($form_errors)." ta xatoliklar böldi.<br>";
        }
    }
        
    }else {
        //display an error
         $result = "<script type=\"text/javascript\">
                    swal('Xatolik!','Bu sörov nomalum manba tomonidan yuborildi va bu saytga zarar yetkazuvchi xavf bölishi mumkin.', 'error');                       
              </script>";
        
    }
    
} else if(isset($_POST['passRecoveryBtn'], $_POST['token'])){
    
    if(valid_token($_POST['token'])){
        //process the form
        //initialize an array to store any error message from the form
    $form_errors = array();
    
    //form validation
    $must_fields = array('email');
    
    //call the function to check empty field and merge the data in form_error array
    $form_errors =array_merge($form_errors, check_empty_fields($must_fields));
    
     //email validation / merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_email());

    //check if error array is empty, if yes process form data and insert record
    if(empty($form_errors)){
        //collect form data and store in variables
        $email = $_POST['email'];
        
        try{
                //create SQL select statement to verify if email address input exist in the database
                $sqlQuery ="SELECT * FROM users WHERE email =:email";

                //use PDO prepared to sanitize data
                $statement = $db->prepare($sqlQuery);

                //execute the query
                $statement->execute(array(':email' => $email));
            
                
                //check if record exist 
                if($rs =$statement ->fetch()){
                    $username =$rs['username'];
                    $email =$rs['email'];
                    $user_id =$rs['id'];
                    $encode_id = base64_encode("encodeuserid{$user_id}");
                    
                    //prepare email body
                    $mail_body = '<html>
                    <body style="background-color:lightblue; color:#000; font-family: Arial, Helvetica, sans-serif;
                                        line-height:1.8em;">
                    <h2>FOYA24: something cool has to happen.</h2>
                    <p>Hurmatli '.$username.'<br><br>Parolingizni qayta tiklash uchun iltimos quyidagi linkka bosing.</p>
                    <p><a href="http://localhost/foya/forgot_pass.php?id='.$encode_id.'"> Parolni yangilamoq</a></p>
                    <p><strong>&copy;2017 FOYA</strong></p>
                    </body>
                    </html>';

                    $mail ->addAddress($email,$username);
                    $mail ->Subject = "Parolni qayta tiklash linki";
                    $mail ->Body =$mail_body;

                    //Error Handling for PHPMailer
                    if(!$mail->Send()){
                    $result = "<script type=\"text/javascript\">
                                        swal(\"Xatolik!\",\" Email jönatishda xatolik böldi: $mail->ErrorInfo \",\"error\");</script>";
                    }else{
                    $result = "<script type=\"text/javascript\">
                                    swal({
                                    title: \"Super!\",
                                    text: \"Sizga parolni qayta tiklash linki yuborildi. Iltimos emailingizni tekshiring.\",
                                    type: 'success',
                                    confirmButtonText: \"Rahmat!\" });
                                </script>";
                                }            
                } else {
                    $result ="<script type=\"text/javascript\">
                                    swal({
                                    title: \"Voy!\",
                                    text: \"Siz kiritgan email manzil bizning bazamizda yöq. Iltimos qayta harakat qilib köring.\",
                                    type: 'error',
                                    confirmButtonText: \"Okay\" });
                                </script>";
                        }
            
                    }  catch (PDOException $ex){
                            $result = shortMessage("Voy, xato sodir böldi: " .$ex->getMessage());
                    }
        
    }               else{
                            if(count($form_errors) ==1) {
                            $result = shortMessage("Formda 1 ta xato böldi <br>");

                        }else{
                            $result = shortMessage("Formda ".count($form_errors)." xatolar böldi.<br>");

          }
    } 
    }else {
        //display an error
         $result = "<script type=\"text/javascript\">
                    swal('Xatolik!','Bu sörov nomalum manba tomonidan yuborildi va bu saytga zarar yetkazuvchi xavf bölishi mumkin.', 'error');                       
              </script>";
    }
    
}
