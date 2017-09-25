<?php 
function check_empty_fields ($must_fields_array) {
    // initialize an array to store error messages from the form
    $form_errors = array();
    
    //loop through the must fields array and popular the form error array
    foreach($must_fields_array as $name_of_field){        if(!isset($_POST[$name_of_field])||$_POST[$name_of_field]==NULL){
            $form_errors[] =$name_of_field ." kiritilishi kerak";
        }
    }
    return $form_errors;
}

/**
*@param $fields_need_checking_length, an array containing the name of fields
*for which we want to check min required length e.g. array ('user ismi' =>4, 'email'=>12)
*@return array, containing all errors
*/ 

function check_min_length ($fields_need_checking_length) {
    // initialize an array to store error messages from the form
    $form_errors = array();
    
    foreach($fields_need_checking_length as $name_of_field => $min_length_required){
        if(strlen(trim($_POST[$name_of_field]))< $min_length_required){
            $form_errors[]= $name_of_field. " kamida {$min_length_required} belgi uzunligida bölishi kerak!";
        }
    }
    return $form_errors;
}
/**
*@param $data, store a key/value pair array where key is the name of the form control
*in this case 'email' and value is the input entered by the user
*@return array, containing all errrors
*/
function check_email() {
    // initialize an array to store error messages from the form
    $form_errors = array();
    $key ='email';
    //check if the email exist in data array
    if (array_key_exists($key, $_POST)){
        
        //check if the mail field has a value
        if($_POST[$key]!= null){
            
            //Avoid all illegal characters from email
            $key =filter_var($_POST[$key], FILTER_SANITIZE_EMAIL);
            
            //check if input is a valid email address
            if(filter_var($key, FILTER_VALIDATE_EMAIL) ===false) {
                $form_errors[] =$key. " tög'ri email manzili emas!";
            }
        }
    }
    return $form_errors;
}

/**
*@param $form_errors_array, the array holding all errors which we want to loop through @return string, list containing
*all error messages
*/

function show_errors ($form_errors_array) {
    $errors ="<p><ul style ='color:red;'>";
    
    //loop through error array and display all items in a list
    foreach($form_errors_array as $the_error) {
        $errors .="<li>{$the_error}</li>";
    }
    $errors.= "</ul></p>";
    return $errors;
}

function shortMessage($message, $passOrFail= "Fail") {
    if($passOrFail ==="Pass") {
        $data = "<div class= 'alert alert-success'>{$message}";
    }else {
        $data = "<div class = 'alert alert-warning'>{$message}";
    }
    return $data;
}

function redirectTo($page) {
    header("Location: {$page}.php");
}

function checkDuplicateEntries ($table, $column_name, $value, $db) {
    try {
        $sqlQuery ="SELECT*FROM $table WHERE $column_name=:$column_name";
        $statement =$db ->prepare($sqlQuery);
        $statement ->execute(array(":$column_name" =>$value));
        
        if($row =$statement ->fetch()){
            return true;
        }
        return false;
    }catch(PDOException $ex){
        //handle
        
    }
}

function eslabqol($user_id) {
    $encryptCookieData = base64_encode("Sardor290793Foya24BIGBoss{$user_id}");
    //cookie set to expire in abt 30 days
    setCookie("rememberUserCookie", $encryptCookieData, time()+30*30*24*100, "/" );
}
/*
*checked if the cookie used is the same with encrypted cookie
*$db, database connection link
*return bool, true if the user cookie is valid */
function isCookieValid($db){
    $isValid = false;
    
    if (isset($_COOKIE ['rememberUserCookie'])){
        // decode cookies and extract user ID
        $decryptCookieData = base64_decode($_COOKIE ['rememberUserCookie']);
        $user_id = explode ("Sardor290793Foya24BIGBoss", $decryptCookieData);
        $userID = $user_id[1];
        
        //check if id taken from the cookie exist in the database
        
        $sqlQuery = "SELECT*FROM users WHERE id =:id";
        $statement =$db ->prepare($sqlQuery);
        $statement -> execute(array(':id' =>$userID));
        
        if($row =$statement ->fetch()){
            $id =$row['id'];
            $username =$row ['username'];
            
            // create the user session variable
            $_SESSION['id'] =$id;
            $_SESSION['username'] =$username;
            $isValid = true;
        }else {
            //cookieID is invalid, destroy session and logout user
            $isValid = false;
            $this ->signout();
        }
    }
    return $isValid;
}
function signout(){
    unset($_SESSION['id']);
    unset($_SESSION['username']);
    
    if(isset($_COOKIE['rememberUserCookie'])) {
        unset($_COOKIE['rememberUserCookie']);
        setcookie('rememberUserCookie', null,-1, '/');
    }
    session_destroy();
    session_regenerate_id(true);
    redirectTo('index');
}

function safety(){
    $isValid =true;
    $inactive =60*3;
    $fingerprint =md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
    
    if((isset($_SESSION['fingerprint'])&& $_SESSION['fingerprint']!=$fingerprint)){
        $isValid= false;
        signout();
    }else if((isset($_SESSION['last_active']) && (time() - $_SESSION['last_active'])>$inactive) && $_SESSION['username']){
        $isValid= false;
        signout();
    }else {
        $_SESSION['last_active']=time();
    }
    return $isValid;
}

function isValidPic($file){
    
    $form_errors = array();
    
    //split file name into an array using the dot(.)
    $part =explode(".", $file);
    
    //targeting the last element in the array
    $extension =end($part);
    
    switch (strtolower($extension)){
        case 'jpg':
        case 'gif':
        case 'bmp':
        case 'png':
        case 'jpeg':
            
            return $form_errors;
    }
    $form_errors[] = $extension." bu ruxsat etilgan rasm formati emas.";
    return $form_errors;
}

function uploadAvatar($username) {
    $isPicMoved =false;
    if($_FILES['avatar']['tmp_name']){
        //file in the temp location
        $temp_file =$_FILES['avatar']['tmp_name'];
        $ds =DIRECTORY_SEPARATOR; //upload/
        $avatar_name =$username.".jpg";
        
        $path ="upload".$ds.$avatar_name; //upload/Sardorjon.jpg
        
        if(move_uploaded_file($temp_file,$path)){
            $isPicMoved = true;
        }
    }
    return $isPicMoved;
}

function _token (){
     $randomToken =base64_encode(openssl_random_pseudo_bytes(32));
   //$randomToken =md5(uniqid(rand(), true)). "md5";
    
    return $_SESSION['token'] =$randomToken;
}

function valid_token($requestToken){
    if(isset($_SESSION['token']) && $requestToken === $_SESSION['token']) {
        unset($_SESSION['token']);
        
        return true;
    }
return false;

}  

function prepLogin($id, $username, $remember){
     $_SESSION ['id'] =$id;
            $_SESSION ['username']=$username;
            
            $fingerprint =md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
            $_SESSION ['last_active'] =time();
            $_SESSION ['fingerprint'] =$fingerprint;
            
            if($remember ==="yes"){
                eslabqol($id);
            }
            
            //call sweetalert 
            echo $welcome = "<script type=\"text/javascript\">
                    swal({
                        title: \"Xush kelibsiz, $username!\",
                        text:  \"Siz saytga ulanyapsiz!\",
                        type: 'success',
                        timer:5000,
                        showConfirmButton: false});
                        
                        setTimeout(function(){
                        window.location.href = 'index.php';
                        },4000);
              </script>";
}