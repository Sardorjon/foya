<?php 
include_once 'resource/Database.php';
include_once 'resource/Utilities.php';

try {
    $sqlQuery =$db ->query ("SELECT user_id FROM deactivation WHERE deactivated_at <= CURRENT_DATE - INTERVAL 14 DAY");
    
    while ($rs = $sqlQuery ->fetch()){
        //get record from users table
        $user_id =$rs['user_id'];
        
        $userRecord =$db->prepare("SELECT*FROM users WHERE id=:id");
        $userRecord ->execute(array(':id' =>$user_id));
        
        if($row =$userRecord ->fetch()){
            $username =$row['username'];
            $id =$row['id'];
            
            $user_pic ="uploads/".$username.".jpg";
            
            if(file_exists($user_pic)){
                unlink($user_pic);
            }
            
            $db ->exec("DELETE FROM deactivation WHERE user_id =$id LIMIT 1");
            $result =$db ->exec("DELETE FROM users WHERE id =$id AND activated='0' LIMIT 1");
            
            echo "$result Profile öchirildi";
            //email admin or write to your log file
            
        
        }
    
    }
    
    
    
}catch(PDOException $ex) {
    
}


try {
    $sqlQuery1 =$db ->query ("SELECT id, username FROM users WHERE join_date <= CURRENT_DATE - INTERVAL 3 DAY
                                AND activated='0'");
    
    while ($rs = $sqlQuery1 ->fetch()){
        //get record from users table
        $user_id =$rs['id'];
        $username =$rs['username'];
            
        //check if a row exist in deactivation table
        if(!checkDuplicateEntries('deactivation', 'user_id', $user_id, $db)){
            $user_pic ="uploads/".$username.".jpg";
            
            if(file_exists($user_pic)){
                unlink($user_pic);
            }
            $result =$db ->exec("DELETE FROM users WHERE id =$user_id AND activated='0' LIMIT 1");
            
            echo "$result Profile öchirildi";
            //email admin or write to your log file
        }           
            
        
        }
    
    }catch(PDOException $ex) {
    
}