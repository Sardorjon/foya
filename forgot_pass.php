
<?php $page_title="FOYA - Parolni qayta tiklash";
include_once 'partials/headers.php';
include_once 'partials/parseforgot_pass.php';

if(isset($_GET['id'])){
    $encoded_id = $_GET['id'];
    $decoded_id = base64_decode($encoded_id);
    $id_array = explode("encodeuserid", $decoded_id);
    $id = $id_array[1];
}
?>

<div class="container">
    <section class="col col-lg-7"> 
        
    <h2>Parolni qayta tiklash formi</h2><hr>       
    <div>
    <?php if(isset($result)) echo $result; ?>
    <?php if(!empty($form_errors)) echo show_errors ($form_errors); ?>
    </div>
    <div class="clearfix"></div>
        
<form action="" method="post">
  <div class="form-group">
    <label for="passwordField">Yangi Parol</label>
    <input type="password" class="form-control" name="new_password" id="passwordField" placeholder="Yangi parol kiriting">
  </div>
  <div class="form-group">
    <label for="passwordField">Parolni Tasdiqlash</label>
    <input type="password" class="form-control" name="confirm_password" id="passwordField" placeholder="Yangi parolni tasdiqlang">
  </div>
  <input type="hidden" name="user_id" value="<?php if(isset($id)) echo $id; ?>">
  <input type="hidden" name="token" value="<?php if(function_exists('_token')) echo _token(); ?>">  
  <button type="submit" name="passwordResetBtn" class="btn float-right btn-success">Parolni yangilamoq</button>
</form>
<p><a href="index.php">Orqaga</a></p>
</section>
</div>
<?php include_once 'partials/footers.php'; ?>
</body>
</html>