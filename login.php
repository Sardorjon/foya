
<?php $page_title="FOYA24 - Ulanish sahifasi";
include_once 'partials/headers.php';
include_once 'partials/parselogin.php';
?>
<div class="container">
    <section class="col col-lg-7"> 
        
    <h2>Ulanish formi</h2><hr>
    <div>
    <?php if(isset($result)) echo $result; ?>
    <?php if(!empty($form_errors)) echo show_errors ($form_errors); ?>
    </div>
    <div class="clearfix"></div>
    
<form action="" method="post">
  <div class="form-group">
    <label for="usernameField">Username</label>
    <input type="text" class="form-control" id="usernameField" name="username" placeholder="User ismi">
  </div>
  <div class="form-group">
    <label for="passwordField">Password</label>
    <input type="password" class="form-control" name="password" id="passwordField" placeholder="Parol">
  </div>  
  <div class="checkbox">
    <label>
      <input name="remember"´value="yes" type="checkbox"> Meni eslab qol
    </label> 
    <a href="pass_reset_link.php" class="float-right">Parolni unutdizmi?</a>
  </div>
  <input type="hidden" name="token" value="<?php if(function_exists('_token')) echo _token(); ?>">

  <button type="submit" name="loginBtn" class="btn btn-primary float-right">Ulanmoq</button>
</form>

</section>
</div>
<?php include_once 'partials/footers.php'; ?>
</body>
</html>