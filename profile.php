<?php 
$page_title = "FOYA24 - Mening Profilim";
include_once 'partials/headers.php';
include_once 'partials/parseprofile.php';
?>
<div class="container">
    <div>
        <h1 style="padding-left:11px"> Mening Sahifam</h1><hr style="max-width:616px; margin-left:10px">
        <?php if(!isset($_SESSION['username'])):?>
        <p class="lead"> Sizda hozirda bu sahifani körish imkoni yöq. Iltimos oldin <a href="login.php">saytga ulaning</a> Hali saytga a'zo emasmisiz? <a href="signup.php">A'zo böling</a>
        </p>
        <?php else:?>
            <section class="col col-lg-7">
                <div>
                    <img style="margin-bottom:13px" src="<?php if(isset($profile_picture)) echo $profile_picture; ?>" class="img img-rounded" width="300";>                
                </div>
                <table class="table table-bordered table-condensed">
                    <tr><th style="width:30%">User ismi:</th><td><?php if(isset($username)) echo $username; ?></td></tr>
                    <tr><th style="">Email manzil:</th><td><?php if(isset($email)) echo $email; ?></td></tr>
                    <tr><th style="">A'zo bölgan sana:</th><td><?php if(isset($date_joined)) echo $date_joined; ?></td></tr>
                                            
                </table>
                <a class="" href="edit_profile.php?user_identity=<?php if(isset($encode_id)) echo $encode_id; ?>">
                    <span class="glyphicons glyphicons-edit" aria-hidden="true"></span>Profilni yangilamoq</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="" href="update_password.php?user_identity=<?php if(isset($encode_id)) echo $encode_id; ?>">
                    <span class="glyphicons glyphicons-edit" aria-hidden="true"></span>Parolnini yangilamoq</a>&nbsp;
                <a class="float-right alert-danger" href="deactivate_profile.php?user_identity=<?php if(isset($encode_id)) echo $encode_id; ?>">
                    <span class="glyphicons glyphicons-edit" aria-hidden="true"></span>Profilni vaqtinchalik öchirmoq</a>
               
            </section>
        <?php endif ?>            
                
    </div>
 </div> 
<?php include_once 'partials/footers.php'; ?>
</body>
</html>
    