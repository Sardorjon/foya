<?php 
include_once 'resource/Database.php';
include_once 'resource/Utilities.php';
include_once 'resource/send-email.php';

if(isset($_POST['delAccountBtn'], $_POST['token'])){
    
    if(valid_token($_POST['token'])){
        
        //process the form
        $id = $_POST['hidden_id'];
        
        try{
            //Step 1: get the user info from the db
            $sqlQuery = "SELECT*FROM users WHERE id =:id";
            $statement =$db ->prepare($sqlQuery);
            $statement ->execute(array(':id' =>$id));
            
            if($row=$statement ->fetch()){
                
                //Step 2: deactivate the account
                
                    //collect the info first
                $username =$row['username'];
                $email =$row['email'];
                $user_id = $row['id'];
                
                $deactivateQuery =$db ->prepare ("UPDATE users SET activated =:activated WHERE id=:id");
                $deactivateQuery ->execute(array(':activated'=>'0', ':id'=>$user_id));
                
                if($deactivateQuery ->rowCount()===1){
                    //Step 3 Insert record into the deleted users table(deactivation)
                    $insertRecord = $db->prepare("INSERT INTO deactivation(user_id, deactivated_at) 
                                                        VALUES(:id, now())");
                    
                    $insertRecord ->execute(array(':id'=>$user_id));
                    
                    if($insertRecord ->rowCount()===1){
                        
                        //Step 4 send notification to user via email and display confirmation alert
                        
                         //prepare email body
                        $mail_body = '<html>
                        <body style="background-color:lightblue; color:#000; font-family: Arial, Helvetica, sans-serif;
                                            line-height:1.8em;">
                        <h2>FOYA24: something awesome has to happen</h2>
                        <p>Hurmatli '.$username.'<br><br>Siz profilingizni vaqtinchalik öchirishni(deaktivizatsiyalashni) söradingiz. Bu sörovingiz hal qilinadi. <br>Agar siz 14 kun ichida fikringizni özgartirib profilingizdan yana foydalanmoqchi bölsangiz, saytimiz quyidagi link orqali ulanishingiz mumkin.<p><a href="http://localhost/foya/login.php"> Saytga ulanmoq</a></p>.
                        <p> Agar siz 14 kun ichida profilingizdan qayta foydalanish istagi bölmasa, sizni barcha malumotlaringiz sayt bazasidan öchiriladi.</p>
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
                                                    title: \"Hurmatli, $username!\",
                                                    text: \"Sizning profil ma'lumotlaringiz 14 kun davomida saqlanadi. Agar 14 kun ichida saytdan yana foydalanish istagi bölmasa, profilingiz butunlay öchiriladi.\",
                                                    type: 'success',
                                                    timer:6000,
                                                    confirmButtonText: false});

                                                    setTimeout(function(){
                                                    window.location.href = 'logout.php';
                                                    },4000);
                                                    </script>";
                                                    }
                        
                    }else {
                        $result=shortMessage("Sörov oxirigacha yakunlanmadi, iltimos boshqatdan urinib köring.");
                    }
                }else {
                    $result=shortMessage("Sörov oxirigacha yakunlanmadi, iltimos boshqatdan urinib köring.");
                }
                
            }else {
                signout();
            }
            
            
        }catch (PDOException $ex){
            $result=shortMessage("Vooy, xatolik yuz berdi: ".$ex ->getMessage());
        }
    }else {
        //display an error
         $result = "<script type=\"text/javascript\">
                    swal('Xatolik!','Bu sörov nomalum manba tomonidan yuborildi va bu saytga zarar yetkazuvchi xavf bölishi mumkin.', 'error');                       
              </script>";
    }
}