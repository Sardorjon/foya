<?php 
$page_title = "FOYA24 - Profilni yangilash";
include_once 'partials/headers.php';
include_once 'partials/parseprofile.php';
include_once 'partials/parsedeactivate.php';
?>
<div class="contanier">
    <section class="col col-lg-7">
        <h2>Profilni vaqtinchalik öchirish</h2> <hr>
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
            <input type="hidden" name="hidden_id" value="<?php if(isset($id)) echo $id; ?>">
            <input type="hidden" name="token" value="<?php if(function_exists('_token')) echo _token(); ?>">
            <button onclick="return confirm('Rostdan ham profilingizni vaqtinchalik öchirmoqchimisiz?')" type="submit" name="delAccountBtn" class="btn btn-danger btn-block float-right"> Profilni vaqtinchalik öchirmoq</button> <br>       
        </form>
        <?php endif ?>  
    </section>
</div>
<?php include_once 'partials/footers.php'; ?>
</body>
</html>