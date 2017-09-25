<?php 
$page_title = "FOYA24 - Profilni yangilash";
include_once 'partials/headers.php';
include_once 'partials/parseprofile.php';
include_once 'partials/parsechangepass.php';

?>
<div class="contanier">
    <section class="col col-lg-7">
        <h2>Parolni yangilash</h2> <hr>
        <div>
            <?php if(isset($result)) echo $result; ?>
            <?php if(!empty($form_errors)) echo show_errors ($form_errors); ?>
        </div>
        <div class="clearfix"></div>
        
        <?php if(!isset($_SESSION['username'])):?>
        <p class="lead"> Sizda hozirda bu sahifani körish imkoni yöq. Iltimos oldin <a href="login.php">saytga ulaning</a> Hali saytga a'zo emasmisiz? <a href="signup.php">A'zo böling</a>
        </p>
        <?php else:?>           
        
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="joriyparolJoyi">Joriy Parol</label>
                <input type="password" name="current_password" class="form-control"
                       id="joriyparolJoyi" placeholder="Amaldagi parolni kiriting">
            </div>
            
            <div class="form-group">
                <label for="yangiparolJoyi">Yangi Parol</label>
                <input type="password" name="new_password" class="form-control"
                       id="yangiparolJoyi" placeholder="Yangi parol kiriting">
            </div>
            
            <div class="form-group">
                <label for="parolnitasdiqlashJoyi">Yangi Parolni Tasqdiqlash</label>
                <input type="password" name="confirm_password" class="form-control"
                       id="parolnitasdiqlashJoyi" placeholder="Yangi parolni tasdiqlang">
            </div>
            
            <input type="hidden" name="user_id" value="<?php if(isset($id)) echo $id; ?>">             
            <input type="hidden" name="token" value="<?php if(function_exists('_token')) echo _token(); ?>">
            <button type="submit" name="changePassBtn" class="btn btn-primary float-right"> Parolni yangilamoq</button><br> <br>               
        </form>
        
        
        <?php endif ?>  
    </section>
</div>
<?php include_once 'partials/footers.php'; ?>
</body>
</html>