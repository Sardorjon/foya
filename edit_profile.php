<?php 
$page_title = "FOYA24 - Profilni yangilash";
include_once 'partials/headers.php';
include_once 'partials/parseprofile.php';

?>
<div class="contanier">
    <section class="col col-lg-7">
        <h2>Profilni yangilash</h2> <hr>
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
                    <label for="emailField">Email manzil</label>
                    <input type="text" name="email" value="<?php if(isset($email)) echo $email; ?>" class="form-control" id="emailField">
                </div>
                <div class="form-group">
                    <label for="usernameField">User ismi</label>
                    <input type="text" name="username" value="<?php if(isset($username)) echo $username; ?>" class="form-control" id="usernameField" >
                </div>
                
                 <div class="form-group">
                    <label for="fileField">Avatar</label><br>
                    <input type="file" name="avatar" id="fileField">
                </div>                
                <input type="hidden" name="hidden_id" value="<?php if(isset($id)) echo $id; ?>">
                <input type="hidden" name="token" value="<?php if(function_exists('_token')) echo _token(); ?>">
                <button type="submit" name="updateProfileBtn" class="btn btn-primary float-right"> Profilni yangilamoq</button>           
            </form><br>       
        <?php endif ?>  
    </section>
</div>
<?php include_once 'partials/footers.php'; ?>
</body>
</html>