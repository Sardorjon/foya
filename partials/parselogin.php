<?php 
include_once 'resource/Database.php';
include_once 'resource/Utilities.php';

if (isset($_POST['loginBtn'], $_POST['token'])) {
    
    //validate the token
    if(valid_token($_POST['token'])){
        //process the form
        //array to display errors on login
 $form_errors = array();

 //validate must_fields
 $must_fields = array('username', 'password');
 $form_errors = array_merge ($form_errors, check_empty_fields($must_fields));

if(empty($form_errors)){
    //collecting form data from users
    $user =$_POST['username'];
    $password =$_POST ['password'];
    
    isset($_POST['remember'])? $remember =$_POST['remember']:$remember = "";
    
    // check if user exists in the database
    $sqlQuery ="SELECT*FROM users WHERE username =:username";
    $statement =$db ->prepare($sqlQuery);
    $statement -> execute(array(':username' =>$user));
    
    if($row =$statement->fetch()) {
        $id= $row['id'];
        $hashed_password = $row['password'];
        $username =$row['username'];
        
        $activated =$row['activated'];
        
        if ($activated ==="0"){
            
            if(checkDuplicateEntries('deactivation','user_id',$id, $db)){
                
                //activate the account
                $db ->exec("UPDATE users SET activated ='1' WHERE id=$id LIMIT 1");
                
                // remove the user from deactivation table
                $db ->exec("DELETE FROM deactivation WHERE user_id=$id LIMIT 1");
                
                // login the user
                prepLogin($id,$username,$remember);
                
            }else {
                $result = shortMessage("Iltimos oldin profilizni aktivlashtiring.");
            }             
        }else {
            if(password_verify($password,$hashed_password)) {                
                prepLogin($id,$username,$remember);               
        } else {
            $result = shortMessage("Siz notögri parol kiritdingiz.");
        }
        }
        
        
    } else {
        $result = shortMessage("Siz notögri user ismi kiritdingiz.");
    }
    
}else {
    if(count($form_errors) ==1){
        $result = shortMessage("Formda 1 ta xatolik böldi.");
    }else {
        $result =shortMessage("Formda ".count($form_errors)." ta xatoliklar böldi.");
        }
    }  
        
    }else{
        //display an error
           $result = "<script type=\"text/javascript\">
                        swal('Xatolik!','Bu sörov nomalum manba tomonidan yuborildi va bu saytga zarar yetkazuvchi xavf bölishi mumkin.', 'error');   
                    </script>";
    }   
      
}
