<?php $page_title="FOYA24 - A'zo bölish sahifasi";
    include_once 'partials/headers.php';
    include_once 'partials/parsesignup.php';
?>
    <div class="container">
    <section class="col col-lg-7"> 
        
    <h2>A'zo bölish formi</h2><hr>        
    <div>
    <?php if(isset($result)) echo $result; ?>
    <?php if(!empty($form_errors)) echo show_errors ($form_errors); ?>
    </div>
    <div class="clearfix"></div>
    
<form action="" method="post">
  <div class="form-group">
    <label for="usernameField">Username</label>
    <input type="text" class="form-control" id="usernameField" name="username" placeholder="User ism">
  </div>
  <div class="form-group">
    <label for="emailField">Email</label>
    <input type="email" class="form-control" id="emailField" name="email" placeholder="Email manzil">
  </div>
  <div class="form-group">
    <label for="passwordField">Password</label>
    <input type="password" class="form-control" name="password" id="passwordField" placeholder="Parol">
  </div> 
  <input type="hidden" name="token" value="<?php if(function_exists('_token')) echo _token(); ?>">
  <button type="submit" name="signupBtn" class="btn btn-primary float-right">A'zo bölmoq</button>
</form>
<p><a href="index.php">Orqaga</a></p>
</section>
</div>
<?php include_once 'partials/footers.php'; ?>
</body>
</html>