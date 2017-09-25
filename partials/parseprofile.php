<?php 
include_once 'resource/Database.php';
include_once 'resource/Utilities.php';


if((isset($_SESSION['id']) ||isset($_GET['user_identity'])) && !isset ($_POST['updateProfileBtn'])){
    if(isset($_GET['user_identity'])){
        $url_encoded_id = $_GET['user_identity'];
        $decode_id =base64_decode ($url_encoded_id);
        $user_id_array =explode ("encodeuserid", $decode_id);
        $id =$user_id_array[1];
    }else {
       $id = $_SESSION['id']; 
    }
        
    $sqlQuery = "SELECT*FROM users WHERE id=:id";
    $statement = $db->prepare($sqlQuery);
    $statement->execute(array(':id' =>$id));
    
    while($rs = $statement->fetch()) {
        $username =$rs['username'];
        $email =$rs['email'];
        $date_joined =strftime("%b. %d. %Y",strtotime($rs['join_date']));
    }
        $user_pic ="upload/".$username.".jpg";
        $default ="upload/default.jpg";
        
        if(file_exists($user_pic)){
            $profile_picture =$user_pic;
        }else {
            $profile_picture =$default;
        }
    
        $encode_id =base64_encode("encodeuserid{$id}");
    
} else if(isset($_POST['updateProfileBtn'], $_POST['token'])){
    
    if(valid_token($_POST['token'])){
        
         // initialize an array to store error messages from the form
    $form_errors = array();
    
    //form validation
    $must_fields = array('email','username');
    
    //call the function to check empty field and merge the return data into form_error array
    $form_errors = array_merge ($form_errors, check_empty_fields($must_fields));
    
    //fields that requires checking for min length
    $fields_need_checking_length =array('username'=>4);
    
    //call the function to check min must length and merge data into form_error array
    $form_errors =array_merge($form_errors,check_min_length($fields_need_checking_length));
    
    //email validation&merge data into form_error array
    $form_errors=array_merge($form_errors, check_email($_POST));
    
    //validate if file has a valid extension
    
    isset($_FILES['avatar']['name']) ? $avatar =$_FILES['avatar']['name'] : $avatar =null;
    if($avatar !=null) {
        $form_errors =array_merge($form_errors, isValidPic($avatar));
    }
    
    //collect form data and store in variables
        
    $email =$_POST['email'];
    $username =$_POST['username'];
    $hidden_id =$_POST['hidden_id'];
    
    if(empty($form_errors)){
        try{
            //create SQL update statement
            $sqlUpdate ="UPDATE users SET username=:username, email=:email WHERE id=:id";
            
            //use PDO prepared to sanitize data
            $statement = $db->prepare($sqlUpdate);
            
            //update the record in the database
            
            $statement -> execute(array(':username' =>$username, ':email'=>$email, ':id' =>$hidden_id));
            
            //check if one new row was created
            if($statement ->rowCount()==1 || uploadAvatar($username)){
                $result = "<script type=\"text/javascript\">
                    swal({
                        title: \"Yangilandi\",
                        text:  \"Siz profilizni muvaffaqiyatli yangiladiz!\",
                        type: 'success',
                        confirmButtonText: \"Rahmat!\"});        
                     </script>"; 
            }else{
                $result = "<script type=\"text/javascript\">
                    swal({
                        title: \"Özgarish bölmadi\",
                        text:  \"Siz profilingizga hech qanday özgarish qilmadingiz.\",
                        type: 'warning',
                        confirmButtonText: \"Ok\"});        
                     </script>"; 
            }
        }catch(PDOException $ex){
            $result =shortMessage("Voy xato sodir böldi: ".$ex->getMessage());
        }
    }else {
        if(count($form_errors)==1){
            $result =shortMessage("Formda 1 ta xato böldi <br>");
        }else {
            $result =shortMessage("Formda ".count($form_errors)." xatoliklar böldi.<br>");
            }
        }    
        
    }else {
        // display an error message
        
        $result = "<script type=\"text/javascript\">
                    swal('Xatolik!','Bu sörov nomalum manba tomonidan yuborildi va bu saytga zarar yetkazuvchi xavf bölishi mumkin.', 'error');                       
              </script>";
    }
   
}