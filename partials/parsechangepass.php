<?php
//add our database connection script
include_once 'resource/Database.php';
include_once 'resource/Utilities.php';

//process the form if the reset password button is clicked
if(isset($_POST['changePassBtn'], $_POST['token'])){
    
    if(valid_token($_POST['token'])){
        //process the form
        //initialize an array to store any error message from the form
    $form_errors = array();

    //Form validation
    $must_fields = array('current_password', 'new_password', 'confirm_password');

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
        $current_password = $_POST['current_password'];
        $password1 = $_POST['new_password'];
        $password2 = $_POST['confirm_password'];

        //kiritilgan parollar bir xilligini tekshirmoq
        if($password1 != $password2){
            $result = shortMessage("Yangi parol va tasqidlovchi parol bir xil emas. Iltimos boshqattan urinib köring.");
        }else{
            try{
                //process the request // check if the old password is correct
                $sqlQuery ="SELECT password FROM users WHERE id =:id";

                //use PDO prepared to sanitize data
                $statement = $db->prepare($sqlQuery);

                //execute the query
                $statement->execute(array(':id' => $id));
                
                //check if record is found
                
                if($row =$statement ->fetch()){
                    $password_from_db =$row['password'];
                    
                    if(password_verify($current_password, $password_from_db)){
                        
                         //hash the password
                    $hashed_password = password_hash($password1, PASSWORD_DEFAULT);

                    //SQL statement to update password
                    $sqlUpdate = "UPDATE users SET password =:password WHERE id=:id";

                    //use PDO prepared to sanitize SQL statement
                    $statement = $db->prepare($sqlUpdate);

                    //execute the statement
                    $statement->execute(array(':password' => $hashed_password, ':id' => $id));
                    
                    if($statement->rowCount() == 1){
                    
                         $result = "<script type=\"text/javascript\">
                                        swal({
                                            title: \"Tabriklaymiz!\",
                                            text:  \"Siz parolingizni muvaffaqiyatli yangiladingiz!\",
                                            type: 'success',
                                            confirmButtonText: \"Okay!\"});        
                                    </script>";
                                 
                        } else{
                            $result = shortMessage("Hech qanday özgarish qilinmadi.");
                    }         
                        
                    }else {
                        $result = "<script type=\"text/javascript\">
                            swal({
                                title: \"Voy!\",
                                text:  \"Kiritilgan joriy parol tög'ri emas. Iltimos qaytadan urinib köring!\",
                                type: 'error',
                                confirmButtonText: \"Okay!\"});        
                             </script>";
                    }
                }else{
                    signout();
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
    
}
