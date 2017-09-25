<?php $page_title="FOYA - Parolni qayta tiklash";
include_once 'partials/headers.php';
include_once 'partials/parseforgot_pass.php';
?>

<div class="container">
<section class=" col col-lg-7">
    <h2> Parolni qayta tiklash</h2><hr>
    
    <div>
        <?php if(isset($result)) echo $result; ?>
        <?php if(!empty($form_errors)) echo show_errors ($form_errors); ?>
    </div>
    <div class="clearfix"></div>
    
    <p>Parolni qayta tiklash linkini olish uchun iltimos email manzilingizni kiriting.</p>
    <form action="" method="post">
         <div class="form-group">
            <label for="emailField">Email manzil</label>
            <input type="email" name="email" value="<?php if(isset($email)) echo $email; ?>" class="form-control" id="emailField" placeholder="Masalan: 1234@mail.ru">
        </div>
        <input type="hidden" name="token" value="<?php if(function_exists('_token')) echo _token(); ?>">
        <button type="submit" name="passRecoveryBtn" class="btn btn-primary float-right"> Parolni qayta tiklamoq</button> 
    </form>    
</section>
</div>
<?php include_once 'partials/footers.php'; ?>
</body>
</html>